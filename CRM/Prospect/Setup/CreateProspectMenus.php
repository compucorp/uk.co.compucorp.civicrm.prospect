<?php

use CRM_Prospect_Service_SalesOpportunityTrackingMenu as SalesOpportunityTrackingMenu;
use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Create Prospect Menu items.
 */
class CRM_Prospect_Setup_CreateProspectMenus {

  /**
   * Creates the Prospect menu items.
   */
  public function apply() {
    (new SalesOpportunityTrackingMenu())->createItems(CaseTypeCategoryHelper::getDataForMenu());
  }

}
