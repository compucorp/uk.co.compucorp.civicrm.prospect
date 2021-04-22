(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ViewPledgeContributionCaseAction', ViewPledgeContributionCaseAction);

  /**
   * @param {object} ProspectConverted Prospect Converted Service
   */
  function ViewPledgeContributionCaseAction (ProspectConverted) {
    var isConvertedToProspect = false;
    var paymentInfo = {
      payment_completed: false,
      pledge_balance: false,
      payment_url: false,
      payment_entity: false
    };

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

          if (!isConvertedToProspect) {
            return;
          }
          ProspectConverted.getPaymentInfo(caseID)
            .then(function (info) {
              paymentInfo = info;
            });
        });
    };

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
      var actionTypeMapping = {
        pledge: 'pledge', contribution: 'contribute'
      };
      var isPaymentTypeSameAsActionType =
        actionTypeMapping[action.type] === paymentInfo.payment_entity;

      return cases[0] &&
        ProspectConverted.checkIfProspectManagementWorkflow(cases[0]['case_type_id.case_type_category']) &&
        isConvertedToProspect && isPaymentTypeSameAsActionType;
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

      return paymentInfo.payment_url + '&cid=' + contactID;
    };
  }
})(angular, CRM.$, CRM._);
