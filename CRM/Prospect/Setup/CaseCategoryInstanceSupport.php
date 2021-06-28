<?php

use CRM_Civicase_Service_CaseCategoryInstance as CaseCategoryInstance;
use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Sets up Case Category Instance Support.
 */
class CRM_Prospect_Setup_CaseCategoryInstanceSupport {

  const INSTANCE_OPTION_GROUP = 'case_category_instance_type';

  /**
   * Adds Case Category Instance Support.
   */
  public function apply() {
    $this->createDefaultInstanceOptionValue();
    $this->assignInstanceToProspect();
  }

  /**
   * Create Default Instance Type option value.
   */
  private function createDefaultInstanceOptionValue() {
    CRM_Core_BAO_OptionValue::ensureOptionValueExists(
      [
        'option_group_id' => self::INSTANCE_OPTION_GROUP,
        'name' => CaseTypeCategoryHelper::PROSPECT_INSTANCE_NAME,
        'label' => 'Sales/Opportunity Tracking',
        'grouping' => 'CRM_Prospect_Service_SalesOpportunityTrackingUtils',
        'is_active' => TRUE,
        'is_reserved' => TRUE,
      ]
    );
  }

  /**
   * Assign Instance to Prospect Case Type Category
   */
  private function assignInstanceToProspect () {
    $prospectInstanceId = civicrm_api3('OptionValue', 'get', [
      'sequential' => 1,
      'option_group_id' => 'case_category_instance_type',
      'name' => CaseTypeCategoryHelper::PROSPECT_INSTANCE_NAME,
    ])['values'][0]['value'];

    $prospectCaseTypeCategory = civicrm_api3('OptionValue', 'get', [
      'sequential' => 1,
      'option_group_id' => "case_type_categories",
      'name' => CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME,
    ])['values'][0];

    (new CaseCategoryInstance())->createInstanceTypeFor(
      $prospectCaseTypeCategory['value'],
      $prospectInstanceId
    );
  }

}
