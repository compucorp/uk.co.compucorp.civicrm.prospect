<?php

use CRM_Prospect_Service_ProspectingMenu as ProspectingMenu;
use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Create Prospect Menu items.
 */
class CRM_Prospect_Setup_CreateProspectMenus {

  /**
   * Creates the Prospect menu items.
   */
  public function apply() {
    (new ProspectingMenu())->createItems(CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME);
  }

}
