<?php

/**
 * @file
 * Declares an Angular module which can be autoloaded in CiviCRM.
 *
 * See also:
 * http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules.
 */

use CRM_Civicase_Helper_GlobRecursive as GlobRecursive;

expose_prospect_permissions();

/**
 * Get a list of JS files.
 */
function get_prospect_js_files() {
  return array_merge(
    ['ang/prospect.js'],
    GlobRecursive::getRelativeToExtension(
      'uk.co.compucorp.civicrm.prospect',
      'ang/prospect/*.js'
    )
  );
}

/**
 * Expose permissions to frontend.
 */
function expose_prospect_permissions() {
  Civi::resources()
    ->addPermissions([
      'administer CiviProspecting',
    ]);
}

return [
  'js' => get_prospect_js_files(),
  'basePages' => [],
];
