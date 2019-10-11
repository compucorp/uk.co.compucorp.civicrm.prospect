<?php

/**
 * @file
 * Prospect Converted API.
 */

/**
 * ProspectConverted.create API.
 *
 * @param array $params
 *   Parameters.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_prospect_converted_create(array $params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ProspectConverted.delete API.
 *
 * @param array $params
 *   Parameters.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_prospect_converted_delete(array $params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ProspectConverted.get API.
 *
 * @param array $params
 *   Parameters.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_prospect_converted_get(array $params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ProspectConverted.getpaymentinfo Spec.
 *
 * @param array $params
 *   Parameters.
 */
function _civicrm_api3_prospect_converted_getpaymentinfo_spec(array &$params) {
  $params['prospect_case_id'] = [
    'title' => 'Prospect Case ID',
    'description' => 'Prospect Case ID',
    'api.required' => TRUE,
    'type' => CRM_Utils_Type::T_INT,
  ];
}

/**
 * ProspectConverted.getpaymentinfo API.
 *
 * @param array $params
 *   Parameters.
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception
 */
function civicrm_api3_prospect_converted_getpaymentinfo(array $params) {
  $prospectConverted = CRM_Prospect_BAO_ProspectConverted::findByCaseID($params['prospect_case_id']);
  $returnValue = [];

  if ($prospectConverted) {
    $returnValue = $prospectConverted->getPaymentInfo();
  }

  return $returnValue;
}
