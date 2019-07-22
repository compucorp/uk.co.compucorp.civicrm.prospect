<?php

/**
 * CRM_Prospect_Hook_Post_ConvertPledgeToProspect class.
 */
class CRM_Prospect_Hook_Post_ConvertPledgeToProspect {

  /**
   * Hooks run function.
   */
  public function run($op, $objectName, $objectId, &$objectRef) {
    if (!$this->shouldRun($op, $objectName, $objectId, $objectRef)) {
      return;
    }

    CRM_Prospect_Helper_ProspectHelper::createProspectConverted(
      $objectId,
      CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_PLEDGE
    );
  }

  /**
   * Determines if the hook will run.
   */
  public function shouldRun($op, $objectName, $objectId, $objectRef) {
    return strtolower($objectName) == 'pledge';
  }

}
