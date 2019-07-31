(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ConvertProspectCaseAction', ConvertProspectCaseAction);

  function ConvertProspectCaseAction ($location, crmApi, ProspectGlobalValues) {
    var isConvertedToProspect = false;

    this.refreshData = checkIfConvertedToProspect;

    /**
     * Checks if the Action is allowed
     */
    this.isActionAllowed = function () {
      return checkIfProspectingCaseTypeCategory() && !isConvertedToProspect;
    };

    /**
     * Click event handler for the Action
     *
     * @param {Array} cases
     * @param {Object} action
     * @param {Function} callbackFn
     */
    this.doAction = function (cases, action, callbackFn) {
      if (action.type !== 'contribution' && action.type !== 'pledge') {
        return;
      }

      var caseId = cases[0].id;
      var contactID = cases[0].client[0].contact_id;
      var url = CRM.url('civicrm/contact/view/' + action.type, {
        action: 'add',
        reset: 1,
        cid: contactID,
        context: action.type,
        caseid: caseId
      });

      CRM.loadForm(url);
    };

    /**
     * Checks if the Case is Converted to Prospect
     *
     * @param {Array} cases
     * @return {Promise}
     */
    function checkIfConvertedToProspect (cases) {
      if (cases[0]) {
        var caseID = cases[0].id;

        return crmApi('ProspectConverted', 'get', {
          'sequential': 1,
          'prospect_case_id': caseID
        }).then(function (caseData) {
          isConvertedToProspect = caseData.values.length > 0;
        });
      }
    }

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
