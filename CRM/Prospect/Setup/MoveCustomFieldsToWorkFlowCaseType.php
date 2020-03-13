<?php

/**
 * Moves prospect custom fields to the workflow case type..
 */
class CRM_Prospect_Setup_MoveCustomFieldsToWorkFlowCaseType {

  /**
   * Migrates the Prospect Custom fields to the prospecting case category.
   */
  public function apply() {
    $prospectCustomGroups = [
      'Prospect_Financial_Information',
      'Prospect_Substatus',
    ];

    $result = civicrm_api3('CustomGroup', 'get', [
      'return' => ['id'],
      'name' => ['IN' => $prospectCustomGroups],
      'extends' => 'Case',
    ]);

    if ($result['count'] == 0) {
      return TRUE;
    }

    $workflowCaseType = $this->getProspectWorkFlowCaseType();
    foreach ($result['values'] as $value) {
      civicrm_api3('CustomGroup', 'create', [
        'id' => $value['id'],
        'extends_entity_column_value' => CRM_Core_DAO::VALUE_SEPARATOR . $workflowCaseType['id'] . CRM_Core_DAO::VALUE_SEPARATOR,
      ]);
    }
  }

  /**
   * Fetches the prospect workflow case type.
   *
   * @return array
   *   Case type details.
   */
  private function getProspectWorkFlowCaseType() {
    $result = civicrm_api3('CaseType', 'getsingle', [
      'name' => 'default_prospect_workflow',
    ]);

    return $result;
  }

}
