<?php

use CRM_Civicase_Service_CaseCategoryMenu as CaseCategoryMenu;
use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Adds the Manage Workflow Menu item for Prospect.
 */
class CRM_Prospect_Setup_AddManageWorkflowMenu {

  /**
   * Create Manage Workflow menu for Prospecting.
   */
  public function apply() {
    $parentMenuForCaseCategory = civicrm_api3('Navigation', 'get', [
      'sequential' => 1,
      'label' => 'Prospects',
    ])['values'][0];

    if ($parentMenuForCaseCategory['id']) {
      CaseCategoryMenu::addSeparatorToTheLastMenuOf(
        $parentMenuForCaseCategory['id']
      );
      CaseCategoryMenu::createManageWorkflowMenuItemInto(
        $parentMenuForCaseCategory['id'],
        CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME,
        'Manage Workflows'
      );
    }
  }

}
