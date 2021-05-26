/* eslint-env jasmine */

describe('ProspectConverted', () => {
  let crmApi, $q, $rootScope, PaymentInfoData, ProspectConverted,
    ProspectConvertedData, crmApiMock;

  beforeEach(module('crmUtil', 'prospect', 'prospect.data', function ($provide) {
    crmApiMock = jasmine.createSpy('crmApi');
    $provide.value('crmApi', crmApiMock);
  }));

  beforeEach(inject((_$q_, _ProspectConverted_, _crmApi_,
    _ProspectConvertedData_, _$rootScope_, _PaymentInfo_) => {
    $q = _$q_;
    $rootScope = _$rootScope_;
    ProspectConvertedData = _ProspectConvertedData_;
    PaymentInfoData = _PaymentInfo_;
    ProspectConverted = _ProspectConverted_;
    crmApi = _crmApi_;
  }));

  describe('getProspectConvertedValue()', () => {
    const caseID = '1';

    describe('when case is already converted to prospect', () => {
      let getProspectConvertedValuePromise;

      beforeEach(() => {
        crmApiMock.and.returnValue($q.resolve(ProspectConvertedData));
        getProspectConvertedValuePromise = ProspectConverted.getProspectConvertedValue(caseID);
      });

      it('calls ProspectConverted API', () => {
        expect(crmApi).toHaveBeenCalledWith('ProspectConverted', 'get', {
          sequential: 1,
          prospect_case_id: caseID
        });
      });

      it('resolved the promise with the found prospects', () => {
        getProspectConvertedValuePromise.then((returnValue) => {
          expect(returnValue).toBe(ProspectConvertedData.values[0]);
        });
        $rootScope.$digest();
      });
    });

    describe('when case is NOT already converted to prospect', () => {
      let getProspectConvertedValuePromise;

      beforeEach(() => {
        crmApiMock.and.returnValue($q.resolve({
          count: 0, values: []
        }));
        getProspectConvertedValuePromise = ProspectConverted.getProspectConvertedValue(caseID);
      });

      it('resolved the promise with a empty list', () => {
        getProspectConvertedValuePromise.then((returnValue) => {
          expect(returnValue).toBeUndefined();
        });
        $rootScope.$digest();
      });
    });
  });

  describe('getPaymentInfo()', () => {
    const caseID = '1';
    let getPaymentInfoPromise;

    beforeEach(() => {
      crmApi.and.returnValue($q.resolve(PaymentInfoData));
      getPaymentInfoPromise = ProspectConverted.getPaymentInfo(caseID);
    });

    it('calls ProspectConverted API', () => {
      expect(crmApi).toHaveBeenCalledWith('ProspectConverted', 'getpaymentinfo', {
        prospect_case_id: caseID
      });
    });

    it('returns payment info', () => {
      getPaymentInfoPromise.then((returnValue) => {
        expect(returnValue).toEqual(PaymentInfoData);
      });
      $rootScope.$digest();
    });
  });

  describe('checkIfSalesOpportunityTrackingWorkflow()', () => {
    let returnValue;

    describe('when the case type category is sales opportunity type', () => {
      beforeEach(() => {
        returnValue = ProspectConverted.checkIfSalesOpportunityTrackingWorkflow('4');
      });

      it('returns true', () => {
        expect(returnValue).toBe(true);
      });
    });

    describe('when the case type category is not sales opportunity type', () => {
      beforeEach(() => {
        returnValue = ProspectConverted.checkIfSalesOpportunityTrackingWorkflow('1');
      });

      it('returns false', () => {
        expect(returnValue).toBe(false);
      });
    });
  });
});
