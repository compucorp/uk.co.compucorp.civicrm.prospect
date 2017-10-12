<?php

class CRM_Prospect_BAO_ProspectConverted extends CRM_Prospect_DAO_ProspectConverted {

  const ENTITY_NAME = 'ProspectConverted';
  const PAYMENT_TYPE_CONTRIBUTION = 1;
  const PAYMENT_TYPE_PLEDGE = 2;

  /**
   * Creates a new Converted Case
   *
   * @param array $params key-value pairs
   *
   * @return CRM_Prospect_DAO_ProspectConverted|NULL
   */
  public static function create($params) {
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, self::ENTITY_NAME, CRM_Utils_Array::value('id', $params), $params);

    $instance = new self();
    $instance->copyValues($params);
    $instance->save();

    CRM_Utils_Hook::post($hook, self::ENTITY_NAME, $instance->id, $instance);

    return $instance;
  }

  /**
   * Gets Converted Case by Case ID.
   *
   * @param int $caseId
   *
   * @return CRM_Prospect_BAO_ProspectConverted|NULL
   */
  public static function findByCaseID($caseId) {
    $prospectConverted = new self();
    $prospectConverted->prospect_case_id = $caseId;
    $prospectConverted->find(TRUE);

    return !empty($prospectConverted->id) ? $prospectConverted : NULL;
  }

  /**
   * Returns an array containing payment_completed and pledge_balance value
   * for Converted Case.
   *
   * @return array
   */
  public function getPaymentInfo() {
    $result = [
      'payment_completed' => NULL,
      'pledge_balance' => NULL,
      'payment_entity' => NULL,
      'payment_entity_id' => NULL,
      'payment_url' => NULL,
    ];

    switch ($this->payment_type_id) {
      case self::PAYMENT_TYPE_CONTRIBUTION:
        $contribution = $this->getContributionById($this->payment_entity_id);

        if (empty($contribution)) {
          break;
        }

        $result['payment_completed'] = $contribution['total_amount'];
        $result['payment_entity'] = 'contribute';
        $result['payment_url'] = '/civicrm/contact/view/contribution?reset=1&id=' . $this->payment_entity_id . '&action=view&context=contribution&selectedChild=contribute';
      break;
      case self::PAYMENT_TYPE_PLEDGE:
        $pledge = $this->getPledgeById($this->payment_entity_id);

        if (empty($pledge)) {
          break;
        }

        $result['pledge_balance'] = CRM_Utils_Money::format((float) $pledge['pledge_amount'] - (float) $pledge['pledge_total_paid']);

        if ((float) $pledge['pledge_total_paid'] > 0) {
          $result['payment_completed'] = CRM_Utils_Money::format((float) $pledge['pledge_total_paid']);
        }

        $result['payment_entity'] = 'pledge';
        $result['payment_url'] = '/civicrm/pledge/payment?action=browse&context=pledge&pledgeId=' . $this->payment_entity_id;
      break;
    }

    $result['payment_entity_id'] = $this->payment_entity_id;

    return $result;
  }

  /**
   * Gets Contribution by specified ID.
   *
   * @param int $id
   *
   * @return array|NULL
   */
  private function getContributionById($id) {
    $result = NULL;

    try {
      $result = civicrm_api3('Contribution', 'getsingle', [
        'id' => $id,
      ]);
    } catch (CiviCRM_API3_Exception $e) {}

    return $result;
  }

  /**
   * Gets Pledge by specified ID.
   *
   * @param int $id
   *
   * @return array|NULL
   */
  private function getPledgeById($id) {
    $result = NULL;

    try {
      $result = civicrm_api3('Pledge', 'getsingle', [
        'id' => $id,
      ]);
    } catch (CiviCRM_API3_Exception $e) {}

    return $result;
  }
}
