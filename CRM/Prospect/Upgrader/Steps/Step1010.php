<?php

use CRM_Prospect_Setup_AddSalesOpportunityTrackingWordReplacement as AddSalesOpportunityTrackingWordReplacement;

/**
 * Creates the Sales/Tracking Word Replacement Option Value.
 */
class CRM_Prospect_Upgrader_Steps_Step1010 {

  /**
   * Creates the Sales/Tracking Word Replacement Option Value.
   *
   * @return bool
   *   Return value in boolean.
   */
  public function apply() {
    $step = new AddSalesOpportunityTrackingWordReplacement();
    $step->apply();

    return TRUE;
  }

}
