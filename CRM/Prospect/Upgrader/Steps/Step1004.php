<?php

use CRM_Prospect_Setup_ProcessProspectCategoryForCustomGroupSupport as ProcessProspectCategoryForCustomGroupSupport;
use CRM_Prospect_Setup_MoveCustomFieldsToWorkFlowCaseType as MoveCustomFieldsToWorkFlowCaseType;

/**
 * Moves the prospect custom fields to the prospect category.
 */
class CRM_Prospect_Upgrader_Steps_Step1004 {

  /**
   * Moves the prospect custom fields to the workflow case type.
   *
   * Adds the prospecting category as an extendable entity.
   *
   * @return bool
   *   Boolean value.
   */
  public function apply() {
    $steps = [
      new ProcessProspectCategoryForCustomGroupSupport(),
      new MoveCustomFieldsToWorkFlowCaseType(),
    ];

    foreach ($steps as $step) {
      $step->apply();
    }

    return TRUE;
  }

}
