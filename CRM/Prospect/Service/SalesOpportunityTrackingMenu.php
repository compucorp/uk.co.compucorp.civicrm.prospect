<?php

use CRM_Civicase_Service_CaseCategoryPermission as CaseCategoryPermission;
use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategory;

/**
 * SalesOpportunityTracking Menu class.
 */
class CRM_Prospect_Service_SalesOpportunityTrackingMenu extends CRM_Civicase_Service_CaseCategoryMenu {

  /**
   * {@inheritDoc}
   */
  public function getSubmenus(array $caseTypeCategory, array $permissions = NULL) {
    $singularLabelForMenu = ucfirst(strtolower($caseTypeCategory['singular_label']));
    $caseTypeCategoryName = $caseTypeCategory['name'];
    $labelForMenu = ucfirst(strtolower($caseTypeCategoryName));
    $categoryId = civicrm_api3('OptionValue', 'getsingle', [
      'option_group_id' => 'case_type_categories',
      'name' => $caseTypeCategoryName,
      'return' => ['value'],
    ])['value'];
    if (!$permissions) {
      $permissions = (new CaseCategoryPermission())->get($caseTypeCategoryName);
    }

    $caseTypeCategoryName = ($caseTypeCategoryName === CaseTypeCategory::PROSPECT_CASE_TYPE_CATEGORY_NAME) ?
      'prospect' :
       $caseTypeCategoryName;

    return [
      [
        'label' => ts('Dashboard'),
        'name' => "{$caseTypeCategoryName}_dashboard",
        'url' => 'civicrm/case/a/?case_type_category=' . $categoryId . '#/case?case_type_category=' . $categoryId,
        'permission' => "{$permissions['ACCESS_MY_CASE_CATEGORY_AND_ACTIVITIES']['name']},{$permissions['ACCESS_CASE_CATEGORY_AND_ACTIVITIES']['name']}",
        'permission_operator' => 'OR',
      ],
      [
        'label' => ts('New %1', ['1' => $singularLabelForMenu]),
        'name' => "new_{$caseTypeCategoryName}",
        'url' => 'civicrm/case/add?case_type_category=' . $categoryId . '&action=add&reset=1&context=standalone',
        'permission' => "{$permissions['ADD_CASE_CATEGORY']['name']},{$permissions['ACCESS_CASE_CATEGORY_AND_ACTIVITIES']['name']}",
        'permission_operator' => 'OR',
      ],
      [
        'label' => ts('Manage %1', ['1' => $labelForMenu]),
        'name' => "manage_{$caseTypeCategoryName}",
        'url' => 'civicrm/case/a/?case_type_category=' . $categoryId . '#/case/list?cf=%7B"case_type_category":"' . $categoryId . '"%7D',
        'permission' => "{$permissions['ACCESS_MY_CASE_CATEGORY_AND_ACTIVITIES']['name']},{$permissions['ACCESS_CASE_CATEGORY_AND_ACTIVITIES']['name']}",
        'permission_operator' => 'OR',
        'has_separator' => 1,
      ],
      [
        'label' => ts('Manage %1 Types', ['1' => $singularLabelForMenu]),
        'name' => "manage_{$caseTypeCategoryName}_workflows",
        'url' => 'civicrm/workflow/a?case_type_category=' . $categoryId . '&p=al#/list',
        'permission' => "{$permissions['ADMINISTER_CASE_CATEGORY']['name']}, administer CiviCRM",
        'permission_operator' => 'OR',
      ],
    ];
  }

}
