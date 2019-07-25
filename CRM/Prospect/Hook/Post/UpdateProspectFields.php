<?php

/**
 * CRM_Prospect_Hook_Post_UpdateProspectFields class.
 */
class CRM_Prospect_Hook_Post_UpdateProspectFields {

  /**
   * Hooks run function.
   *
   * Updates Prospect Custom Fields.
   *
   * @param string $op
   *   Operation.
   * @param string $objectName
   *   Object name.
   * @param int $objectId
   *   Object ID.
   * @param object $objectRef
   *   Object Reference.
   */
  public function run($op, $objectName, $objectId, &$objectRef) {
    if (!$this->shouldRun($op, $objectName, $objectRef)) {
      return;
    }

    $this->updateProspectFields($op, $objectId, $objectRef);
  }

  /**
   * Determines if the hook will run.
   *
   * @param string $op
   *   Operation.
   * @param string $objectName
   *   Object name.
   * @param object $objectRef
   *   Object Reference.
   */
  public function shouldRun($op, $objectName, &$objectRef) {
    if (strtolower($objectName) == 'case' && in_array($op, ['create', 'edit'])
      && CRM_Prospect_Helper_CaseTypeCategory::isProspectCategory($objectRef->case_type_id)) {
      return TRUE;
    }
  }

  /**
   * Update the Prospect Custom Fields.
   *
   * @param string $op
   *   Operation.
   * @param int $objectId
   *   Object name.
   * @param object $objectRef
   *   Object Reference.
   */
  private function updateProspectFields($op, $objectId, &$objectRef) {
    try {
      // Update Financial Information fields data.
      $fields = new CRM_Prospect_prospectFinancialInformationFields($objectId);
      $fields->updateFieldsFromRequest([
        'Prospect_Amount',
        'Probability',
        'Expected_Date',
      ]);
      $fields->updateExpectation();

      // Update Substatus fields data.
      $fields = new CRM_Prospect_ProspectCustomGroups('Prospect_Substatus', $objectId);
      $fields->updateFieldsFromRequest([
        'Substatus',
      ]);
    }
    catch (CiviCRM_API3_Exception $e) {
      CRM_Core_Session::setStatus(
        ts('Cannot find Case entry. The Case didn\'t get created properly or there is other issue with retrieving the Case.'),
        ts('Error updating Expectation value'),
        'error'
      );

      return;
    }
  }

}
