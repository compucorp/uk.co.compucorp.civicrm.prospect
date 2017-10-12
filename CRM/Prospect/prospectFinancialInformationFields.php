<?php

/**
 * Wrapper class for (Prospect financial Information) custom group of particular Case.
 */
class CRM_Prospect_prospectFinancialInformationFields extends CRM_Prospect_ProspectCustomGroups {

  /**
   * @param int $caseId
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
      $machineName => $this->calculateExpectation()
    ]);
  }

  /**
   * Calculates Expectation value and returns the value rounded to 2 decimal places.
   *
   * @return float
   */
  private function calculateExpectation() {
    $prospectAmount= $this->getValueOf('Prospect_Amount');
    $probability = $this->getValueOf('Probability');

    return number_format($prospectAmount * $probability / 100, 2, '.', '');
  }
}
