<?php

use CRM_Civicase_Hook_alterMailParams_SubjectCaseTypeCategoryProcessor as SubjectCaseTypeCategoryProcessor;
use CRM_Civicase_Test_Fabricator_Case as CaseFabricator;
use CRM_Civicase_Test_Fabricator_Contact as ContactFabricator;
use CRM_Prospect_WordReplacement_SalesOpportunityTracking as SalesOpportunityTrackingWordReplacement;

/**
 * Test class for the SubjectCaseTypeCategoryProcessor for the Sales Instance.
 *
 * @group headless
 */
class CRM_Prospect_Hook_alterMailParams_SubjectCaseTypeCategoryProcessorTest extends BaseHeadlessTest {

  /**
   * Test first instance of case is replaced.
   */
  public function testRunReplacesTheFirstInstanceOfCaseInMailSubjectCorrectly() {
    $emailSubjectProcessor = new SubjectCaseTypeCategoryProcessor();
    $_REQUEST['caseid'] = $this->getProspect()['id'];
    $params['subject'] = "[case ] This is a test email subject case";
    $emailSubjectProcessor->run($params, $context = '');
    $prospectWordReplacements = (new SalesOpportunityTrackingWordReplacement())->get();
    $expectedSubject = "[{$prospectWordReplacements['case']} ] This is a test email subject case";
    $this->assertEquals($expectedSubject, $params['subject']);
  }

  /**
   * Fabricates a case with given case category.
   *
   * @return array
   *   Prospect data.
   */
  private function getProspect() {
    $client = ContactFabricator::fabricate();

    return CaseFabricator::fabricate(
      [
        'case_type_id' => 'default_prospect_workflow',
        'contact_id' => $client['id'],
        'creator_id' => $client['id'],
      ]
    );
  }

}
