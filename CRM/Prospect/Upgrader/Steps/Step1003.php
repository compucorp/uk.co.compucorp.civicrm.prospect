<?php

use CRM_Prospect_Setup_MoveCustomFieldsToProspecting as MoveCustomFieldsToProspecting;

/**
 * Moves the prospect custom fields to the prospect category.
 */
class CRM_Prospect_Upgrader_Steps_Step1003 {

  /**
   * Moves the prospect custom fields to the prospect category.
   *
   * @return bool
   *   Boolean value.
   */
  public function apply() {
    $step = new MoveCustomFieldsToProspecting();
    $step->apply();

    return TRUE;
  }

}
