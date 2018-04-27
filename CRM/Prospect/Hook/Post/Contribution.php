<?php

class CRM_Prospect_Hook_Post_Contribution {

  /**
   * Operation being performed on the record.
   *
   * @var string
   */
  private $op;

  /**
   * ID of the contribution.
   *
   * @var int
   */
  private $contributionID;

  /**
   * DAO of the contribution.
   *
   * @var \CRM_Contribute_DAO_Contribution
   */
  private $contributionDAO;

  /**
   * Array mapping contribution status ID's to status names.
   *
   * @var
   */
  private static $contributionStatuses = [];

  /**
   * CRM_Prospect_Hook_Post_Contribution constructor.
   *
   * @param string $op
   * @param int $objectId
   * @param \CRM_Contribute_DAO_Contribution $objectRef
   */
  public function __construct($op, $objectId, &$objectRef) {
    $this->op = $op;
    $this->contributionID = $objectId;
    $this->contributionDAO = $objectRef;

    $this->loadContributionStatusMap();
  }

  /**
   * creates an array mapping contribution status ID's to status names.
   */
  private function loadContributionStatusMap() {
    if (empty(self::$contributionStatuses)) {
      $contributionStatuses = civicrm_api3('OptionValue', 'get', [
        'sequential' => 1,
        'return' => ['name', 'value'],
        'option_group_id' => 'contribution_status',
        'options' => ['limit' => 0],
      ])['values'];

      foreach ($contributionStatuses as $status) {
        $contributionStatusesNameMap[$status['value']] = $status['name'];
      }

      self::$contributionStatuses = $contributionStatusesNameMap;
    }
  }

  /**
   * Processes hook.
   */
  public function runHook() {
    switch ($this->op) {
      case 'create':
        $this->handleCreation();
        break;
      case 'edit':
        $this->handleUpdate();
        break;
      case 'delete':
        $this->handleDeletion();
        break;
    }
  }

  /**
   * Handles contribution updates.
   */
  private function handleUpdate() {
    $this->processContributionStatus();
  }

  /**
   * Processe contribution status to update prospects' information.
   */
  private function processContributionStatus() {
    $contributionStatusName = self::$contributionStatuses[$this->contributionDAO->contribution_status_id];

    switch ($contributionStatusName) {
      case 'Completed':
      case 'Chargeback':
        $this->setPaymentsOK();
        break;

      case 'In Progress':
      case 'Overdue':
      case 'Partially paid':
      case 'Pending':
      case 'Pending refund':
        $this->setProspectAmountsFromContribution();
        break;

      case 'Cancelled':
      case 'Failed':
      case 'Refunded':
        $this->deletePayments();
        break;
    }
  }

  /**
   * Uses contribution data to recalculate Prospect_Amount and Expectation.
   */
  private function setProspectAmountsFromContribution() {
    $prospectConversion = new CRM_Prospect_BAO_ProspectConverted();
    $prospectConversion->payment_entity_id = $this->contributionID;
    $prospectConversion->payment_type_id = CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_CONTRIBUTION;
    $prospectConversion->find();

    while ($prospectConversion->fetch()) {
      $fields = new CRM_Prospect_prospectFinancialInformationFields($prospectConversion->prospect_case_id);
      $fields->setValueOf('Prospect_Amount', $this->contributionDAO->total_amount);
      $fields->updateExpectation();
    }
  }

  /**
   * If Case Id is passed through New Contribution / Pledge form then it means
   * that the entity is asked to be converted by Prospect form.
   *
   * Creates ProspectConverted entity with specified payment entity, payment type
   * and Case Id.
   */
  private function handleCreation() {
    $caseId = CRM_Utils_Request::retrieve('caseId', 'Integer');
    $paymentEntityId = $this->contributionID;
    $paymentTypeId = CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_CONTRIBUTION;

    if (!$caseId) {
      return;
    }

    $prospectConverted = CRM_Prospect_BAO_ProspectConverted::findByCaseID($caseId);
    if (!empty($prospectConverted)) {
      return;
    }

    CRM_Prospect_BAO_ProspectConverted::create([
      'prospect_case_id' => $caseId,
      'payment_entity_id' => $paymentEntityId,
      'payment_type_id' => $paymentTypeId,
    ]);

    $this->processContributionStatus();
  }

  /**
   * Updates prospect amounts to reflect the payment done.
   */
  private function setPaymentsOK() {
    $prospectConversion = new CRM_Prospect_BAO_ProspectConverted();
    $prospectConversion->payment_entity_id = $this->contributionID;
    $prospectConversion->payment_type_id = CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_CONTRIBUTION;
    $prospectConversion->find();

    while ($prospectConversion->fetch()) {
      $caseId = $prospectConversion->prospect_case_id;
      $fields = new CRM_Prospect_prospectFinancialInformationFields($caseId);

      // Sets (Prospect Amount) value to 0.
      $fields->setValueOf('Prospect_Amount', 0);

      // Sets (Expectation) value to 0.
      $fields->setValueOf('Expectation', 0);
    }
  }

  /**
   * Deletes payments associated to the current contribution.
   */
  private function deletePayments() {
    $prospectConversion = new CRM_Prospect_BAO_ProspectConverted();
    $prospectConversion->payment_entity_id = $this->contributionID;
    $prospectConversion->payment_type_id = CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_CONTRIBUTION;
    $prospectConversion->find();

    while ($prospectConversion->fetch()) {
      $fields = new CRM_Prospect_prospectFinancialInformationFields($prospectConversion->prospect_case_id);
      $fields->setValueOf('Prospect_Amount', $this->contributionDAO->total_amount);
      $fields->updateExpectation();

      $prospectConversion->delete();
    }
  }

  /**
   * If contribution is deleted and it is associated to a converted prospect, it
   * should also be deleted and prospect amount and expectation should be reset.
   */
  private function handleDeletion() {
    $this->deletePayments();
  }

}
