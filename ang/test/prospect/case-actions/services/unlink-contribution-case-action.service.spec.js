/* eslint-env jasmine */

describe('UnlinkContributionCaseAction', () => {
  let ProspectConverted, UnlinkContributionCaseAction;

  beforeEach(module('crmUtil', 'prospect', 'prospect.data'));

  beforeEach(inject((_ProspectConverted_, _UnlinkContributionCaseAction_) => {
    ProspectConverted = _ProspectConverted_;
    UnlinkContributionCaseAction = _UnlinkContributionCaseAction_;

    CRM.checkPerm = jasmine.createSpy('checkPerm');
    CRM.checkPerm.and.returnValue(true);

    spyOn(ProspectConverted, 'checkIfSalesOpportunityTrackingWorkflow');
  }));

  describe('visibility', () => {
    describe('when the case is of sales opportunity type', () => {
      describe('when "administer CiviProspecting" permission is present', () => {
        describe('when prospect is converted to contribution', () => {
          var returnValue;

          beforeEach(() => {
            var cases = [{
              prospect: {
                isProspectConverted: true,
                paymentInfo: {
                  payment_entity: 'contribute'
                }
              }
            }];

            ProspectConverted.checkIfSalesOpportunityTrackingWorkflow.and.returnValue(true);
            returnValue = UnlinkContributionCaseAction.isActionAllowed({}, cases);
          });

          it('shows the action', () => {
            expect(returnValue).toBe(true);
          });
        });

        describe('when prospect is not converted to contribution', () => {
          var returnValue;

          beforeEach(() => {
            var cases = [{
              prospect: {
                isProspectConverted: true,
                paymentInfo: {
                  payment_entity: 'not contribute'
                }
              }
            }];

            ProspectConverted.checkIfSalesOpportunityTrackingWorkflow.and.returnValue(true);
            returnValue = UnlinkContributionCaseAction.isActionAllowed({}, cases);
          });

          it('hides the action', () => {
            expect(returnValue).toBeFalsy();
          });
        });
      });

      describe('when "administer CiviProspecting" permission is NOT present', () => {
        var returnValue;

        beforeEach(() => {
          var cases = [{
            prospect: {
              isProspectConverted: true,
              paymentInfo: {
                payment_entity: 'contribute'
              }
            }
          }];

          CRM.checkPerm.and.returnValue(false);

          returnValue = UnlinkContributionCaseAction.isActionAllowed({}, cases);
          ProspectConverted.checkIfSalesOpportunityTrackingWorkflow.and.returnValue(true);
        });

        it('hides the action', () => {
          expect(returnValue).toBe(false);
        });
      });
    });

    describe('when the case is NOT of sales opportunity type', () => {
      var returnValue;

      beforeEach(() => {
        var cases = [{
          prospect: {
            isProspectConverted: true,
            paymentInfo: {
              payment_entity: 'contribute'
            }
          }
        }];

        ProspectConverted.checkIfSalesOpportunityTrackingWorkflow.and.returnValue(false);
        returnValue = UnlinkContributionCaseAction.isActionAllowed({}, cases);
      });

      it('hides the action', () => {
        expect(returnValue).toBe(false);
      });
    });
  });

  describe('click handler', () => {
    let crmConfirmOnFn, crmConfirmCallbackFn, mockCallbackFn;

    beforeEach(() => {
      crmConfirmOnFn = jasmine.createSpy('crmConfirmOnFn').and.callFake(function (evtName, crmConfirmCallbackFntn) {
        crmConfirmCallbackFn = crmConfirmCallbackFntn;
      });

      spyOn(CRM, 'confirm');
      CRM.confirm.and.returnValue({ on: crmConfirmOnFn });
    });

    beforeEach(() => {
      var cases = [{
        prospect: {
          id: '1',
          isProspectConverted: true,
          paymentInfo: {
            payment_entity: 'contribute',
            payment_entity_id: '2'
          }
        }
      }];

      mockCallbackFn = jasmine.createSpy('mockCallbackFn');
      UnlinkContributionCaseAction.doAction(cases, { title: 'Unlink Contribution' }, mockCallbackFn);
    });

    it('confirms before unlinking', () => {
      expect(CRM.confirm).toHaveBeenCalledWith({
        title: 'Unlink Contribution',
        message: 'Are you sure?'
      });
    });

    describe('when unlinking is confirmed', () => {
      beforeEach(() => {
        crmConfirmCallbackFn();
      });

      it('unlinks the contribution', () => {
        expect(mockCallbackFn).toHaveBeenCalledWith([
          ['ProspectConverted', 'delete', { id: '1' }],
          ['Contribution', 'create', { id: '2', contribution_status_id: 'Cancelled' }]
        ]);
      });
    });
  });
});
