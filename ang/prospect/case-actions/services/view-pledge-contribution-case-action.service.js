(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ViewPledgeContributionCaseAction', ViewPledgeContributionCaseAction);

  function ViewPledgeContributionCaseAction (
    $location, crmApi, ProspectGlobalValues, ProspectConverted) {
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
     * @param {array} cases
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
     * @param {Object} action
     * @param {Array} cases
     * @return {boolean}
     */
    this.isActionAllowed = function (action, cases) {
      var actionTypeMapping = {
        pledge: 'pledge', contribution: 'contribute'
      };
      var isPaymentTypeSameAsActionType =
        actionTypeMapping[action.type] === paymentInfo.payment_entity;

      return checkIfProspectingCaseTypeCategory() &&
        isConvertedToProspect && isPaymentTypeSameAsActionType;
    };

    /**
     * Click event handler for the Action
     *
     * @param {Array} cases
     * @param {Object} action
     * @param {Function} callbackFn
     */
    this.doAction = function (cases, action, callbackFn) {
      var contactID = cases[0].client[0].contact_id;

      return paymentInfo.payment_url + '&cid=' + contactID;
    };

    /**
     * Check if Case Type Category is Prospecting.
     *
     * @return {Boolean}
     */
    function checkIfProspectingCaseTypeCategory () {
      var filtersQueryParams = $location.search().cf;
      if (filtersQueryParams) {
        var caseTypeCategory = JSON.parse(filtersQueryParams).case_type_category;

        return caseTypeCategory === ProspectGlobalValues.caseTypeCategory;
      }
    }
  }
})(angular, CRM.$, CRM._);
