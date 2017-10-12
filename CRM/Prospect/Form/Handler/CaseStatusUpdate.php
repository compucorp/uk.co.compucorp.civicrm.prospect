<?php

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
    $prospectFinancialInformationFieldsForm = $this->buildFieldsForm('Prospect_Financial_Information', CRM_Utils_Request::retrieve('caseid', 'Integer'), $fieldsToShow);
    $form->assign('prospectFinancialInformationFieldsForm', $prospectFinancialInformationFieldsForm->toSmarty());
    $form->assign('prospectFinancialInformationGroupTree', $this->getCustomGroupTreeFormatted('Prospect_Financial_Information'));

    // builds (Substatus) Fields
    $fieldsToShow = [
      'Substatus',
    ];
    $prospectSubstatusFieldsForm = $this->buildFieldsForm('Prospect_Substatus', CRM_Utils_Request::retrieve('caseid', 'Integer'), $fieldsToShow);
    $form->assign('prospectSubstatusFieldsForm', $prospectSubstatusFieldsForm->toSmarty());
    $form->assign('prospectSubstatusGroupTree', $this->getCustomGroupTreeFormatted('Prospect_Substatus'));
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

  /**
   * Returns a Form containing the Specified custom fields.
   *
   * @param string $customFieldName
   * @param int $caseId
   * @param array $fieldsToShow
   *
   * @return \CRM_Core_Form
   */
  private function buildFieldsForm($customFieldName, $caseId, $fieldsToShow) {
    $fieldsForm = new CRM_Core_Form();
    $prospectCustomFields = new CRM_Prospect_ProspectCustomGroups($customFieldName, $caseId);

    $defaults = [];
    foreach ($fieldsToShow as $field) {
      $machineName = $prospectCustomFields->getMachineNameOf($field);
      $fieldKey = $machineName . '_-1';
      $fieldId = substr($fieldKey, 7);

      CRM_Core_BAO_CustomField::addQuickFormElement($fieldsForm, $fieldKey, $fieldId);
      $defaults[$fieldKey] = $prospectCustomFields->getValueOf($field);
    }

    $fieldsForm->setDefaults($defaults);

    return $fieldsForm;
  }

  /**
   * Gets formatted groupTree of a Custom Group for a given group name.
   * Used to display form fields.
   *
   * @param int $customGroupName
   *   Custom group machine name
   *
   * @return array
   */
  private function getCustomGroupTreeFormatted($customGroupName) {
    $groupId = $this->getCustomGroupId($customGroupName);

    $formatGroupTree = CRM_Core_BAO_CustomGroup::formatGroupTree(CRM_Core_BAO_CustomGroup::getGroupDetail($groupId));
    return !empty($formatGroupTree[$groupId]) ? $formatGroupTree[$groupId] : [];
  }

  /**
   * Gets Custom Group ID for a given group name
   *
   * @param int $customGroupName
   *   Custom group machine name
   *
   * @return int|NULL
   */
  private function getCustomGroupId($customGroupName) {
    $customGroupId = NULL;

    try {
      $customGroupResult = civicrm_api3('CustomGroup', 'getsingle', [
        'return' => ['id'],
        'name' => $customGroupName,
      ]);

      $customGroupId = $customGroupResult['id'];
    } catch (CiviCRM_API3_Exception $e) {}

    return $customGroupId;
  }

}
