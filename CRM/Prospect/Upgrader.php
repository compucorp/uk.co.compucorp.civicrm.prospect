<?php

use CRM_Prospect_Setup_CreateProspectingOptionValue as CreateProspectingOptionValue;
use CRM_Prospect_Setup_CreateProspectMenus as CreateProspectMenus;

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
   * Tasks to perform when the module is installed.
   */
  public function install() {
    $steps = [
      new CreateProspectingOptionValue(),
      new CreateProspectMenus()
    ];

    foreach ($steps as $step) {
      $step->apply();
    }
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

  /**
   * @inheritdoc
   */
  public function hasPendingRevisions() {
    $revisions = $this->getRevisions();
    $currentRevisionNum = $this->getCurrentRevision();
    if (empty($revisions)) {
      return FALSE;
    }
    if (empty($currentRevisionNum)) {
      return TRUE;
    }

    return ($currentRevisionNum < max(array_keys($revisions)));
  }

  /**
   * @inheritdoc
   */
  public function enqueuePendingRevisions(CRM_Queue_Queue $queue) {
    $currentRevisionNum = (int) $this->getCurrentRevision();
    foreach ($this->getRevisions() as $revisionNum => $revisionClass) {

      if ($revisionNum <= $currentRevisionNum) {
        continue;
      }
      $tsParams = [1 => $this->extensionName, 2 => $revisionNum];
      $title = ts('Upgrade %1 to revision %2', $tsParams);
      $upgradeTask = new CRM_Queue_Task(
        [get_class($this), 'runStepUpgrade'],
        [(new $revisionClass())],
        $title
      );
      $queue->createItem($upgradeTask);
      $setRevisionTask = new CRM_Queue_Task(
        [get_class($this), '_queueAdapter'],
        ['setCurrentRevision', $revisionNum],
        $title
      );
      $queue->createItem($setRevisionTask);
    }
  }

  /**
   * This is a callback for running step upgraders from the queue
   *
   * @param CRM_Queue_TaskContext $context
   * @param \object $step
   *
   * @return true
   *   The queue requires that true is returned on successful upgrade, but we
   *   use exceptions to indicate an error instead.
   */
  public function runStepUpgrade($context, $step) {
    $step->apply();

    return TRUE;
  }

  /**
   * Get a list of revisions.
   *
   * @return array
   *   An array of revision classes sorted numerically by their key
   */
  public function getRevisions() {
    $extensionRoot = __DIR__;
    $stepClassFiles = glob($extensionRoot . '/Upgrader/Steps/Step*.php');
    $sortedKeyedClasses = [];
    foreach ($stepClassFiles as $file) {
      $class = $this->getUpgraderClassnameFromFile($file);
      $numberPrefix = 'Steps_Step';
      $startPos = strpos($class, $numberPrefix) + strlen($numberPrefix);
      $revisionNum = (int) substr($class, $startPos);
      $sortedKeyedClasses[$revisionNum] = $class;
    }
    ksort($sortedKeyedClasses, SORT_NUMERIC);

    return $sortedKeyedClasses;
  }

  /**
   * Gets the PEAR style classname from an upgrader file
   *
   * @param string $file
   *
   * @return string
   */
  private function getUpgraderClassnameFromFile($file) {
    $file = str_replace(realpath(__DIR__ . '/../../'), '', $file);
    $file = str_replace('.php', '', $file);
    $file = str_replace('/', '_', $file);

    return ltrim($file, '_');
  }
}
