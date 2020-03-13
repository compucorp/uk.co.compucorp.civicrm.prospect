<?php

use CRM_Prospect_Helper_CaseTypeCategory as CaseTypeCategoryHelper;
use CRM_Civicase_Service_CaseCategoryCustomDataType as CaseCategoryCustomDataType;
use CRM_Civicase_Service_CaseCategoryCustomFieldExtends as CaseCategoryCustomFieldExtends;

/**
 * CRM_Prospect_Setup_ProcessProspectCategoryForCustomGroupSupport class.
 */
class CRM_Prospect_Setup_ProcessProspectCategoryForCustomGroupSupport {

  const PROSPECTS_CATEGORY_LABEL = 'Case (Prospects)';

  /**
   * Add Prospecting as a valid Entity that a custom group can extend.
   */
  public function apply() {
    $caseCategoryCustomData = new CaseCategoryCustomDataType();
    $caseCategoryCustomFieldExtends = new CaseCategoryCustomFieldExtends();
    $caseCategoryCustomFieldExtends->create(CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME, self::PROSPECTS_CATEGORY_LABEL);
    $caseCategoryCustomData->create(CaseTypeCategoryHelper::PROSPECT_CASE_TYPE_CATEGORY_NAME);
  }

}
