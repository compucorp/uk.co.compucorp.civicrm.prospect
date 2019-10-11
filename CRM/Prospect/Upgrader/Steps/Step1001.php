<?php

use CRM_Prospect_Setup_CreateProspectingOptionValue as CreateProspectingOptionValue;

/**
 * Creates Prospecting Option Value.
 */
class CRM_Prospect_Upgrader_Steps_Step1001 {

  /**
   * Creates the Prospecting option as a case type category option group.
   *
   * @return bool
   *   Returns True
   */
  public function apply() {
    $step = new CreateProspectingOptionValue();
    $step->apply();

    return TRUE;
  }

}
