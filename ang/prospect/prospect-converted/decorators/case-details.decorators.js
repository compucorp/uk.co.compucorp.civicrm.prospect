(function (angular, $, _) {
  var module = angular.module('civicase');

  module.config(function ($provide) {
    $provide.decorator('civicaseCaseDetailsDirective', civicaseCaseDetailsDirectiveDecorator);
  });

  /**
   * Case Details Directive Decorator
   */
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
         * Adds Extra Field to 'Financial Information' Custom Group block
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

          if (paymentInfo.pledge_balance) {
            addUpdateCustomField(financialInformationCustomField, 'pledge_balance', {
              label: 'Pledge balance',
              name: 'pledge_balance',
              value: { display: paymentInfo.pledge_balance }
            });
          }

          if (paymentInfo.payment_completed) {
            addUpdateCustomField(financialInformationCustomField, 'payment_completed', {
              label: 'Payment completed',
              name: 'payment_completed',
              value: { display: paymentInfo.payment_completed }
            });
          }
        }

        /**
         * Adds or Updates Custom fields
         *
         * @param {Object} financialInformationCustomField
         * @param {String} fieldName
         * @param {Object} fieldToAdd
         */
        function addUpdateCustomField (financialInformationCustomField, fieldName, fieldToAdd) {
          var field = _.find(financialInformationCustomField.fields, function (customField) {
            return customField.name === fieldName;
          });

          if (field) {
            field.value = fieldToAdd.value;
          } else {
            financialInformationCustomField.fields = financialInformationCustomField.fields.concat(fieldToAdd);
          }
        }
      };
    };

    return $delegate;
  }
})(angular, CRM.$, CRM._);
