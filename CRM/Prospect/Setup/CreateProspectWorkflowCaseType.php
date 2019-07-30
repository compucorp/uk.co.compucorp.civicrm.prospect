<?php

use CRM_Prospect_Setup_CreateProspectWorkflowCaseStatuses as ProspectWorkflowCaseStatusesSetup;

/**
 * Creates the Default Prospect Workflow case type.
 */
class CRM_Prospect_Setup_CreateProspectWorkflowCaseType {

  /**
   * Creates the Default Prospect Workflow case type.
   */
  public function apply() {
    $result = civicrm_api3('CaseType', 'get', [
      'name' => 'default_prospect_workflow',
    ]);

    if ($result['count'] > 0) {
      return;
    }

    $definition = [
      'statuses' => $this->getStatusDefinition(),
      'activityTypes' => $this->getActivityTypeDefinition(),
      'caseRoles' => $this->getCaseRolesDefinition(),
    ];

    civicrm_api3('CaseType', 'create', [
      'name' => 'default_prospect_workflow',
      'title' => 'Default Prospect Workflow',
      'description' => "The Default Prospect Workflow Case Type",
      'definition' => $definition,
      'is_active' => 0,
      'case_type_category' => 'Prospecting',
    ]);
  }

  /**
   * Returns the status definitions.
   *
   * @return array
   *   The status definitions
   */
  private function getStatusDefinition() {
    $workflowStatuses = ProspectWorkflowCaseStatusesSetup::getStatuses();
    $statusDefinition = [];

    foreach ($workflowStatuses as $status) {
      $statusDefinition[] = $status['name'];
    }

    return $statusDefinition;
  }

  /**
   * Returns the activity type definitions.
   *
   * @return array
   *   The activity type definitions
   */
  private function getActivityTypeDefinition() {
    $activityTypes = [];
    $result = civicrm_api3('OptionValue', 'get', [
      'sequential' => 1,
      'option_group_id' => 'activity_type',
      'name' => ['IN' => ['Meeting', 'Phone Call']],
    ]);

    if ($result['count'] == 2) {
      $activityTypes = [
        ['name' => 'Phone Call'],
        ['name' => 'Meeting'],
      ];
    }

    return $activityTypes;
  }

  /**
   * Returns the Case role definitions.
   *
   * @return array
   *   The case roles definitions
   */
  private function getCaseRolesDefinition() {
    $caseRoles = [
      ['name' => 'Prospect Owner', 'manager' => 1],
    ];

    return $caseRoles;
  }

}
