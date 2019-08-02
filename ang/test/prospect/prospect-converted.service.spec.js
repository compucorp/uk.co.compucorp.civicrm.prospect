/* eslint-env jasmine */

(function (_, $) {
  describe('ProspectConverted', function () {
    var ProspectConverted, crmApiMock;

    beforeEach(module('prospect', function ($provide) {
      crmApiMock = jasmine.createSpy('crmApi');

      $provide.value('crmApi', crmApiMock);
    }));

    beforeEach(inject(function (_ProspectConverted_) {
      ProspectConverted = _ProspectConverted_;
    }));

    describe('Test', function () {
      it('ProspectConverted', function () {
        console.log(ProspectConverted);
        expect(1).toBe(1);
      });
    });
  });
})(CRM._, CRM.$);
