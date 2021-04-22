(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ConvertProspectCaseAction', ConvertProspectCaseAction);

  /**
   * @param {object} ProspectConverted Prospect Converted Service
   */
  function ConvertProspectCaseAction (ProspectConverted) {
    var isConvertedToProspect = false;

    /**
     * Refresh Data for the Service
     *
     * @param {Array} cases cases
     */
    this.refreshData = function (cases) {
      if (!cases[0]) {
        return;
      }

      var caseID = cases[0].id;

      ProspectConverted.getProspectIsConverted(caseID)
        .then(function (isConverted) {
          isConvertedToProspect = isConverted;
        });
    };

    /**
     * Checks if the Action is allowed
     *
     * @param {object} action action
     * @param {Array} cases cases
     * @returns {boolean} if action is allowed
     */
    this.isActionAllowed = function (action, cases) {
      var isPledgeOrContribution = _.includes(
        ['contribution', 'pledge'], action.type);

      return cases[0] && isPledgeOrContribution &&
        ProspectConverted.checkIfSalesOpportunityTrackingWorkflow(cases[0]['case_type_id.case_type_category']) &&
        !isConvertedToProspect;
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
