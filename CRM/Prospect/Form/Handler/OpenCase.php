<?php

use CRM_Prospect_CustomFieldsFormBuilder as CustomFieldsFormBuilder;

/**
 * Implementation of custom handler for open case form
 * executed within prospect_civicrm_buildForm hook.
 */
class CRM_Prospect_Form_Handler_OpenCase {

  /**
   * Contains the form object provided
   * by buildForm hook.
   *
   * @var object
   */
  private $form;

  /**
   * Executes custom changes to new/update
   * case form.
   *
   * @param string $formName
   * @param object $form
   */
  public function handle($formName, $form) {
    if (!$this->canHandle($formName)) {
      return;
    }

    $this->form = $form;

    $this->addSubstatusCustomGroup();
  }

  /**
   * Checks if this page is 'Open Case'
   * form.
   *
   * @param string $formName
   *
   * @return bool
   */
  private function canHandle($formName) {
    return $formName == 'CRM_Case_Form_Case';
  }

  private function addSubstatusCustomGroup() {
    $fieldsToShow = [
      'Substatus',
    ];

    $customFieldsFormBuilder = new CustomFieldsFormBuilder();
    $this->form->assign('prospectSubstatusGroupTree', $customFieldsFormBuilder->getCustomGroupTreeFormatted('Prospect_Substatus'));

    $prospectSubstatusFieldsForm = $customFieldsFormBuilder->buildFieldsForm('Prospect_Substatus', $fieldsToShow);
    $this->form->assign('prospectSubstatusFieldsForm', $prospectSubstatusFieldsForm->toSmarty());

  }
}
