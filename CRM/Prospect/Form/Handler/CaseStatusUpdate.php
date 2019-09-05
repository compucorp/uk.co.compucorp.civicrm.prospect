<?php

use CRM_Prospect_CustomFieldsFormBuilder as CustomFieldsFormBuilder;
use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Implementation of custom handler executed within buildForm hook.
 */
class CRM_Prospect_Form_Handler_CaseStatusUpdate {

  /**
   * Handle function.
   *
   * Adds Specified 'Prospect_Financial_Information' and 'Prospect_Substatus'
   * custom groups fields to Case status update form.
   *
   * @param string $formName
   *   Form name.
   * @param object $form
   *   Form object.
   */
  public function handle($formName, $form) {
    if (!$this->canHandle($formName, $form)) {
      return;
    }

    // Builds (Financial Information) Fields.
    $fieldsToShow = [
      'Prospect_Amount',
      'Probability',
      'Expected_Date',
    ];
    $customFieldsFormBuilder = new CustomFieldsFormBuilder();
    $form->assign('prospectFinancialInformationGroupTree', $customFieldsFormBuilder->getCustomGroupTreeFormatted('Prospect_Financial_Information'));

    $prospectFinancialInformationFieldsForm = $customFieldsFormBuilder->buildFieldsForm('Prospect_Financial_Information', $fieldsToShow, CRM_Utils_Request::retrieve('caseid', 'Integer'));
    $form->assign('prospectFinancialInformationFieldsForm', $prospectFinancialInformationFieldsForm->toSmarty());

    // Builds (Substatus) Fields.
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
   *   Form name.
   * @param object $form
   *   Form object.
   *
   * @return bool
   *   Returns boolean.
   */
  private function canHandle($formName, $form) {
    $isCaseActivityForm = $formName === 'CRM_Case_Form_Activity';
    $isAddAction = $form->_action == CRM_Core_Action::ADD;
    if (!$isCaseActivityForm || !$isAddAction) {
      return FALSE;
    }

    return CaseTypeCategoryHelper::isProspectCategory($form->_caseType[0]);
  }

}
