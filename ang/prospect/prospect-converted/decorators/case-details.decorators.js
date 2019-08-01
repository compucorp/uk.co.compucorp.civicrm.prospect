(function (angular, $, _) {
  var module = angular.module('civicase');

  module.config(function ($provide) {
    $provide.decorator('civicaseCaseDetailsDirective', civicaseCaseDetailsDirectiveDecorator);
  });

  function civicaseCaseDetailsDirectiveDecorator ($delegate, ProspectConverted) {
    var civicaseCaseDetails = $delegate[0];

    civicaseCaseDetails.compile = function () {
      return function ($scope) {
        $scope.$on('updateCaseData', updateCaseListener);

        /**
         * Listener for `updateCaseData` event
         */
        function updateCaseListener () {
          var caseID = $scope.item.id;

          ProspectConverted.getProspectIsConverted(caseID)
            .then(function (isConverted) {
              if (!isConverted) {
                return;
              }

              ProspectConverted.getPaymentInfo(caseID)
                .then(addExtraProspectField);
            });
        }

        /**
         * Adds Extra Field to Financial Information Custom Group block
         *
         * @param {Object} paymentInfo
         */
        function addExtraProspectField (paymentInfo) {
          var financialInformationCustomField = _.find($scope.item.customData, function (customField) {
            return customField.name === 'Prospect_Financial_Information';
          });

          if (!financialInformationCustomField) {
            return;
          }

          var fieldToAdd = {};

          if (paymentInfo.payment_entity === 'pledge') {
            fieldToAdd = {
              label: 'Pledge balance',
              name: 'pledge_balance',
              value: { display: paymentInfo.pledge_balance }
            };
          } else if (paymentInfo.payment_entity === 'contribute') {
            fieldToAdd = {
              label: 'Payment completed',
              name: 'payment_completed',
              value: { display: paymentInfo.payment_completed }
            };
          }

          financialInformationCustomField.fields = financialInformationCustomField.fields.concat(fieldToAdd);
        }
      };
    };

    return $delegate;
  }
})(angular, CRM.$, CRM._);
