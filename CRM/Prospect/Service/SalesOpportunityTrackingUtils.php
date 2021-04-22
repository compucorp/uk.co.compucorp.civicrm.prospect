<?php

use CRM_Prospect_Service_SalesOpportunityTrackingMenu as SalesOpportunityTrackingMenu;
use CRM_Civicase_Service_CaseManagementCustomGroupPostProcessor as CaseManagementCustomGroupPostProcessor;
use CRM_Civicase_Helper_CaseManagementCustomGroupPostProcess as CaseManagementCustomGroupPostProcessHelper;
use CRM_Civicase_Service_CaseManagementCaseTypePostProcessor as CaseManagementCaseTypePostProcessor;
use CRM_Civicase_Service_CaseManagementCustomGroupDisplayFormatter as CaseManagementCustomGroupDisplayFormatter;

/**
 * SalesOpportunityTrackingUtils class for case instance type.
 */
class CRM_Prospect_Service_SalesOpportunityTrackingUtils extends CRM_Civicase_Service_CaseCategoryInstanceUtils {

  /**
   * Returns the menu object for the default category instance.
   *
   * @return \CRM_Prospect_Service_SalesOpportunityTrackingMenu
   *   Menu object.
   */
  public function getMenuObject() {
    return new SalesOpportunityTrackingMenu();
  }

  /**
   * {@inheritDoc}
   */
  public function getCaseTypePostProcessor() {
    return new CaseManagementCaseTypePostProcessor(new CaseManagementCustomGroupPostProcessHelper());
  }

  /**
   * {@inheritDoc}
   */
  public function getCustomGroupDisplayFormatter() {
    return new CaseManagementCustomGroupDisplayFormatter(new CaseManagementCustomGroupPostProcessHelper());
  }

  /**
   * {@inheritDoc}
   */
  public function getCustomGroupPostProcessor() {
    return new CaseManagementCustomGroupPostProcessor(new CaseManagementCustomGroupPostProcessHelper());
  }

}
