<?php

use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;

/**
 * Class CRM_Prospect_Setup_AddSalesOpportunityTrackingWordReplacement.
 */
class CRM_Prospect_Setup_AddSalesOpportunityTrackingWordReplacement {

  /**
   * Word replacement option group name.
   */
  const WordReplacementOptionGroup = 'case_type_category_word_replacement_class';

  /**
   * Creates the Prospecting word replacement option value.
   */
  public function apply() {
    $this->ensureOptionGroupExists();
    $this->deleteOldOptionValue();
    $optionName = CaseTypeCategoryHelper::PROSPECT_INSTANCE_NAME . "_word_replacement";
    CRM_Core_BAO_OptionValue::ensureOptionValueExists([
      'option_group_id' => self::WordReplacementOptionGroup,
      'name' => $optionName,
      'label' => $optionName,
      'value' => 'CRM_Prospect_WordReplacement_SalesOpportunityTracking',
      'is_default' => 1,
      'is_active' => TRUE,
      'is_reserved' => TRUE,
    ]);
  }

  /**
   * This function ensures that the word replacement option group exists.
   *
   * On the development site, the prospect extension upgrader might run before
   * the one in civicase and throw an error about the missing option group. This
   * function takes care of that. Normally this will not occur for fresh install
   * because civicase will be installed first and this option group will exist.
   */
  private function ensureOptionGroupExists() {
    CRM_Core_BAO_OptionGroup::ensureOptionGroupExists([
      'name' => self::WordReplacementOptionGroup,
      'title' => ts('Case Type Category Word Replacements'),
      'is_reserved' => 1,
    ]);

  }

  /**
   * Deletes the old prospect category word replacement option value.
   */
  private function deleteOldOptionValue() {
    $oldOptionName = CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME . "_word_replacement";
    $optionValue = CRM_Core_BAO_OptionValue::ensureOptionValueExists(
      [
        'option_group_id' => self::WordReplacementOptionGroup,
        'name' => $oldOptionName,
        'label' => $oldOptionName,
        'value' => 'CRM_Prospect_WordReplacement_ProspectCategory',
      ]
    );

    if (empty($optionValue)) {
      return;
    }
    CRM_Core_BAO_OptionValue::del($optionValue['id']);
  }

}
