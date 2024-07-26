<?php

/**
 * @file
 * Prospect Extention.
 */

require_once 'prospect.civix.php';

/**
 * Implements hook_civicrm_alterAPIPermissions().
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_alterAPIPermissions/
 */
function prospect_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  $permissions['prospect_converted']['get'] = ['access CiviCRM'];
  $permissions['prospect_converted']['delete'] = ['administer CiviProspecting'];
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function prospect_civicrm_config(&$config) {
  _prospect_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_alterMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterMenu
 */
function prospect_civicrm_alterMenu(&$items) {
  $items['civicrm/contact/view/pledge']['ids_arguments']['json'][] = 'civicase_reload';
  $items['civicrm/contact/view/contribution']['ids_arguments']['json'][] = 'civicase_reload';
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function prospect_civicrm_install() {
  _prospect_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function prospect_civicrm_enable() {
  _prospect_civix_civicrm_enable();
}

/**
 * Implements addCiviCaseDependentAngularModules().
 */
function prospect_addCiviCaseDependentAngularModules(&$dependentModules) {
  $dependentModules[] = "prospect";
}

/**
 * Implements hook_civicrm_entityTypes().
 */
function prospect_civicrm_entityTypes(&$entityTypes) {
  $entityTypes[] = [
    'name'  => 'ProspectConverted',
    'class' => 'CRM_Prospect_DAO_ProspectConverted',
    'table' => 'civicrm_prospect_converted',
  ];
}

/**
 * Implements hook_civicrm_custom().
 */
function prospect_civicrm_custom($op, $groupID, $entityID, &$params) {
  if ((int) $groupID === _prospect_civicrm_get_custom_group_id('Prospect_Financial_Information') && $op === 'edit') {
    $fields = new CRM_Prospect_prospectFinancialInformationFields($entityID);

    $fields->updateExpectation();
  }
}

/**
 * Returns 'Prospect_Financial_Information' Custom Group ID.
 *
 * @return int|null
 *   Custom Group ID.
 */
function _prospect_civicrm_get_custom_group_id($customGroupName) {
  $customGroupResponse = civicrm_api3('CustomGroup', 'get', [
    'return' => ['id'],
    'name' => $customGroupName,
    'options' => ['limit' => 1],
  ]);

  if (!empty($customGroupResponse['id'])) {
    return (int) $customGroupResponse['id'];
  }

  return NULL;
}

/**
 * Implements hook_civicrm_post().
 */
function prospect_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  $hooks = [
    'CRM_Prospect_Hook_Post_UpdateProspectFields',
    'CRM_Prospect_Hook_Post_ConvertContributionToProspect',
    'CRM_Prospect_Hook_Post_ConvertPledgeToProspect',
  ];

  foreach ($hooks as $hookClass) {
    if (class_exists($hookClass)) {
      $hook = new $hookClass();
      $hook->run($op, $objectName, $objectId, $objectRef);
    }
  }
}

/**
 * Implements hook_civicrm_apiWrappers().
 */
function prospect_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if ($apiRequest == 'Case') {
    $caseCreationApiWrapper = new CRM_Prospect_Hook_APIWrappers_CaseCreationAPIWrapper();
    $caseCreationApiWrapper->run($wrappers, $apiRequest);
  }
}

/**
 * Implements hook_civicrm_alterTemplateFile().
 */
function prospect_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName) {
  $functionName = '_prospect_civicrm_alterTemplateFile_' . $formName;

  if (!function_exists($functionName)) {
    return;
  }

  call_user_func_array($functionName, [$formName, &$form, $context, $tplName]);
}

/**
 * Implements hook_civicrm_alterTemplateFile().
 *
 * Callback for 'CRM_Pledge_Page_Payment' form name.
 */
function _prospect_civicrm_alterTemplateFile_CRM_Pledge_Page_Payment($formName, &$form, $context, &$tplName) {
  _prospect_civicrm_addMainCSSFile();

  $pledgeId = CRM_Utils_Request::retrieve('pledgeId', 'Integer');

  if (empty($pledgeId)) {
    return;
  }

  $prospectConverted = civicrm_api3('ProspectConverted', 'get', [
    'sequential' => 1,
    'payment_entity_id' => $pledgeId,
    'payment_type_id' => CRM_Prospect_BAO_ProspectConverted::PAYMENT_TYPE_PLEDGE,
    'options' => ['limit' => 1],
  ]);

  if (empty($prospectConverted['count'])) {
    return;
  }

  $form->assign('caseID', $prospectConverted['values'][0]['prospect_case_id']);
  $form->assign('prospectFinancialInformationFields', new CRM_Prospect_prospectFinancialInformationFields($prospectConverted['values'][0]['prospect_case_id']));
}

/**
 * Implements hook_civicrm_alterTemplateFile().
 *
 * Callback for 'CRM_Contribute_Page_Tab' form name.
 */
function _prospect_civicrm_alterTemplateFile_CRM_Contribute_Page_Tab($formName, &$form, $context, &$tplName) {
  _prospect_civicrm_addMainCSSFile();
}

/**
 * Implements hook_civicrm_alterTemplateFile().
 *
 * Callback for 'CRM_Pledge_Page_Tab' form name.
 */
function _prospect_civicrm_alterTemplateFile_CRM_Pledge_Page_Tab($formName, &$form, $context, &$tplName) {
  _prospect_civicrm_addMainCSSFile();
}

/**
 * Adds 'prospect.min.css' to resource files.
 */
function _prospect_civicrm_addMainCSSFile() {
  CRM_Core_Resources::singleton()->addStyleFile('uk.co.compucorp.civicrm.prospect', 'css/prospect.min.css');
}

/**
 * Implements hook_civicrm_alterContent().
 */
function prospect_civicrm_alterContent(&$content, $context, $tplName, &$object) {
  if (_prospect_isOpenCasePage($tplName)) {
    _prospect_RemoveSubstatusCustomGroup($content);
  }
}

/**
 * Check if the sent page is Open Case page.
 */
function _prospect_isOpenCasePage($tplName) {
  $pageType = CRM_Utils_Request::retrieve('type', 'String');
  return ($tplName == 'CRM/Custom/Form/CustomDataByType.tpl') && ($pageType == 'Case');
}

/**
 * Removes prospect substatus custom.
 *
 * Group from 'Open Case' form since
 * we will re-add it in a different location.
 */
function _prospect_RemoveSubstatusCustomGroup(&$content) {
  $extensionDirectory = CRM_Core_Resources::singleton()->getPath('uk.co.compucorp.civicrm.prospect');
  $content .= file_get_contents($extensionDirectory . '/js/Prospect.Page.CaseOpen.js');
}

/**
 * Implements hook_civicrm_buildForm().
 */
function prospect_civicrm_buildForm($formName, &$form) {
  $handlers = [
    new CRM_Prospect_Form_Handler_PaymentEntityAdd(),
    new CRM_Prospect_Form_Handler_PaymentEntityUpdate(),
    new CRM_Prospect_Form_Handler_CaseStatusUpdate(),
    new CRM_Prospect_Form_Handler_OpenCase(),
  ];

  foreach ($handlers as $handler) {
    $handler->handle($formName, $form);
  }
}
