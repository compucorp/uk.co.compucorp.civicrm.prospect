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
        'grouping' => $caseStatus['class'],
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
    $openCaseStatusClass = 'Opened';
    $closedCaseStatusClass = 'Closed';

    $caseStatuses = [
      [
        'name' => 'enquiry',
        'label' => 'Enquiry (positive)',
        'class' => $openCaseStatusClass,
      ],
      [
        'name' => 'qualified',
        'label' => 'Qualified (positive)',
        'class' => $openCaseStatusClass,
      ],
      [
        'name' => 'in_progress',
        'label' => 'In progress (positive)',
        'class' => $openCaseStatusClass,
      ],
      [
        'name' => 'submitted',
        'label' => 'Submitted (positive)',
        'class' => $openCaseStatusClass,
      ],
      [
        'name' => 'won',
        'label' => 'Won (positive)',
        'class' => $closedCaseStatusClass,
      ],
      [
        'name' => 'lost',
        'label' => 'Lost (negative)',
        'class' => $closedCaseStatusClass,
      ],
    ];

    return $caseStatuses;
  }

}
