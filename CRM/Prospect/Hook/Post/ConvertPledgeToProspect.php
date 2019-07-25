<?php

use CRM_Prospect_Helper_ProspectConverted as ProspectConvertedHelper;

/**
 * CRM_Prospect_Hook_Post_ConvertPledgeToProspect class.
 */
class CRM_Prospect_Hook_Post_ConvertPledgeToProspect {

  /**
   * Hooks run function.
   *
   * Creates ProspectConverted record.
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
    if (!$this->shouldRun($objectName)) {
      return;
    }

    ProspectConvertedHelper::create(
      $objectId,
      CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_PLEDGE
    );
  }

  /**
   * Checks if the Object is of Pledge type.
   *
   * @param string $objectName
   *   Object name.
   */
  public function shouldRun($objectName) {
    return strtolower($objectName) == 'pledge';
  }

}
