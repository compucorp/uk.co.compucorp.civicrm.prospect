<?php

/**
 * CRM_Prospect_Hook_APIWrappers_CaseCreationAPIWrapper class.
 */
class CRM_Prospect_Hook_APIWrappers_CaseCreationAPIWrapper {

  /**
   * Hooks run function.
   *
   * Applies the API Wrapper to update Financial Information Custom Fields.
   *
   * @param array $wrappers
   *   Wrappers.
   * @param mixed $apiRequest
   *   Api Request.
   */
  public function run(array &$wrappers, $apiRequest) {
    if (is_object($apiRequest)) {
      return;
    }

    if (!$this->shouldRun($apiRequest)) {
      return;
    }

    $wrappers[] = new CRM_Prospect_APIWrapper_ProspectFinancialInformationCustomFields();
  }

  /**
   * Determines if the hook will run.
   *
   * @param array $apiRequest
   *   Api Request.
   *
   * @return bool
   *   If the hook should run.
   */
  public function shouldRun(array $apiRequest) {
    $entityIsCaseAndActionCreateOrEdit = $apiRequest['entity'] === 'Case'
      && in_array($apiRequest['action'], ['create', 'edit']);

    if ($entityIsCaseAndActionCreateOrEdit
      && CRM_Prospect_Helper_CaseTypeCategory::isProspectCategory($apiRequest['params']['case_type_id'])) {
      return TRUE;
    }

    return FALSE;
  }

}
