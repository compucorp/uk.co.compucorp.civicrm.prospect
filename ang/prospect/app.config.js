(function (angular, $, _) {
  var module = angular.module('prospect');

  module.run(function () {
    (function init () {
      CRM.civicase.caseActions = CRM.civicase.caseActions.concat(getCaseActionsForProspect());
    }());

    /**
     * Get the Case Actions for Prospect.
     *
     * @return {Array}
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
        }
      ];
    }
  });
})(angular);
