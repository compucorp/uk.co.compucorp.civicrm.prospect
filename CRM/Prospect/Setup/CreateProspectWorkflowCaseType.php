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
      'activitySets' => $this->getActivitySets(),
    ];

    $result = civicrm_api3('CaseType', 'create', [
      'name' => 'default_prospect_workflow',
      'title' => 'Default Prospect Workflow',
      'description' => "The Default Prospect Workflow Case Type",
      'definition' => $definition,
      'is_active' => 0,
      'case_type_category' => 'Prospecting',
    ]);
    // Updating through manual query is unavoidable here
    // as case_type_category is not a core field and it does
    // not gets filled through api during installation.
    $this->updateCaseTypeCategoryToProspecting($result['id']);
  }

  /**
   * Updates the case type category to "Prospecting".
   *
   * @param int $caseTypeId
   *   Case Type Id.
   */
  private function updateCaseTypeCategoryToProspecting($caseTypeId) {
    $caseTypeTable = CRM_Case_BAO_CaseType::getTableName();
    $caseCategoryOptionValue = $this->getCaseCategoryOptionValueForProspecting();
    CRM_Core_DAO::executeQuery(
      "UPDATE {$caseTypeTable} SET case_type_category = %1 WHERE id = $caseTypeId",
      [1 => [$caseCategoryOptionValue, 'Integer']]
    );
  }

  /**
   * Returns the Case category option value.
   *
   * @return int|null
   *   Case category value.
   */
  private function getCaseCategoryOptionValueForProspecting() {
    $result = civicrm_api3('OptionValue', 'get', [
      'sequential' => 1,
      'option_group_id' => 'case_type_categories',
      'name' => CRM_Prospect_Helper_CaseTypeCategory::PROSPECT_CASE_TYPE_CATEGORY_NAME,
      'return' => ['value'],
    ]);

    if ($result['count'] == 0) {
      return;
    }

    return $result['values'][0]['value'];
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

  /**
   * Returns the Timeline.
   *
   * @return array
   *   The timeline.
   */
  private function getActivitySets() {
    return [
      [
        'timeline' => TRUE,
        'name' => 'standard_timeline',
        'label' => 'Standard Timeline',
      ],
    ];
  }

}
