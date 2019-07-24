<?php

/**
 * Migrates the Prospect Custom fields to the prospecting case category.
 */
class CRM_Prospect_Upgrader_Steps_Step1003 {

  /**
   * Migrates the Prospect Custom fields to the prospecting case category.
   *
   * @return bool
   *   Return value in boolean.
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
        'extends' => 'prospecting',
      ]);
    }

    return TRUE;
  }

}
