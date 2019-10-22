/* eslint no-param-reassign: "error" */

((CRM) => {
  const module = angular.module('prospect.data');

  CRM.civicase.caseTypeCategories = {
    1: { value: '1', label: 'Cases', name: 'Cases' },
    2: { value: '2', label: 'Prospecting', name: 'Prospecting' }
  };

  module.constant('CaseTypeCategories', CRM.civicase.caseTypeCategories);
})(CRM);
