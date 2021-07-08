(function (angular) {
  var module = angular.module('prospect');

  module.service('UnlinkContributionCaseAction', UnlinkContributionCaseAction);

  /**
   * @param {object} ProspectConverted Prospect Converted Service
   */
  function UnlinkContributionCaseAction (ProspectConverted) {
    /**
     * Checks if the Action is allowed
     *
     * @param {object} action action
     * @param {Array} cases cases
     * @returns {boolean} if action is allowed
     */
    this.isActionAllowed = function (action, cases) {
      if (!cases[0] || !cases[0].prospect.paymentInfo) {
        return;
      }

      var isContributeTypeProspect =
        cases[0].prospect.paymentInfo.payment_entity === 'contribute';

      if (!isContributeTypeProspect) {
        return;
      }

      return CRM.checkPerm('administer CiviProspecting') &&
        ProspectConverted.checkIfSalesOpportunityTrackingWorkflow(cases[0]['case_type_id.case_type_category']) &&
        cases[0].prospect.isProspectConverted;
    };

    /**
     * Click event handler for the Action
     *
     * @param {Array} cases cases
     * @param {object} action action
     * @param {Function} callbackFn call back function
     */
    this.doAction = function (cases, action, callbackFn) {
      var prospectID = cases[0].prospect.id;
      var contributionId = cases[0].prospect.paymentInfo.payment_entity_id;

      CRM.confirm({ title: action.title, message: 'Are you sure?' })
        .on('crmConfirm:yes', function () {
          var calls = [
            ['ProspectConverted', 'delete', { id: prospectID }],
            ['Contribution', 'create', { id: contributionId, contribution_status_id: 'Cancelled' }]
          ];

          callbackFn(calls);
        });
    };
  }
})(angular);
