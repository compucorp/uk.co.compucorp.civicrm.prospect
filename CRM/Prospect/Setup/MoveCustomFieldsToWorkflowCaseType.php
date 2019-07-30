<?php

/**
 * Moves the default prospect custom fields to the workflow case type.
 */
class CRM_Prospect_Setup_MoveCustomFieldsToWorkflowCaseType {

  /**
   * Moves the default custom fields for prospect to the workflow case type.
   */
  public function apply() {
    $prospectCustomGroups = [
      'Prospect_Financial_Information',
      'Prospect_Substatus',
    ];

    $result = civicrm_api3('CustomGroup', 'get', [
      'return' => ['id'],
      'name' => ['IN' => $prospectCustomGroups],
    ]);

    if ($result['count'] == 0) {
      return;
    }

    $defaultWorkflowCaseTypeId = $this->getDefaultWorkflowCaseTypeId();
    if (!$defaultWorkflowCaseTypeId) {
      return;
    }

    foreach ($result['values'] as $value) {
      civicrm_api3('CustomGroup', 'create', [
        'id' => $value['id'],
        'extends_entity_column_value' => $defaultWorkflowCaseTypeId,
        'extends' => 'prospecting',
      ]);
    }
  }

  /**
   * Gets the Prospect workflow case type ID.
   *
   * @return int
   *   Workflow case type ID.
   */
  private function getDefaultWorkflowCaseTypeId() {
    $result = civicrm_api3('CaseType', 'get', [
      'return' => ['id'],
      'name' => 'default_prospect_workflow',
    ]);

    return $result['id'] ? $result['id'] : NULL;
  }

}
