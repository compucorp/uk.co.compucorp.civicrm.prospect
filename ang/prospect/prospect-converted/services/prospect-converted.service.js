(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ProspectConverted', ProspectConverted);

  /**
   * Prospect converted service
   *
   * @param {object} crmApi crm api
   * @param {*} ProspectGlobalValues prospect global values
   * @param {object} CaseTypeCategory case type category service
   */
  function ProspectConverted (crmApi, ProspectGlobalValues, CaseTypeCategory) {
    /**
     * Returns the prospect converted value
     *
     * @param {string} caseID case id
     * @returns {Promise} promise
     */
    this.getProspectConvertedValue = function (caseID) {
      return crmApi('ProspectConverted', 'get', {
        sequential: 1,
        prospect_case_id: caseID
      }).then(function (prospect) {
        return prospect.values[0];
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
     * Checks if Case Type Category is Sales Opportunity Tracking type.
     *
     * @param {string} caseTypeCategoryID case type category id
     * @returns {boolean} if prospect type category
     */
    this.checkIfSalesOpportunityTrackingWorkflow = function (caseTypeCategoryID) {
      var instanceName = CaseTypeCategory.getCaseTypeCategoryInstance(caseTypeCategoryID).name;

      return instanceName === ProspectGlobalValues.instanceName;
    };
  }
})(angular, CRM.$, CRM._);
