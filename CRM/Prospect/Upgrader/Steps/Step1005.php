<?php

use CRM_Prospect_Setup_EnableRequiredComponents as EnableRequiredComponents;

/**
 * CRM_Prospect_Upgrader_Steps_Step1005 class.
 */
class CRM_Prospect_Upgrader_Steps_Step1005 {

  /**
   * Enables the CiviContribute and CiviPledge Components.
   *
   * @return bool
   *   Return value in boolean.
   */
  public function apply() {
    $step = new EnableRequiredComponents();
    $step->apply();

    return TRUE;
  }

}
