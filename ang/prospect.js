(function(angular, $, _) {

  // Add the Extra Case Actions
  CRM.civicase.caseActions = CRM.civicase.caseActions.concat([
    {
      title: 'Convert to Pledge',
      action: 'convertProspectTo',
      type: 'pledge',
      icon: 'fa-pencil-square-o',
    },
    {
      title: 'Convert to Contribution',
      action: 'convertProspectTo',
      type: 'contribution',
      icon: 'fa-pencil-square-o',
    },
    {
      title: 'View Pledge',
      action: 'viewPledgeOrContribution',
      type: 'pledge',
      icon: 'fa-pencil-square-o',
    },
    {
      title: 'View Contribution',
      action: 'viewPledgeOrContribution',
      type: 'contribution',
      icon: 'fa-pencil-square-o',
    },
  ])

  // Declare a list of dependencies.
  angular.module('prospect', CRM.angRequires('prospect'));
  var module = angular.module('civicase');

  module.config(function ($provide) {
    $provide.decorator('civicaseCaseActionsDirective', civicaseCaseActionsDecorator);
  });

  function civicaseCaseActionsDecorator ($delegate, crmApi) {
    var directive = $delegate[0];
    var link = directive.link;

    directive.compile = function() {
      return function(scope, element, attrs) {
        link.apply(this, arguments);

        var prospectConverted = {
          isConverted: false,
          paymentInfo: {
            payment_completed: false,
            pledge_balance: false,
            payment_url: false,
            payment_entity: false
          }
        };

        (function init () {
          checkIfConvertedToProspect(scope.cases[0].id)
            .then(function () {
              if (prospectConverted.isConverted) {
                crmApi('ProspectConverted', 'getpaymentinfo', {
                  'prospect_case_id': scope.cases[0].id
                }).then(function (data) {
                  prospectConverted.paymentInfo = data;
                })
              }
            });
        }());

        function checkIfConvertedToProspect (caseID) {
          return crmApi('ProspectConverted', 'get', {
            'sequential': 1,
            'prospect_case_id': caseID
          }).then(function (caseData) {
            prospectConverted.isConverted = caseData.values.length > 0;
          });
        }


        scope.convertProspectTo = function (action) {
          if (action.type !== 'contribution' && action.type !== 'pledge') {
            return;
          }

          var caseId = scope.cases[0].id;
          var contactID = scope.cases[0].client[0].contact_id;
          var url = CRM.url('civicrm/contact/view/' + action.type, {
            action: 'add',
            reset: 1,
            cid: contactID,
            context: action.type,
            caseid: caseId
          });

          CRM.loadForm(url);
        }

        scope.isActionAllowedconvertProspectTo = function () {
          return !prospectConverted.isConverted;
        }

        scope.isActionAllowedviewPledgeOrContribution = function (action) {
          var isActionAllowed = action.type === 'pledge'
            ? prospectConverted.paymentInfo.payment_entity === 'pledge'
            : prospectConverted.paymentInfo.payment_entity === 'contribute';

          return prospectConverted.isConverted && isActionAllowed;
        }

        scope.viewPledgeOrContribution = function () {
          var contactID = scope.cases[0].client[0].contact_id;
          var url = prospectConverted.paymentInfo.payment_url + '&cid=' + contactID;
          CRM.loadForm(url);
        }
      };
    };

    return $delegate;

  }
})(angular, CRM.$, CRM._);
