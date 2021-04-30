<?php

use CRM_Prospect_Setup_CaseCategoryInstanceSupport as CaseCategoryInstanceSupport;
use CRM_Prospect_Setup_AddManageWorkflowMenu as AddManageWorkflowMenu;

/**
 * Creates the Prospect instance type.
 */
class CRM_Prospect_Upgrader_Steps_Step1009 {

  /**
   * Add the Case category Instance support.
   *
   * @return bool
   *   Return value in boolean.
   */
  public function apply() {
    $caseInstanceStep = new CaseCategoryInstanceSupport();
    $caseInstanceStep->apply();

    $workflowMenuStep = new AddManageWorkflowMenu();
    $workflowMenuStep->apply();

    return TRUE;
  }

}
