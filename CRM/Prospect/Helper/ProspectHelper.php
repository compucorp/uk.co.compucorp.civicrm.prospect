<?php

/**
 * CRM_Prospect_Helper_ProspectHelper class.
 */
class CRM_Prospect_Helper_ProspectHelper {

  const PROSPECT_CASE_TYPE_CATEGORY_NAME = 'Prospecting';

  /**
   * Checks if the API call belongs to Prospect Category.
   */
  public static function isApiCallProspectCategory($case_type_id) {
    $result = civicrm_api3('CaseType', 'get', [
      'sequential' => 1,
      'return' => ['name', 'id'],
      'case_type_category' => self::PROSPECT_CASE_TYPE_CATEGORY_NAME,
    ])['values'];

    $prospectCaseTypesByName = \CRM_Utils_Array::rekey($result, 'name');
    $prospectCaseTypesByID = \CRM_Utils_Array::rekey($result, 'id');

    if (isset($prospectCaseTypesByName[$case_type_id])
      || isset($prospectCaseTypesByID[$case_type_id])) {
      return TRUE;
    }
  }

  /**
   * Creates ProspectConverted entity.
   *
   * If Case Id is passed through New Contribution / Pledge form then it means
   * that the entity is asked to be converted by Prospect form.
   *
   * Creates ProspectConverted entity with specified payment entity,
   * payment type and Case Id.
   *
   * @param int $paymentEntityId
   *   Payment Entity ID.
   * @param int $paymentTypeId
   *   Payment Type ID.
   */
  public function createProspectConverted($paymentEntityId, $paymentTypeId) {
    $caseId = CRM_Utils_Request::retrieve('caseId', 'Integer');

    if (!$caseId) {
      return;
    }

    $prospectConverted = CRM_Prospect_BAO_ProspectConverted::findByCaseID($caseId);
    if (!empty($prospectConverted)) {
      return;
    }

    $fields = new CRM_Prospect_prospectFinancialInformationFields($caseId);

    CRM_Prospect_BAO_ProspectConverted::create([
      'prospect_case_id' => $caseId,
      'payment_entity_id' => $paymentEntityId,
      'payment_type_id' => $paymentTypeId,
    ]);

    // Sets (Prospect Amount) value to 0.
    $fields->setValueOf('Prospect_Amount', 0);

    // Sets (Expectation) value to 0.
    $fields->setValueOf('Expectation', 0);
  }

}
