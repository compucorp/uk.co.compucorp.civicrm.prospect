<?php

/**
 * @file
 * Declares an Angular module which can be autoloaded in CiviCRM.
 *
 * See also:
 * http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules.
 */

use CRM_Civicase_Helper_GlobRecursive as GlobRecursive;

/**
 * Get a list of JS files.
 */
function get_prospect_js_files() {
  return array_merge([
    'ang/prospect.js',
  ], GlobRecursive::get(dirname(__FILE__) . '/prospect/*.js'));
}

return [
  'js' => get_prospect_js_files(),
  'basePages' => [],
];
