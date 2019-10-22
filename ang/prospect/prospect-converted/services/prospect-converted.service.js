(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ProspectConverted', ProspectConverted);

  /**
   * Prospect converted service
   *
   * @param {object} crmApi crm api
   * @param {*} ProspectGlobalValues prospect global values
   */
  function ProspectConverted (crmApi, ProspectGlobalValues) {
    /**
     * Returns if the case is converted to Prospect
     *
     * @param {string} caseID case id
     * @returns {Promise} promise
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
     * @param {string} caseID case id
     * @returns {Promise} promise
     */
    this.getPaymentInfo = function (caseID) {
      return crmApi('ProspectConverted', 'getpaymentinfo', {
        prospect_case_id: caseID
      });
    };

    /**
     * Checks if Case Type Category is 'Prospecting'.
     *
     * @param {object} caseData case data
     * @returns {boolean} if prospect type category
     */
    this.checkIfProspectingCaseTypeCategory = function (caseData) {
      var caseTypeCategory = CRM.civicase.caseTypeCategories[caseData['case_type_id.case_type_category']].name;

      return caseTypeCategory === ProspectGlobalValues.caseTypeCategory;
    };
  }
})(angular, CRM.$, CRM._);
