<?php

use CRM_Prospect_Setup_AddProspectCategoryCgExtendsValue as AddProspectCategoryCgExtendsValue;
use CRM_Prospect_Setup_MoveCustomFieldsToProspecting as MoveCustomFieldsToProspecting;

/**
 * Moves the prospect custom fields to the prospect category.
 */
class CRM_Prospect_Upgrader_Steps_Step1003 {

  /**
   * Moves the prospect custom fields to the prospect category.
   *
   * Adds the prospecting category as an extendable entity.
   *
   * @return bool
   *   Boolean value.
   */
  public function apply() {
    $steps = [
      new AddProspectCategoryCgExtendsValue(),
      new MoveCustomFieldsToProspecting(),
    ];

    foreach ($steps as $step) {
      $step->apply();
    }

    return TRUE;
  }

}
