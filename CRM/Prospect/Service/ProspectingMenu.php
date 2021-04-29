<?php

use CRM_Civicase_Service_CaseCategoryPermission as CaseCategoryPermission;

/**
 * Prospecting Menu class.
 */
class CRM_Prospect_Service_ProspectingMenu extends CRM_Civicase_Service_CaseCategoryMenu {

  /**
   * {@inheritDoc}
   */
  public function getSubmenus($caseTypeCategoryName, array $permissions = NULL) {
    $categoryId = civicrm_api3('OptionValue', 'getsingle', [
      'option_group_id' => 'case_type_categories',
      'name' => $caseTypeCategoryName,
      'return' => ['value'],
    ])['value'];
    if (!$permissions) {
      $permissions = (new CaseCategoryPermission())->get($caseTypeCategoryName);
    }

    return [
      [
        'label' => ts('Dashboard'),
        'name' => 'prospect_dashboard',
        'url' => 'civicrm/case/a/?case_type_category=' . $categoryId . '#/case?case_type_category=' . $categoryId,
        'permission' => "{$permissions['ACCESS_MY_CASE_CATEGORY_AND_ACTIVITIES']['name']},{$permissions['ACCESS_CASE_CATEGORY_AND_ACTIVITIES']['name']}",
        'permission_operator' => 'OR',
      ],
      [
        'label' => ts('New Prospect'),
        'name' => 'new_prospect',
        'url' => 'civicrm/case/add?case_type_category=' . $categoryId . '&action=add&reset=1&context=standalone',
        'permission' => "{$permissions['ADD_CASE_CATEGORY']['name']},{$permissions['ACCESS_CASE_CATEGORY_AND_ACTIVITIES']['name']}",
        'permission_operator' => 'OR',
      ],
      [
        'label' => ts('Manage Prospects'),
        'name' => 'manage_prospect',
        'url' => 'civicrm/case/a/?case_type_category=' . $categoryId . '#/case/list?cf=%7B"case_type_category":"' . $categoryId . '"%7D',
        'permission' => "{$permissions['ACCESS_MY_CASE_CATEGORY_AND_ACTIVITIES']['name']},{$permissions['ACCESS_CASE_CATEGORY_AND_ACTIVITIES']['name']}",
        'permission_operator' => 'OR',
      ],
    ];
  }

}
