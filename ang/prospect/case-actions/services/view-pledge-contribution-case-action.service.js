(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ViewPledgeContributionCaseAction', ViewPledgeContributionCaseAction);

  /**
   * @param {object} ProspectConverted Prospect Converted Service
   */
  function ViewPledgeContributionCaseAction (ProspectConverted) {
    /**
     * Checks if the Action is allowed
     * Return true, if the
     *  Query String has Prospecting Case Type Category
     *  Case is Converted to Prospect
     *  Payment Entity matches the Action Type
     *
     * @param {object} action action
     * @param {Array} cases cases
     * @returns {boolean} if action is allowed
     */
    this.isActionAllowed = function (action, cases) {
      if (!cases[0] || !cases[0].prospect.paymentInfo) {
        return;
      }

      var actionTypeMapping = {
        pledge: 'pledge', contribution: 'contribute'
      };
      var isPaymentTypeSameAsActionType =
        actionTypeMapping[action.type] === cases[0].prospect.paymentInfo.payment_entity;

      return cases[0] &&
        ProspectConverted.checkIfSalesOpportunityTrackingWorkflow(cases[0]['case_type_id.case_type_category']) &&
        cases[0].prospect.isProspectConverted && isPaymentTypeSameAsActionType;
    };

    /**
     * Click event handler for the Action
     *
     * @param {Array} cases cases
     * @param {object} action action
     * @param {Function} callbackFn callback function
     * @returns {string} url
     */
    this.doAction = function (cases, action, callbackFn) {
      var contactID = cases[0].client[0].contact_id;

      return cases[0].prospect.paymentInfo.payment_url + '&cid=' + contactID;
    };
  }
})(angular, CRM.$, CRM._);
