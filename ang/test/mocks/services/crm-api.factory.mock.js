/* eslint-env jasmine */

((angular) => {
  const module = angular.module('crmUtil');

  module.factory('crmApi', ['$q', ($q) => {
    const crmApi = jasmine.createSpy('crmApi');
    crmApi.and.returnValue($q.resolve());

    return crmApi;
  }]);
})(angular);
