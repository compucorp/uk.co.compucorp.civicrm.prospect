<?php

/**
 * API Wrapper for Financial Information Custom Fields.
 */
class CRM_Prospect_APIWrapper_ProspectFinancialInformationCustomFields implements API_Wrapper {

  /**
   * Implements fromApiInput() interface.
   *
   * @return array
   *   API Request.
   */
  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Implements toApiOutput() interface.
   *
   * Used to update Case custom fields and Expectation value.
   *
   * @return array
   *   Result
   */
  public function toApiOutput($apiRequest, $result) {
    if (empty($apiRequest['params']['id'])) {
      return $result;
    }

    $fields = new CRM_Prospect_prospectFinancialInformationFields($apiRequest['params']['id']);

    $fields->updateFieldsFromParams($apiRequest['params']);
    $fields->updateExpectation();

    return $result;
  }

}
