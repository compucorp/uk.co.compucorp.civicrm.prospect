<?php

use CRM_Civicase_Service_CaseCategoryPermission as CaseCategoryPermission;
use CRM_Prospect_Service_SalesOpportunityTrackingMenu as SalesOpportunityTrackingMenu;

use CRM_Certificate_ExtensionUtil as E;

/**
 * Updates the Sales/Tracking case type category menu labels.
 */
class CRM_Prospect_Upgrader_Steps_Step1011 {

  /**
   * Updates the Sales/Tracking case type category menu labels.
   *
   * @return bool
   *   Return value in boolean.
   */
  public function apply() {
    try {
      $this->updateCaseCategoryMenuLabel();
      $this->updateCaseCategorySubmenusLabel();
      CRM_Core_BAO_Navigation::resetNavigation();

      return TRUE;
    }
    catch (\Throwable $th) {
      \Civi::log()->error(E::ts("Error updating Sales opportunity menu"), [
        'context' => [
          'backtrace' => $th->getTraceAsString(),
          'message' => $th->getMessage(),
        ],
      ]);
    }

    return FALSE;
  }

  /**
   * Updates the Case Category Sub Menus label.
   */
  protected function updateCaseCategorySubmenusLabel() {
    $menuData = CRM_Prospect_Helper_CaseTypeCategory::getDataForMenu();
    $menu = new SalesOpportunityTrackingMenu();
    $caseCategoryPermission = new CaseCategoryPermission();
    $permissions = $caseCategoryPermission->get($menuData['name']);
    $submenus = $menu->getSubmenus($menuData, $permissions);

    foreach ($submenus as $item) {
      \Civi\Api4\Navigation::update(FALSE)
        ->addValue('label', $item['label'])
        ->addWhere('name', '=', $item['name'])
        ->execute();
    }
  }

  /**
   * Updates the Case Category Menu Label.
   */
  protected function updateCaseCategoryMenuLabel() {
    $menuData = CRM_Prospect_Helper_CaseTypeCategory::getDataForMenu();
    $labelForMenu = ucfirst(strtolower($menuData['label']));

    \Civi\Api4\Navigation::update(FALSE)
      ->addValue('label', $labelForMenu)
      ->addWhere('name', '=', $menuData['name'])
      ->execute();
  }

}
