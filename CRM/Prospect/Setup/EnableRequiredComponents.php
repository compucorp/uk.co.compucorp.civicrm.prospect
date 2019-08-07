<?php

/**
 * CRM_Prospect_Setup_EnableRequiredComponents class.
 */
class CRM_Prospect_Setup_EnableRequiredComponents {

  /**
   * Enables the CiviContribute and CiviPledge Components.
   */
  public function apply() {
    $getResult = civicrm_api3('setting', 'getsingle', [
      'return' => ['enable_components'],
    ]);

    $enabledComponents = $getResult['enable_components'];
    $componentsToEnable = ['CiviContribute', 'CiviPledge'];

    // Check if these components are already enabled.
    if (count(array_intersect($enabledComponents, $componentsToEnable)) == 2) {
      return;
    }

    $componentsToEnable = array_merge($enabledComponents, $componentsToEnable);

    civicrm_api3('setting', 'create', [
      'enable_components' => array_unique($componentsToEnable),
    ]);
  }

}
