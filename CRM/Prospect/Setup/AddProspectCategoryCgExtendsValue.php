<?php

/**
 * CRM_Prospect_Setup_AddProspectCategoryCgExtendsValue class.
 */
class CRM_Prospect_Setup_AddProspectCategoryCgExtendsValue {

  /**
   * Add Prospecting as a valid Entity that a custom group can extend.
   */
  public function apply() {
    $prospectCategoryLabel = 'Case (Prospects)';

    $result = civicrm_api3('OptionValue', 'get', [
      'option_group_id' => 'cg_extend_objects',
      'label' => $prospectCategoryLabel,
    ]);

    if ($result['count'] > 0) {
      return;
    }

    civicrm_api3('OptionValue', 'create', [
      'option_group_id' => 'cg_extend_objects',
      'name' => 'civicrm_case',
      'label' => $prospectCategoryLabel,
      'value' => 'prospecting',
      'description' => 'CRM_Prospect_Helper_CaseTypeCategory::getProspectCaseTypes;',
      'is_active' => TRUE,
      'is_reserved' => TRUE,
    ]);
  }

}
