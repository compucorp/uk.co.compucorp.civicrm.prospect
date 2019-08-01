(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ProspectConverted', ProspectConverted);

  function ProspectConverted (crmApi) {
    /**
     * Returns if the case is converted to Prospect
     *
     * @param {string/int} caseID
     * @return {Promise}
     */
    this.getProspectIsConverted = function (caseID) {
      return crmApi('ProspectConverted', 'get', {
        sequential: 1,
        prospect_case_id: caseID
      }).then(function (caseData) {
        return caseData.count > 0;
      });
    };

    /**
     * Gets the payment information
     *
     * @param {String/Int} caseID
     * @return {Promise}
     */
    this.getPaymentInfo = function (caseID) {
      return crmApi('ProspectConverted', 'getpaymentinfo', {
        prospect_case_id: caseID
      });
    };
  }
})(angular, CRM.$, CRM._);
