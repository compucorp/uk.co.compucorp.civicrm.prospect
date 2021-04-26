<?php

use CRM_Prospect_Service_SalesOpportunityTrackingMenu as SalesOpportunityTrackingMenu;

/**
 * Update menus with new URL.
 */
class CRM_Prospect_Upgrader_Steps_Step1008 {

  /**
   * Runs the upgrader changes.
   *
   * @return bool
   *   Return value in boolean.
   */
  public function apply() {
    (new SalesOpportunityTrackingMenu())->resetCaseCategorySubmenusUrl(
        CRM_Prospect_Helper_CaseTypeCategory::getDataForMenu()
    );

    CRM_Core_BAO_Navigation::resetNavigation();

    return TRUE;
  }

}
