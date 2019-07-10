<?php

class CRM_Prospect_Setup_CreateProspectingOptionValue {

  /**
   * Creates the Prospecting option as a case type category option group
   *
   * @return bool
   */
  public function apply() {
    CRM_Core_BAO_OptionValue::ensureOptionValueExists([
      'option_group_id' => 'case_type_categories',
      'name' => 'Prospecting',
      'label' => 'Prospecting',
      'is_default' => 1,
      'is_active' => TRUE,
      'is_reserved' => TRUE,
    ]);

    return TRUE;
  }
}
