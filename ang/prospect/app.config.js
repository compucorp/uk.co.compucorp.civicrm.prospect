(function (angular, $, _) {
  var module = angular.module('prospect');

  module.run(function () {
    (function init () {
      CRM.civicase.caseActions = getCaseActionsForProspect().concat(CRM.civicase.caseActions);
    }());

    /**
     * Get the Case Actions for Prospect.
     *
     * @returns {Array} case actions
     */
    function getCaseActionsForProspect () {
      return [
        {
          title: 'Convert to Pledge',
          action: 'ConvertProspect',
          type: 'pledge',
          icon: 'fa-exchange'
        },
        {
          title: 'Convert to Contribution',
          action: 'ConvertProspect',
          type: 'contribution',
          icon: 'fa-exchange'
        },
        {
          title: 'View Pledge',
          action: 'ViewPledgeContribution',
          type: 'pledge',
          icon: 'fa-list'
        },
        {
          title: 'View Contribution',
          action: 'ViewPledgeContribution',
          type: 'contribution',
          icon: 'fa-list'
        },
        {
          title: 'Unlink Contribution',
          action: 'UnlinkContribution',
          type: 'contribution',
          icon: 'fa-ban'
        }
      ];
    }
  });
})(angular);
