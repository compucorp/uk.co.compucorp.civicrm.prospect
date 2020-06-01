<?php

use CRM_Prospect_CustomFieldsFormBuilder as CustomFieldsFormBuilder;

/**
 * Implementation of custom handler for open case form.
 *
 * This is executed within prospect_civicrm_buildForm hook.
 */
class CRM_Prospect_Form_Handler_OpenCase {

  /**
   * Contains the form object provided by buildForm hook.
   *
   * @var object
   */
  private $form;

  /**
   * Executes custom changes to new/update case form.
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

    $this->form = $form;

    $this->addSubstatusCustomGroup();
  }

  /**
   * Checks if this page is 'Open Case' form.
   *
   * @param string $formName
   *   Form name.
   * @param CRM_Core_Form $form
   *   Form object.
   *
   * @return bool
   *   Returns boolean.
   */
  private function canHandle($formName, CRM_Core_Form $form) {
    return $formName == 'CRM_Case_Form_Case' && $this->isProspectCategory($form);
  }

  /**
   * Add the substatus custom group.
   */
  private function addSubstatusCustomGroup() {
    $fieldsToShow = [
      'Substatus',
    ];

    $customFieldsFormBuilder = new CustomFieldsFormBuilder();
    $this->form->assign('prospectSubstatusGroupTree', $customFieldsFormBuilder->getCustomGroupTreeFormatted('Prospect_Substatus'));

    $prospectSubstatusFieldsForm = $customFieldsFormBuilder->buildFieldsForm('Prospect_Substatus', $fieldsToShow);
    $this->form->assign('prospectSubstatusFieldsForm', $prospectSubstatusFieldsForm->toSmarty());
  }

  /**
   * Checks if the case is of prospect category.
   *
   * @param CRM_Core_Form $form
   *   Form name.
   *
   * @return string|null
   *   case category name.
   */
  private function isProspectCategory(CRM_Core_Form $form) {
    $urlParams = parse_url(htmlspecialchars_decode($form->controller->_entryURL), PHP_URL_QUERY);
    parse_str($urlParams, $urlParams);

    return !empty($urlParams['case_type_category']) && strtolower($urlParams['case_type_category']) == strtolower(CRM_Prospect_Helper_CaseTypeCategory::PROSPECT_CASE_TYPE_CATEGORY_NAME);
  }

}
