<?php

/**
 * Class CRM_Prospect_Upgrader_Steps_Step1007.
 */
class CRM_Prospect_Upgrader_Steps_Step1007 {

  /**
   * Removes the 'Campaign_Id' Custom Field.
   *
   * @return bool
   *   Return value in boolean.
   */
  public function apply() {
    try {
      $result = civicrm_api3('CustomField', 'getsingle', [
        'return' => ['id'],
        'custom_group_id' => 'Prospect_Financial_Information',
        'name' => 'Campaign_Id',
      ]);

      if (!empty($result['id'])) {
        civicrm_api3('CustomField', 'delete', ['id' => $result['id']]);
      }
    }
    catch (Exception $e) {

    }

    return TRUE;
  }

}
