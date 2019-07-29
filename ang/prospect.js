(function(angular, $, _) {
  // Declare a list of dependencies.
  var module = angular.module('prospect', CRM.angRequires('prospect'));

  // Add the Extra Case Actions
  CRM.civicase.caseActions = CRM.civicase.caseActions.concat([
    {
      title: 'Convert to Prospect',
      action: 'convertToProspect',
      icon: 'fa-pencil-square-o',
    },
  ])


  module.config(function ($provide) {
    $provide.decorator('civicaseCaseActionsDirective', civicaseCaseActionsDecorator);
  });

  function civicaseCaseActionsDecorator ($delegate) {
    var directive = $delegate[0];
    var link = directive.link;

    directive.compile = function() {
      return function(scope, element, attrs) {
        link.apply(this, arguments);

        scope.convertToProspect = function () {
          // Actions
        }
      };
    };

    return $delegate;

  }
})(angular, CRM.$, CRM._);
