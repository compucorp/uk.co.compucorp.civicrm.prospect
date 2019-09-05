<?php

use CRM_Prospect_Setup_AddProspectCategoryWordReplacement as AddProspectCategoryWordReplacement;

/**
 * Class CRM_Prospect_Upgrader_Steps_Step1006.
 */
class CRM_Prospect_Upgrader_Steps_Step1006 {

  /**
   * Creates the Prospecting word replacement option value.
   *
   * @return bool
   *   Return value in boolean.
   */
  public function apply() {
    $step = new AddProspectCategoryWordReplacement();
    $step->apply();

    return TRUE;
  }

}
