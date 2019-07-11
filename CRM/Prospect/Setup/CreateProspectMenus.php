<?php

class CRM_Prospect_Setup_CreateProspectMenus {

  /**
   * Creates the Prospect menu items
   *
   * @return bool
   */
  public function apply() {
    $this->createProspectMenuItems();

    return TRUE;
  }

  /**
   * Creates Prospect Main menu.
   */
  private function createProspectMenuItems() {
    $result = civicrm_api3('Navigation', 'get', ['name' => 'prospects']);

    if ($result['count'] > 0) {
      return;
    }

    $casesWeight = CRM_Core_DAO::getFieldValue(
      'CRM_Core_DAO_Navigation',
      'Cases',
      'weight',
      'name'
    );

    $params = [
      'label' => ts('Prospects'),
      'name' => 'prospects',
      'url' => NULL,
      'permission_operator' => 'OR',
      'is_active' => 1,
      'permission' => 'access my cases and activities,access all cases and activities',
      'icon' => 'crm-i fa-folder-open-o'
    ];

    $prospectMenu = civicrm_api3('Navigation', 'create', $params);
    //Menu weight seems to be ignored on create irrespective of whatever is passed, Civi
    //will assign the next available weight. This fixes the issue.
    civicrm_api3('Navigation', 'create', [
        'id' => $prospectMenu['id'],
        'weight' => $casesWeight + 1]
    );
    $this->createProspectSubmenus($prospectMenu['id']);
  }

  /**
   * Creates Prospect sub menu items.
   *
   * @param int $prospectMenuId
   */
  private function createProspectSubmenus($prospectMenuId) {
    $submenus = [
      [
        'label' => ts('Dashboard'),
        'name' => 'prospect_dashboard',
        'url' => 'civicrm/case/a/#/case?category=prospect',
        'permission' => 'access my cases and activities,access all cases and activities',
        'permission_operator' => 'OR',
      ],
      [
        'label' => ts('New Prospect'),
        'name' => 'new_prospect',
        'url' => 'civicrm/case/add?category=prospect',
        'permission' => 'add cases,access all cases and activities',
        'permission_operator' => 'OR',
      ],
      [
        'label' => ts('Manage Prospects'),
        'name' => 'manage_prospect',
        'url' => 'civicrm/case/a/#/case/list?category=prospect',
        'permission' => 'access my cases and activities,access all cases and activities',
        'permission_operator' => 'OR',
      ],
    ];

    foreach ($submenus as $i => $item) {
      $item['weight'] = $i;
      $item['parent_id'] = $prospectMenuId;
      $item['is_active'] = 1;
      civicrm_api3('Navigation', 'create', $item);
    }
  }
}