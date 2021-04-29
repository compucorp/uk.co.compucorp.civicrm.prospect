<?php

use CRM_Prospect_Service_ProspectingMenu as ProspectingMenu;

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
    (new ProspectingMenu())->resetCaseCategorySubmenusUrl(
        CRM_Prospect_Helper_CaseTypeCategory::PROSPECT_CASE_TYPE_CATEGORY_NAME
    );

    CRM_Core_BAO_Navigation::resetNavigation();

    return TRUE;
  }

}
