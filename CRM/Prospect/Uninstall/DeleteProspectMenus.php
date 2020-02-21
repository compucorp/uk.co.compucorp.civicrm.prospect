<?php

/**
 * Class CRM_Prospect_Uninstall_DeleteProspectMenus.
 */
class CRM_Prospect_Uninstall_DeleteProspectMenus {

  /**
   * Deletes the Default Prospect menus added on installation.
   */
  public function apply() {
    $parentMenu = civicrm_api3('Navigation', 'get', ['name' => 'prospects']);

    if ($parentMenu['count'] == 0) {
      return;
    }

    $result = civicrm_api3('Navigation', 'get', ['parent_id' => $parentMenu['id']]);

    foreach ($result['values'] as $submenu) {
      civicrm_api3('Navigation', 'delete', ['id' => $submenu['id']]);
    }

    civicrm_api3('Navigation', 'delete', ['id' => $parentMenu['id']]);
  }

}
