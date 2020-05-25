<?php

use CRM_Prospect_Setup_CreateProspectOwnerRelationship as CreateProspectOwnerRelationship;
use CRM_Prospect_Setup_CreateProspectWorkflowCaseStatuses as CreateProspectWorkflowCaseStatuses;
use CRM_Prospect_Setup_CreateProspectWorkflowCaseType as CreateProspectWorkflowCaseType;

/**
 * Creates the Default Prospect Workflow case type.
 *
 * The Prospect owner relationship, the default prospect workflow statuses are
 * also created.
 *
 * @return bool
 *   Return value in boolean.
 */
class CRM_Prospect_Upgrader_Steps_Step1003 {

  /**
   * Applies the setup functions.
   *
   * @return bool
   *   Return value.
   */
  public function apply() {
    $steps = [
      new CreateProspectWorkflowCaseStatuses(),
      new CreateProspectOwnerRelationship(),
      new CreateProspectWorkflowCaseType(),
    ];

    foreach ($steps as $step) {
      $step->apply();
    }

    return TRUE;
  }

}
