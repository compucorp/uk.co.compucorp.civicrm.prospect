(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ConvertProspectCaseAction', ConvertProspectCaseAction);

  function ConvertProspectCaseAction ($location, crmApi, ProspectGlobalValues, ProspectConverted) {
    var isConvertedToProspect = false;

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
     */
    this.isActionAllowed = function (action) {
      var isPledgeOrContribution = _.includes(
        ['contribution', 'pledge'], action.type);

      return isPledgeOrContribution &&
        checkIfProspectingCaseTypeCategory() && !isConvertedToProspect;
    };

    /**
     * Click event handler for the Action
     *
     * @param {Array} cases
     * @param {Object} action
     * @param {Function} callbackFn
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

    /**
     * Check if Case Type Category is Prospecting.
     *
     * @return {Boolean}
     */
    function checkIfProspectingCaseTypeCategory () {
      var filtersQueryParams = $location.search().cf;

      if (!filtersQueryParams) {
        return false;
      }

      var caseTypeCategory = JSON.parse(filtersQueryParams).case_type_category;

      return caseTypeCategory === ProspectGlobalValues.caseTypeCategory;
    }
  }
})(angular, CRM.$, CRM._);
