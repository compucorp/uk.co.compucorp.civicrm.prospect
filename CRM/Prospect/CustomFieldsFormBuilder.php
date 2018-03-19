<?php

/**
 * This class build form elements for the
 * provided prospect custom groups and fields.
 */
class CRM_Prospect_CustomFieldsFormBuilder {

  /**
   * Returns a Form containing the Specified custom fields.
   *
   * @param string $customFieldName
   * @param array $fieldsToShow
   * @param int $caseId
   *
   * @return \CRM_Core_Form
   */
  public function buildFieldsForm($customFieldName, $fieldsToShow, $caseId = NULL) {
    $fieldsForm = new CRM_Core_Form();
    $prospectCustomFields = new CRM_Prospect_ProspectCustomGroups($customFieldName, $caseId);

    $defaults = [];
    foreach ($fieldsToShow as $field) {
      $machineName = $prospectCustomFields->getMachineNameOf($field);
      $fieldKey = $machineName . '_-1';
      $fieldId = substr($fieldKey, 7);
      $isRequired = $prospectCustomFields->isRequired($field);

      CRM_Core_BAO_CustomField::addQuickFormElement($fieldsForm, $fieldKey, $fieldId, $isRequired);
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
  public function getCustomGroupTreeFormatted($customGroupName) {
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
