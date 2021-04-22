<?php

use CRM_Civicase_Service_ManageWorkflowMenu as ManageWorkflowMenu;
use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Adds the Manage Workflow Menu item for Prospect.
 */
class CRM_Prospect_Setup_AddManageWorkflowMenu {

  /**
   * Create Manage Workflow menu for Prospecting.
   */
  public function apply() {
    (new ManageWorkflowMenu())->create(
      CaseTypeCategoryHelper::PROSPECT_INSTANCE_NAME,
      FALSE,
      'Prospects'
    );
  }

}
