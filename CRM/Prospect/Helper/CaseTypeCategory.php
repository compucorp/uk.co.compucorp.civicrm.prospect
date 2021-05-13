<?php

/**
 * CRM_Prospect_Helper_CaseTypeCategory class.
 */
class CRM_Prospect_Helper_CaseTypeCategory {

  /**
   * Prospect instance name.
   */
  const PROSPECT_INSTANCE_NAME = 'sales_opportunity_tracking';

  /**
   * Prospect category name.
   */
  const PROSPECT_CASE_TYPE_CATEGORY_NAME = 'Prospecting';

  /**
   * Prospect category label.
   */
  const PROSPECT_CASE_TYPE_CATEGORY_LABEL = 'Prospecting';

  /**
   * Prospect category singular label.
   */
  const PROSPECT_CASE_TYPE_CATEGORY_SINGULAR_LABEL = 'Prospecting';

  /**
   * Checks if Case Type ID/Name belongs to Prospect Category.
   *
   * @param int|string $case_type
   *   Case Type ID or Name.
   */
  public static function isProspectCategory($case_type) {
    $filterCaseTypesBy = ['name' => $case_type];

    if (is_numeric($case_type)) {
      $filterCaseTypesBy = ['id' => $case_type];
    }

    $results = self::getCaseTypeDetails($filterCaseTypesBy);

    if ($results['count'] > 0) {
      return TRUE;
    }
  }

  /**
   * Returns the details of sent Case Type.
   *
   * @param array $params
   *   Case Type ID or Name.
   *
   * @return array
   *   Details of Case Types
   */
  private static function getCaseTypeDetails(array $params) {
    $defaultParams = [
      'case_type_category' => self::PROSPECT_CASE_TYPE_CATEGORY_NAME,
    ];

    $params = array_merge($defaultParams, $params);
    $results = civicrm_api3('CaseType', 'get', $params);

    return $results;
  }

  /**
   * Returns the case types for the prospect category.
   *
   * @return array
   *   Array of Case Types indexed by Id.
   */
  public static function getProspectCaseTypes() {
    $result = civicrm_api3('CaseType', 'get', [
      'sequential' => 1,
      'return' => ['title', 'id'],
      'case_type_category' => self::PROSPECT_CASE_TYPE_CATEGORY_NAME,
    ]);

    return array_column($result['values'], 'title', 'id');
  }

  /**
   * Get data for creating the menu.
   *
   * @return string[]
   *   Category data.
   */
  public static function getDataForMenu() {
    return [
      'name' => self::PROSPECT_CASE_TYPE_CATEGORY_NAME,
      'label' => self::PROSPECT_CASE_TYPE_CATEGORY_LABEL,
      'singular_label' => self::PROSPECT_CASE_TYPE_CATEGORY_SINGULAR_LABEL,
    ];
  }

}
