<?php

use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;
/**
 * CRM_Prospect_Setup_AddProspectCategoryCgExtendsValue class.
 */
class CRM_Prospect_Setup_AddProspectCategoryCgExtendsValue {

  const PROSPECTS_CATEGORY_LABEL = 'Case (Prospects)';

  /**
   * Add Prospecting as a valid Entity that a custom group can extend.
   */
  public function apply() {
    $result = civicrm_api3('OptionValue', 'get', [
      'option_group_id' => 'cg_extend_objects',
      'label' => self::PROSPECTS_CATEGORY_LABEL,
    ]);

    if ($result['count'] > 0) {
      $this->toggleOptionValueStatus(TRUE);

      return;
    }

    civicrm_api3('OptionValue', 'create', [
      'option_group_id' => 'cg_extend_objects',
      'name' => 'civicrm_case',
      'label' => self::PROSPECTS_CATEGORY_LABEL,
      'value' => CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME,
      'description' => 'CRM_Prospect_Helper_CaseTypeCategory::getProspectCaseTypes;',
      'is_active' => TRUE,
      'is_reserved' => TRUE,
    ]);
  }

  /**
   * Enables/Disables Prospects option values.
   *
   * The method also updates the Option Value 'description' to empty when the
   * extension is disabled. Because the 'description' is dynamically loaded,
   * this helps prevent the fatal error that is thrown when Civi tries
   * to load a class from a disabled extension.
   *
   * @param bool $newStatus
   *   True to enable, False to disable.
   */
  public function toggleOptionValueStatus($newStatus) {
    $optionValueDescription = (
      $newStatus ?
        'CRM_Prospect_Helper_CaseTypeCategory::getProspectCaseTypes;' :
          ''
    );

    civicrm_api3('OptionValue', 'get', [
      'option_group_id' => 'cg_extend_objects',
      'label' => self::PROSPECTS_CATEGORY_LABEL,
      'api.OptionValue.create' => [
        'id' => '$value.id',
        'description' => $optionValueDescription,
        'is_active' => $newStatus,
      ],
    ]);
  }

}
