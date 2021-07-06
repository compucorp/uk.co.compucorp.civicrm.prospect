<?php

use CRM_Prospect_ExtensionUtil as ExtensionUtil;

/**
 * Class ProspectCategory.
 */
class CRM_Prospect_WordReplacement_SalesOpportunityTracking implements CRM_Civicase_WordReplacement_BaseInterface {

  /**
   * {@inheritdoc}
   *
   * @return array
   *   Returns the word replacements
   */
  public function get() {
    $configFile = CRM_Core_Resources::singleton()
      ->getPath(ExtensionUtil::LONG_NAME, 'config/word_replacement/prospect_category.php');

    return include $configFile;
  }

}
