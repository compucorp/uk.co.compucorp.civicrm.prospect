<?php

use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Create Prospecting Option Value.
 */
class CRM_Prospect_Setup_CreateProspectingOptionValue {

  /**
   * Creates the Prospecting option as a case type category option group.
   *
   * @return bool
   *   Returns TRUE.
   */
  public function apply() {
    CRM_Core_BAO_OptionValue::ensureOptionValueExists([
      'option_group_id' => 'case_type_categories',
      'name' => CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME,
      'label' => 'Prospects',
      'is_default' => 1,
      'is_active' => TRUE,
      'is_reserved' => TRUE,
    ]);

    return TRUE;
  }

}
