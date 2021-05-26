(function (angular, $, _) {
  var module = angular.module('civicase');

  module.config(function ($provide) {
    $provide.decorator('civicaseCaseDetailsDirective', civicaseCaseDetailsDirectiveDecorator);
  });

  /**
   * Case Details Directive Decorator
   *
   * @param {object} $delegate delegate service
   * @param {object} ProspectConverted ProspectConverted
   * @returns {object} decorated directive
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
          $scope.item.prospect = {};

          var caseID = $scope.item.id;

          ProspectConverted.getProspectConvertedValue(caseID)
            .then(function (prospectConverted) {
              if (!prospectConverted) {
                return;
              }

              $scope.item.prospect.isProspectConverted = !!prospectConverted;
              $scope.item.prospect.id = prospectConverted.id;

              ProspectConverted.getPaymentInfo(caseID)
                .then(addExtraProspectField);
            });
        }

        /**
         * Adds Extra Field to 'Financial Information' Custom Group block
         *
         * @param {object} paymentInfo payment info
         */
        function addExtraProspectField (paymentInfo) {
          $scope.item.prospect.paymentInfo = paymentInfo;
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
         * @param {object} financialInformationCustomField financial information custom field
         * @param {string} fieldName field name
         * @param {object} fieldToAdd field to add
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
