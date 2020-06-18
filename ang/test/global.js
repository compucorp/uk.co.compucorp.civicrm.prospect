/* eslint-env jasmine */
/* eslint no-param-reassign: "error" */

((CRM) => {
  CRM.civicase = {};
  CRM['civicase-base'] = {};
  CRM.angular = { requires: {} };

  /**
   * Dependency Injection for Prospect module, defined in ang/prospect.ang.php
   * For unit testing they needs to be mentioned here
   */
  CRM.angular.requires.prospect = ['civicase-base'];
})(CRM);
