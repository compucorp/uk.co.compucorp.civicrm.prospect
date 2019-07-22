<?php

/**
 * CRM_Prospect_Hook_APIWrappers_CaseCreationAPIWrapper class.
 */
class CRM_Prospect_Hook_APIWrappers_CaseCreationAPIWrapper {

  /**
   * Hooks run function.
   */
  public function run(&$wrappers, $apiRequest) {
    if (!$this->shouldRun($apiRequest)) {
      return;
    }

    $wrappers[] = new CRM_Prospect_APIWrapper_prospectFinancialInformationCustomFields();
  }

  /**
   * Determines if the hook will run.
   */
  public function shouldRun($apiRequest) {
    if (!($apiRequest['entity'] === 'Case' && in_array($apiRequest['action'], ['create', 'edit']))) {
      return;
    }
    elseif (CRM_Prospect_Helper_ProspectHelper::isApiCallProspectCategory($apiRequest['params']['case_type_id'])) {
      return TRUE;
    }
  }

}
