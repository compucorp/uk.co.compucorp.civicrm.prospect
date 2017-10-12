<?php

/**
 * Implementation of custom handler executed within prospect_civicrm_buildForm
 * hook.
 */
class CRM_Prospect_Form_Handler_PaymentEntityUpdate {

  /**
   * Payment Entity Id value
   *
   * @var int
   */
  private $paymentEntityId;

  /**
   * Payment Type Id value
   *
   * @var int
   */
  private $paymentTypeId;

  /**
   * Extends payment update form with Case Id and (Prospect Financial Information) values.
   *
   * @param string $formName
   * @param object $form
   */
  public function handle($formName, $form) {
    if (!$this->canHandle($formName, $form)) {
      return;
    }

    $this->setPaymentInformation($formName, $form);

    try {
      $prospectConverted = civicrm_api3('ProspectConverted', 'getsingle', [
        'payment_entity_id' => $this->paymentEntityId,
        'payment_type_id' => $this->paymentTypeId,
      ]);

      $form->assign('caseID', $prospectConverted['prospect_case_id']);
      $form->assign('prospectFinancialInformationFields', new CRM_Prospect_prospectFinancialInformationFields($prospectConverted['prospect_case_id']));
    } catch (CiviCRM_API3_Exception $e) {}
  }

  /**
   * Sets $paymentEntityId and $paymentTypeId local variables based on
   * request values and page context and action.
   *
   * @param string $formName
   * @param object $form
   */
  private function setPaymentInformation($formName, $form) {
    $paymentEntityId = CRM_Utils_Request::retrieve('id', 'Integer');
    $paymentTypeId = CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_CONTRIBUTION;

    if (in_array($formName, ['CRM_Pledge_Form_Pledge', 'CRM_Pledge_Form_PledgeView'])) {
      $paymentTypeId = CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_PLEDGE;
    }

    $pageContext = CRM_Utils_Request::retrieve('context', 'String');
    if ($pageContext === 'pledge' && $form->_action == CRM_Core_Action::VIEW) {
      // Get Case ID from PledgePayment entity.
      $pledgePaymentData = civicrm_api3('PledgePayment', 'get', [
        'sequential' => 1,
        'contribution_id' => $paymentEntityId,
        'options' => ['limit' => 1],
      ]);

      if (!empty($pledgePaymentData['count'])) {
        $paymentEntityId = $pledgePaymentData['values'][0]['pledge_id'];
        $paymentTypeId = CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_PLEDGE;
      }
    }

    $this->paymentEntityId = $paymentEntityId;
    $this->paymentTypeId = $paymentTypeId;
  }

  /**
   * Checks if we are on either edit Contribution/Pledge form
   * or view Contribution/Pledge page.
   *
   * @param string $formName
   * @param object $form
   *
   * @return bool
   */
  private function canHandle($formName, $form) {
    $contributionEditPage = ($formName == 'CRM_Contribute_Form_Contribution') &&  $form->_action == CRM_Core_Action::UPDATE;
    $pledgeEditPage = ($formName == 'CRM_Pledge_Form_Pledge') &&  $form->_action == CRM_Core_Action::UPDATE;
    $contributionViewPage = ($formName == 'CRM_Contribute_Form_ContributionView') &&  $form->_action == CRM_Core_Action::VIEW;
    $pledgeViewPage = ($formName == 'CRM_Pledge_Form_PledgeView') &&  $form->_action == CRM_Core_Action::VIEW;

    return $contributionEditPage  || $pledgeEditPage  ||  $contributionViewPage  || $pledgeViewPage;
  }
}
