<?php

/**
 * CRM_Prospect_Hook_Post_UpdateProspectFields class.
 */
class CRM_Prospect_Hook_Post_UpdateProspectFields {

  /**
   * Hooks run function.
   */
  public function run($op, $objectName, $objectId, &$objectRef) {
    if (!$this->shouldRun($op, $objectName, $objectId, $objectRef)) {
      return;
    }

    $this->updateProspectFields($op, $objectId, $objectRef);
  }

  /**
   * Determines if the hook will run.
   */
  public function shouldRun($op, $objectName, $objectId, &$objectRef) {
    if (strtolower($objectName) == 'case' && in_array($op, ['create', 'edit'])
      && CRM_Prospect_Helper_ProspectHelper::isApiCallProspectCategory($objectRef->case_type_id)) {
      return TRUE;
    }
  }

  /**
   * Update the Prospect Custom Fields.
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
