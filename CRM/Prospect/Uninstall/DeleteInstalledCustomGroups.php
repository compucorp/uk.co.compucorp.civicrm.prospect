<?php

/**
 * Class CRM_Prospect_Uninstall_DeleteInstalledCustomGroups.
 */
class CRM_Prospect_Uninstall_DeleteInstalledCustomGroups {

  /**
   * Deletes the Default Installed Custom Groups.
   */
  public function apply() {
    $customGroups = ['Prospect_Financial_Information', 'Prospect_Substatus'];
    $result = civicrm_api3('CustomGroup', 'get', [
      'return' => ['id'],
      'name' => ['IN' => $customGroups],
    ]);

    if (empty($result['values'])) {
      return;
    }

    foreach ($result['values'] as $customGroup) {
      civicrm_api3('CustomGroup', 'delete', [
        'id' => $customGroup['id'],
      ]);
    }
  }

}
