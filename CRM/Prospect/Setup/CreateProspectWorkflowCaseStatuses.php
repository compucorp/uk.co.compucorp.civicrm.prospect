<?php

/**
 * Create Prospect Workflow Case statuses.
 */
class CRM_Prospect_Setup_CreateProspectWorkflowCaseStatuses {

  /**
   * Creates the case statuses option values for prospect workflow case type.
   */
  public function apply() {
    $caseStatuses = self::getStatuses();

    foreach ($caseStatuses as $caseStatus) {
      CRM_Core_BAO_OptionValue::ensureOptionValueExists([
        'option_group_id' => 'case_status',
        'name' => $caseStatus['name'],
        'label' => $caseStatus['label'],
        'is_active' => TRUE,
        'is_reserved' => TRUE,
      ]);
    }
  }

  /**
   * Returns the prospect workflow case statuses.
   *
   * @return array
   *   Workflow case statuses.
   */
  public static function getStatuses() {
    $caseStatuses = [
      ['name' => 'enquiry', 'label' => 'Enquiry (positive)'],
      ['name' => 'qualified', 'label' => 'Qualified (positive)'],
      ['name' => 'in_progress', 'label' => 'In progress (positive)'],
      ['name' => 'submitted', 'label' => 'Submitted (positive)'],
      ['name' => 'won', 'label' => 'Won (positive)'],
      ['name' => 'lost', 'label' => 'Lost (negative)'],
    ];

    return $caseStatuses;
  }

}
