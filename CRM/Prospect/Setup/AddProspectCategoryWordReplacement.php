<?php

use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Class CRM_Prospect_Setup_AddProspectCategoryWordReplacement.
 */
class CRM_Prospect_Setup_AddProspectCategoryWordReplacement {

  /**
   * Creates the Prospecting word replacement option value.
   */
  public function apply() {
    $optionName = CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME . "_word_replacement";
    CRM_Core_BAO_OptionValue::ensureOptionValueExists([
      'option_group_id' => 'case_type_category_word_replacement_class',
      'name' => $optionName,
      'label' => $optionName,
      'value' => 'CRM_Prospect_WordReplacement_ProspectCategory',
      'is_default' => 1,
      'is_active' => TRUE,
      'is_reserved' => TRUE,
    ]);

  }

}
