/* eslint-env jasmine */

(() => {
  describe('ProspectConverted', () => {
    let crmApi;
    let $q;
    let $rootScope;
    let ProspectConverted;
    let ProspectConvertedData;

    beforeEach(module('crmUtil', 'prospect', 'prospect.data'));

    beforeEach(inject((_$q_, _ProspectConverted_, _crmApi_,
      _ProspectConvertedData_, _$rootScope_) => {
      $q = _$q_;
      $rootScope = _$rootScope_;
      ProspectConvertedData = _ProspectConvertedData_;
      ProspectConverted = _ProspectConverted_;
      crmApi = _crmApi_;
    }));

    describe('getProspectIsConverted()', () => {
      const caseID = '1';

      describe('when case is already Converted to Prospect', () => {
        let getProspectIsConvertedPromise;

        beforeEach(() => {
          crmApi.and.returnValue($q.resolve(ProspectConvertedData));
          getProspectIsConvertedPromise = ProspectConverted.getProspectIsConverted(caseID);
        });

        it('calls ProspectConverted API', () => {
          expect(crmApi).toHaveBeenCalledWith('ProspectConverted', 'get', {
            sequential: 1,
            prospect_case_id: caseID,
          });
        });

        it('promise resolved TRUE value', () => {
          getProspectIsConvertedPromise.then((returnValue) => {
            expect(returnValue).toBe(true);
          });
          $rootScope.$digest();
        });
      });

      describe('when case is NOT already Converted to Prospect', () => {
        let getProspectIsConvertedPromise;

        beforeEach(() => {
          crmApi.and.returnValue($q.resolve({
            count: 0, values: [],
          }));
          getProspectIsConvertedPromise = ProspectConverted.getProspectIsConverted(caseID);
        });

        it('promise resolved FALSE value', () => {
          getProspectIsConvertedPromise.then((returnValue) => {
            expect(returnValue).toBe(false);
          });
          $rootScope.$digest();
        });
      });
    });

    describe('getPaymentInfo()', () => {
      const caseID = '1';

      beforeEach(() => {
        ProspectConverted.getPaymentInfo(caseID);
      });

      it('calls ProspectConverted API', () => {
        expect(crmApi).toHaveBeenCalledWith('ProspectConverted', 'getpaymentinfo', {
          prospect_case_id: caseID,
        });
      });
    });

    describe('checkIfProspectingCaseTypeCategory()', () => {
      let returnValue;

      describe('when the case type category is prospect', () => {
        beforeEach(() => {
          returnValue = ProspectConverted.checkIfProspectingCaseTypeCategory({
            'case_type_id.case_type_category': 2,
          });
        });

        it('returns true', () => {
          expect(returnValue).toBe(true);
        });
      });

      describe('when the case type category is not prospect', () => {
        beforeEach(() => {
          returnValue = ProspectConverted.checkIfProspectingCaseTypeCategory({
            'case_type_id.case_type_category': 1,
          });
        });

        it('returns false', () => {
          expect(returnValue).toBe(false);
        });
      });
    });
  });
})();
