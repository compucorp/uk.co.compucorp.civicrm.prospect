<?php

class CRM_Prospect_APIWrapper_prospectFinancialInformationCustomFields implements API_Wrapper {

  /**
   * Implements fromApiInput() interface.
   *
   * @param array $apiRequest
   *
   * @return array
   */
  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Implements toApiOutput() interface.
   *
   * Used to update Case custom fields and Expectation value.
   *
   * @param array $apiRequest
   * @param array $result
   *
   * @return array
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
