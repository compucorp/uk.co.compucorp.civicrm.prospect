<?php

/**
 * Wrapper class for (Financial Information) custom group of particular Case.
 */
class CRM_Prospect_prospectFinancialInformationFields extends CRM_Prospect_ProspectCustomGroups {

  /**
   * Contrsuctor.
   *
   * @param int $caseId
   *   Case ID.
   */
  public function __construct($caseId) {
    parent::__construct('Prospect_Financial_Information', $caseId);
  }

  /**
   * Updates Expectation custom field data.
   */
  public function updateExpectation() {
    $machineName = $this->getMachineNameOf('Expectation');

    $this->updateFieldsFromParams([
      $machineName => $this->calculateExpectation(),
    ]);
  }

  /**
   * Calculates and returns Expectation value rounded to 2 decimal places.
   *
   * @return float
   *   Calculated Expectation
   */
  private function calculateExpectation() {
    $prospectAmount = $this->getValueOf('Prospect_Amount');
    $probability = $this->getValueOf('Probability');

    return number_format($prospectAmount * $probability / 100, 2, '.', '');
  }

}
