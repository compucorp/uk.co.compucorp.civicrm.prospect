(function(angular, $, _) {
  // Add the Extra Case Actions
  CRM.civicase.caseActions = CRM.civicase.caseActions.concat([
    {
      title: 'Convert to Pledge',
      action: 'ConvertProspect',
      type: 'pledge',
      icon: 'fa-pencil-square-o',
    },
    {
      title: 'Convert to Contribution',
      action: 'ConvertProspect',
      type: 'contribution',
      icon: 'fa-pencil-square-o',
    },
    {
      title: 'View Pledge',
      action: 'ViewPledgeContribution',
      type: 'pledge',
      icon: 'fa-pencil-square-o',
    },
    {
      title: 'View Contribution',
      action: 'ViewPledgeContribution',
      type: 'contribution',
      icon: 'fa-pencil-square-o',
    },
  ])

  // Declare a list of dependencies.
  angular.module('prospect', CRM.angRequires('prospect'));
})(angular, CRM.$, CRM._);
