<?php

use CRM_Prospect_CustomFieldsFormBuilder as CustomFieldsFormBuilder;

/**
 * Implementation of custom handler executed within prospect_civicrm_buildForm
 * hook.
 */
class CRM_Prospect_Form_Handler_CaseStatusUpdate {
  /**
   * Adds Specified 'Prospect_Financial_Information' and 'Prospect_Substatus'
   * custom groups fields to Case status update form.
   *
   * @param string $formName
   * @param object $form
   */
  public function handle($formName, $form) {
    if (!$this->canHandle($formName, $form)) {
      return;
    }

    // builds (Financial Information) Fields
    $fieldsToShow = [
      'Prospect_Amount',
      'Probability',
      'Expected_Date',
    ];
    $customFieldsFormBuilder = new CustomFieldsFormBuilder();
    $form->assign('prospectFinancialInformationGroupTree', $customFieldsFormBuilder->getCustomGroupTreeFormatted('Prospect_Financial_Information'));

    $prospectFinancialInformationFieldsForm = $customFieldsFormBuilder->buildFieldsForm('Prospect_Financial_Information', $fieldsToShow, CRM_Utils_Request::retrieve('caseid', 'Integer'));
    $form->assign('prospectFinancialInformationFieldsForm', $prospectFinancialInformationFieldsForm->toSmarty());

    // builds (Substatus) Fields
    $fieldsToShow = [
      'Substatus',
    ];
    $prospectSubstatusFieldsForm = $customFieldsFormBuilder->buildFieldsForm('Prospect_Substatus', $fieldsToShow, CRM_Utils_Request::retrieve('caseid', 'Integer'));
    $form->assign('prospectSubstatusFieldsForm', $prospectSubstatusFieldsForm->toSmarty());
    $form->assign('prospectSubstatusGroupTree', $customFieldsFormBuilder->getCustomGroupTreeFormatted('Prospect_Substatus'));
  }

  /**
   * Checks if we are on add Case Activity form.
   *
   * @param string $formName
   * @param object $form
   *
   * @return bool
   */
  private function canHandle($formName, $form) {
    return $formName === 'CRM_Case_Form_Activity' && $form->_action == CRM_Core_Action::ADD;
  }
}
