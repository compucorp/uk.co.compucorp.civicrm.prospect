<?php

/**
 * Implementation of custom handler executed within prospect_civicrm_buildForm
 * hook.
 */
class CRM_Prospect_Form_Handler_PaymentEntityAdd {

  /**
   * Updates payment create form with Case Id, Prospect Amount, Campaign Id
   * and (Prospect Financial Information) values.
   *
   * @param string $formName
   * @param object $form
   */
  public function handle($formName, $form) {
    if (!$this->canHandle($formName, $form)) {
      return;
    }

    $caseId = $this->getProspectConvertedCaseID();

    if (!$caseId) {
      return;
    }

    $form->assign('caseID', $caseId);
    $form->assign('prospectFinancialInformationFields', new CRM_Prospect_prospectFinancialInformationFields($caseId));

    $form->addElement('hidden', 'caseId');

    $defaults = [
      'caseId' => $caseId,
    ];

    $fields = new CRM_Prospect_prospectFinancialInformationFields($caseId);
    $prospectConverted = CRM_Prospect_BAO_ProspectConverted::findByCaseID($caseId);
    if (empty($prospectConverted)) {
      $prospectAmount = $fields->getValueOf('Prospect_Amount');

      if ($formName === 'CRM_Contribute_Form_Contribution') {
        $defaults['total_amount'] = $prospectAmount;
      } else {
        $defaults['amount'] = $prospectAmount;
      }
    }

    $defaults['campaign_id'] = $fields->getValueOf('Campaign_Id');

    $form->setDefaults($defaults);
  }

  /**
   * Returns Case Id basing on request parameter or basing on Pledge Payment Id
   * if specified.
   *
   * @return int
   */
  private function getProspectConvertedCaseID() {
    $caseId = CRM_Utils_Request::retrieve('caseid', 'Integer');

    $pledgePaymentId = CRM_Utils_Request::retrieve('ppid', 'Integer');
    if (empty($pledgePaymentId)) {
      return $caseId;
    }

    // Get Case ID from Converted Prospect if it's converted.
    $prospectConvertedData = civicrm_api3('PledgePayment', 'get', [
      'sequential' => 1,
      'id' => $pledgePaymentId,
      'options' => ['limit' => 1],
      'api.ProspectConverted.get' => ['payment_entity_id' => '$value.pledge_id', 'payment_type_id' => CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_PLEDGE],
    ]);

    if (!empty($prospectConvertedData['values'][0]['api.ProspectConverted.get']['values'][0])) {
      $caseId = $prospectConvertedData['values'][0]['api.ProspectConverted.get']['values'][0]['prospect_case_id'];
    }

    return $caseId;
  }

  /**
   * Checks if we are on add Contribution/Pledge form.
   *
   * @param string $formName
   * @param object $form
   *
   * @return bool
   */
  private function canHandle($formName, $form) {
    return in_array($formName, ['CRM_Contribute_Form_Contribution', 'CRM_Pledge_Form_Pledge']) && $form->_action == CRM_Core_Action::ADD;
  }
}
