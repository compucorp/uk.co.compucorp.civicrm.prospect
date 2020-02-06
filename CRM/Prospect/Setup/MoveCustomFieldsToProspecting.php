<?php

use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Moves prospect custom fields to prospect category type..
 */
class CRM_Prospect_Setup_MoveCustomFieldsToProspecting {

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

    foreach ($result['values'] as $value) {
      civicrm_api3('CustomGroup', 'create', [
        'id' => $value['id'],
        'extends' => CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME,
      ]);
    }
  }

}
