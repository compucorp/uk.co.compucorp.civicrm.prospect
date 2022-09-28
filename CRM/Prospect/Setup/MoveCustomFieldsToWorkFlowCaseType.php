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
      $this->updateCustomGroup($value['id'], (array) $workflowCaseType['id']);
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

  /**
   * Updates a custom group.
   *
   * We are using the custom group object here rather than the API because if
   * this is updated via the API the `extends_entity_column_value` field will be
   * validated against the extends_entity_column_id column.
   *
   * @param int $id
   *   Custom group Id.
   * @param array|null $entityColumnValues
   *   Entity custom values for custom group.
   */
  protected function updateCustomGroup($id, $entityColumnValues) {
    $customGroup = new CRM_Core_BAO_CustomGroup();
    $customGroup->id = $id;
    $entityColValue = is_null($entityColumnValues) ? 'null' : CRM_Core_DAO::VALUE_SEPARATOR . implode(CRM_Core_DAO::VALUE_SEPARATOR, $entityColumnValues) . CRM_Core_DAO::VALUE_SEPARATOR;
    $customGroup->extends_entity_column_value = $entityColValue;
    $customGroup->save();
  }

}
