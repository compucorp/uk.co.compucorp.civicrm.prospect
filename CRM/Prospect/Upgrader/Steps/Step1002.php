<?php

use CRM_Prospect_Setup_CreateProspectMenus as CreateProspectMenus;

class CRM_Prospect_Upgrader_Steps_Step1002 {

  /**
   * Creates the Prospect menu items
   *
   * @return bool
   */
  public function apply() {
    $step = new CreateProspectMenus();
    $step->apply();

    return TRUE;
  }
}
