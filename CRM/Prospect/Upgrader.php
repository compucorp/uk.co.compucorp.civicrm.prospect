<?php

/**
 * Collection of upgrade steps.
 */
class CRM_Prospect_Upgrader extends CRM_Prospect_Upgrader_Base {

  /**
   * Option Group names created by the extension.
   *
   * @var array
   */
  private static $DEFAULT_OPTION_GROUPS = [
    'Prospect_Financial_Information_Campaign',
    'Prospect_Financial_Information_Fin_Yr',
    'Prospect_Financial_Information_Capital',
    'Prospect_Financial_Information_Restricted_Code',
    'Prospect_Financial_Information_Restricted',
    'Prospect_Substatus_Substatus'
  ];

  /**
   * Custom groups created by the extension
   *
   * @var array
   */
  private static $DEFAULT_CUSTOM_GROUPS = [
    'Prospect_Financial_Information',
    'Prospect_Substatus',
  ];

  /**
   * Action triggered on enable the extension.
   */
  public function enable() {
      $this->toggleDefaulValues(1);
  }

  /**
   * Action triggered on disable the extension.
   */
  public function disable() {
    $this->toggleDefaulValues(0);
  }

  /**
   * Enables / Disables default values (custom groups, custom fields,
   * option values, etc) created by this extension.
   *
   * @param int $status
   */
  private function toggleDefaulValues($status) {
    $this->toggleDefaultOptionGroups($status);
    $this->toggleDefaultOptionValues($status);
    // We don't want to enable / disable particular Custom Fields
    // because some of them can be enabled / disabled manually by the client.
    // So we enable / disable Custom Groups when enabling / disabling the extension
    // leaving 'is_active' property of Custom Fields unchanged.
    $this->toggleDefaultCustomGroups($status);
  }

  /**
   * Enables / Disables OptionGroups.
   *
   * @param int $newStatus
   */
  private function toggleDefaultOptionGroups($newStatus) {
    CRM_Core_DAO::executeQuery(
      'UPDATE civicrm_option_group SET is_active = %1 WHERE name IN ("' . implode('", "', self::$DEFAULT_OPTION_GROUPS) . '")',
      [ 1 => [ $newStatus, 'Integer' ] ]
    );
  }

  /**
   * Enables / Disables OptionValues.
   *
   * @param int $newStatus
   */
  private function toggleDefaultOptionValues($newStatus) {
    CRM_Core_DAO::executeQuery(
      'UPDATE civicrm_option_value JOIN civicrm_option_group ON civicrm_option_group.id = civicrm_option_value.option_group_id SET civicrm_option_value.is_active = %1 WHERE civicrm_option_group.name IN ("' . implode('", "', self::$DEFAULT_OPTION_GROUPS) . '")',
      [ 1 => [ $newStatus, 'Integer' ] ]
    );
  }

  /**
   * Enables / Disables CustomGroups:
   *
   * @param int $newStatus
   */
  private function toggleDefaultCustomGroups($newStatus) {
    CRM_Core_DAO::executeQuery(
      'UPDATE civicrm_custom_group SET is_active = %1 WHERE name IN ("' . implode('", "', self::$DEFAULT_CUSTOM_GROUPS) . '")',
      [ 1 => [ $newStatus, 'Integer' ] ]
    );
  }
}
