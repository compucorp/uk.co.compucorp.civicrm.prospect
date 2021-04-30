(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ConvertProspectCaseAction', ConvertProspectCaseAction);

  /**
   * @param {object} ProspectConverted Prospect Converted Service
   */
  function ConvertProspectCaseAction (ProspectConverted) {
    /**
     * Checks if the Action is allowed
     *
     * @param {object} action action
     * @param {Array} cases cases
     * @returns {boolean} if action is allowed
     */
    this.isActionAllowed = function (action, cases) {
      if (!cases[0] || !cases[0].prospect) {
        return;
      }

      var isPledgeOrContribution = _.includes(
        ['contribution', 'pledge'], action.type);

      return isPledgeOrContribution &&
        ProspectConverted.checkIfSalesOpportunityTrackingWorkflow(cases[0]['case_type_id.case_type_category']) &&
        !cases[0].prospect.isProspectConverted;
    };

    /**
     * Click event handler for the Action
     *
     * @param {Array} cases cases
     * @param {object} action action
     * @param {Function} callbackFn call back function
     * @returns {string} url
     */
    this.doAction = function (cases, action, callbackFn) {
      var caseId = cases[0].id;
      var contactID = cases[0].client[0].contact_id;

      return {
        path: 'civicrm/contact/view/' + action.type,
        query: {
          action: 'add',
          reset: 1,
          cid: contactID,
          context: action.type,
          caseid: caseId
        }
      };
    };
  }
})(angular, CRM.$, CRM._);
