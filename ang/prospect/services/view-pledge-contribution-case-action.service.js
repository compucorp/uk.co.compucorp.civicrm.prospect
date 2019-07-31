(function (angular, $, _) {
  var module = angular.module('prospect');

  module.service('ViewPledgeContributionCaseAction', ViewPledgeContributionCaseAction);

  function ViewPledgeContributionCaseAction ($location, crmApi, ProspectGlobalValues) {
    var prospectConverted = {
      isConverted: false,
      paymentInfo: {
        payment_completed: false,
        pledge_balance: false,
        payment_url: false,
        payment_entity: false
      }
    };

    /**
     * Refresh Data for the Service
     *
     * @param {array} cases
     */
    this.refreshData = function (cases) {
      if (cases[0]) {
        var caseID = cases[0].id;

        checkIfConvertedToProspect(caseID)
          .then(function () {
            getPaymentInfo(caseID);
          });
      }
    };

    /**
     * Checks if the Action is allowed
     *
     * @param {Object} action
     * @param {Array} cases
     * @return {boolean}
     */
    this.isActionAllowed = function (action, cases) {
      var isActionAllowed = action.type === 'pledge'
        ? prospectConverted.paymentInfo.payment_entity === 'pledge'
        : prospectConverted.paymentInfo.payment_entity === 'contribute';

      return checkIfProspectingCaseTypeCategory()
        && prospectConverted.isConverted && isActionAllowed;
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
      var url = prospectConverted.paymentInfo.payment_url + '&cid=' + contactID;

      CRM.loadForm(url);
    };

    /**
     * Checks if the Case is Converted to Prospect
     *
     * @param {String/Int} caseID
     * @return {Promise}
     */
    function checkIfConvertedToProspect (caseID) {
      return crmApi('ProspectConverted', 'get', {
        'sequential': 1,
        'prospect_case_id': caseID
      }).then(function (caseData) {
        prospectConverted.isConverted = caseData.count > 0;
      });
    }

    /**
     * Gets the payment information
     *
     * @param {String/Int} caseID
     */
    function getPaymentInfo (caseID) {
      if (prospectConverted.isConverted) {
        crmApi('ProspectConverted', 'getpaymentinfo', {
          'prospect_case_id': caseID
        }).then(function (paymentInfo) {
          prospectConverted.paymentInfo = paymentInfo;
        })
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
