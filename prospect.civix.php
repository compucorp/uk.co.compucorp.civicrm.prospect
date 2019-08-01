<?php

/**
 * @file
 * AUTO-GENERATED FILE -- Civix may overwrite any changes made to this file.
 */

/**
 * The class provides small stubs for accessing resources of this extension.
 */
class CRM_Prospect_ExtensionUtil {
  const SHORT_NAME = "prospect";
  const LONG_NAME = "uk.co.compucorp.civicrm.prospect";
  const CLASS_PREFIX = "CRM_Prospect";

  /**
   * Translate a string using the extension's domain.
   *
   * If the extension doesn't have a specific translation
   * for the string, fallback to the default translations.
   *
   * @param string $text
   *   Canonical message text (generally en_US).
   * @param array $params
   *   Parameters.
   *
   * @return string
   *   Translated text.
   *
   * @see ts
   */
  public static function ts($text, array $params = []) {
    if (!array_key_exists('domain', $params)) {
      $params['domain'] = [self::LONG_NAME, NULL];
    }
    return ts($text, $params);
  }

  /**
   * Get the URL of a resource file (in this extension).
   *
   * @param string|null $file
   *   Ex: NULL.
   *   Ex: 'css/foo.css'.
   *
   * @return string
   *   Ex: 'http://example.org/sites/default/ext/org.example.foo'.
   *   Ex: 'http://example.org/sites/default/ext/org.example.foo/css/foo.css'.
   */
  public static function url($file = NULL) {
    if ($file === NULL) {
      return rtrim(CRM_Core_Resources::singleton()->getUrl(self::LONG_NAME), '/');
    }
    return CRM_Core_Resources::singleton()->getUrl(self::LONG_NAME, $file);
  }

  /**
   * Get the path of a resource file (in this extension).
   *
   * @param string|null $file
   *   Ex: NULL.
   *   Ex: 'css/foo.css'.
   *
   * @return string
   *   Ex: '/var/www/example.org/sites/default/ext/org.example.foo'.
   *   Ex: '/var/www/example.org/sites/default/ext/org.example.foo/css/foo.css'.
   */
  public static function path($file = NULL) {
    // Return CRM_Core_Resources::singleton()->getPath(self::LONG_NAME, $file).
    return __DIR__ . ($file === NULL ? '' : (DIRECTORY_SEPARATOR . $file));
  }

  /**
   * Get the name of a class within this extension.
   *
   * @param string $suffix
   *   Ex: 'Page_HelloWorld' or 'Page\\HelloWorld'.
   *
   * @return string
   *   Ex: 'CRM_Foo_Page_HelloWorld'.
   */
  public static function findClass($suffix) {
    return self::CLASS_PREFIX . '_' . str_replace('\\', '_', $suffix);
  }

}

use CRM_Prospect_ExtensionUtil as E;

/**
 * Delegated - Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function _prospect_civix_civicrm_config(&$config = NULL) {
  static $configured = FALSE;
  if ($configured) {
    return;
  }
  $configured = TRUE;

  $template =& CRM_Core_Smarty::singleton();

  $extRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
  $extDir = $extRoot . 'templates';

  if (is_array($template->template_dir)) {
    array_unshift($template->template_dir, $extDir);
  }
  else {
    $template->template_dir = [$extDir, $template->template_dir];
  }

  $include_path = $extRoot . PATH_SEPARATOR . get_include_path();
  set_include_path($include_path);
}

/**
 * Delegated - Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *   Files.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function _prospect_civix_civicrm_xmlMenu(array &$files) {
  foreach (_prospect_civix_glob(__DIR__ . '/xml/Menu/*.xml') as $file) {
    $files[] = $file;
  }
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function _prospect_civix_civicrm_install() {
  _prospect_civix_civicrm_config();
  if ($upgrader = _prospect_civix_upgrader()) {
    $upgrader->onInstall();
  }
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function _prospect_civix_civicrm_postInstall() {
  _prospect_civix_civicrm_config();
  if ($upgrader = _prospect_civix_upgrader()) {
    if (is_callable([$upgrader, 'onPostInstall'])) {
      $upgrader->onPostInstall();
    }
  }
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function _prospect_civix_civicrm_uninstall() {
  _prospect_civix_civicrm_config();
  if ($upgrader = _prospect_civix_upgrader()) {
    $upgrader->onUninstall();
  }
}

/**
 * Delegated - Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function _prospect_civix_civicrm_enable() {
  _prospect_civix_civicrm_config();
  if ($upgrader = _prospect_civix_upgrader()) {
    if (is_callable([$upgrader, 'onEnable'])) {
      $upgrader->onEnable();
    }
  }
}

/**
 * Delegated - Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function _prospect_civix_civicrm_disable() {
  _prospect_civix_civicrm_config();
  if ($upgrader = _prospect_civix_upgrader()) {
    if (is_callable([$upgrader, 'onDisable'])) {
      $upgrader->onDisable();
    }
  }
}

/**
 * Delegated - Implements hook_civicrm_upgrade().
 *
 * @param string $op
 *   The type of operation being performed; 'check' or 'enqueue'.
 * @param CRM_Queue_Queue $queue
 *   For 'enqueue' - the modifiable list of pending up upgrade tasks.
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean)
 *   (TRUE if upgrades are pending)
 *   for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function _prospect_civix_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  if ($upgrader = _prospect_civix_upgrader()) {
    return $upgrader->onUpgrade($op, $queue);
  }
}

/**
 * Upgraders.
 *
 * @return CRM_Prospect_Upgrader
 *   Upgrader Instance.
 */
function _prospect_civix_upgrader() {
  if (!file_exists(__DIR__ . '/CRM/Prospect/Upgrader.php')) {
    return NULL;
  }
  else {
    return CRM_Prospect_Upgrader_Base::instance();
  }
}

/**
 * Search directory tree for files which match a glob pattern.
 *
 * Note: Dot-directories (like "..", ".git", or ".svn") will be ignored.
 * Note: In Civi 4.3+, delegate to CRM_Utils_File::findFiles()
 *
 * @param string $dir
 *   Base dir.
 * @param string $pattern
 *   Glob pattern, eg "*.txt".
 *
 * @return array
 *   Files.
 */
function _prospect_civix_find_files($dir, $pattern) {
  if (is_callable(['CRM_Utils_File', 'findFiles'])) {
    return CRM_Utils_File::findFiles($dir, $pattern);
  }

  $todos = [$dir];
  $result = [];
  while (!empty($todos)) {
    $subdir = array_shift($todos);
    foreach (_prospect_civix_glob("$subdir/$pattern") as $match) {
      if (!is_dir($match)) {
        $result[] = $match;
      }
    }
    if ($dh = opendir($subdir)) {
      while (FALSE !== ($entry = readdir($dh))) {
        $path = $subdir . DIRECTORY_SEPARATOR . $entry;
        if ($entry{0} == '.') {
        }
        elseif (is_dir($path)) {
          $todos[] = $path;
        }
      }
      closedir($dh);
    }
  }
  return $result;
}

/**
 * Delegated - Implements hook_civicrm_managed().
 *
 * Find any *.mgd.php files, merge their content, and return.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function _prospect_civix_civicrm_managed(&$entities) {
  $mgdFiles = _prospect_civix_find_files(__DIR__, '*.mgd.php');
  sort($mgdFiles);
  foreach ($mgdFiles as $file) {
    $es = include $file;
    foreach ($es as $e) {
      if (empty($e['module'])) {
        $e['module'] = E::LONG_NAME;
      }
      if (empty($e['params']['version'])) {
        $e['params']['version'] = '3';
      }
      $entities[] = $e;
    }
  }
}

/**
 * Delegated - Implements hook_civicrm_caseTypes().
 *
 * Find any and return any files matching "xml/case/*.xml"
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function _prospect_civix_civicrm_caseTypes(&$caseTypes) {
  if (!is_dir(__DIR__ . '/xml/case')) {
    return;
  }

  foreach (_prospect_civix_glob(__DIR__ . '/xml/case/*.xml') as $file) {
    $name = preg_replace('/\.xml$/', '', basename($file));
    if ($name != CRM_Case_XMLProcessor::mungeCaseType($name)) {
      $errorMessage = sprintf("Case-type file name is malformed (%s vs %s)", $name, CRM_Case_XMLProcessor::mungeCaseType($name));
      CRM_Core_Error::fatal($errorMessage);
      // Throw new CRM_Core_Exception($errorMessage);
    }
    $caseTypes[$name] = [
      'module' => E::LONG_NAME,
      'name' => $name,
      'file' => $file,
    ];
  }
}

/**
 * Delegated - Implements hook_civicrm_angularModules().
 *
 * Find any and return any files matching "ang/*.ang.php"
 *
 * Note: This hook only runs in CiviCRM 4.5+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function _prospect_civix_civicrm_angularModules(&$angularModules) {
  _prospect_includeAngularModules($angularModules);
  _prospect_addProspectAsRequirementForCivicase($angularModules);
}

/**
 * Add Angular Modules.
 *
 * Find and return files matching "ang/*.ang.php" and includes them as
 * angular modules.
 *
 * @param array $angularModules
 *   Angular Modules.
 */
function _prospect_includeAngularModules(array &$angularModules) {
  if (!is_dir(__DIR__ . '/ang')) {
    return;
  }

  $files = _prospect_civix_glob(__DIR__ . '/ang/*.ang.php');
  foreach ($files as $file) {
    $name = preg_replace(':\.ang\.php$:', '', basename($file));
    $module = include $file;
    if (empty($module['ext'])) {
      $module['ext'] = E::LONG_NAME;
    }
    $angularModules[$name] = $module;
  }
}

/**
 * Add Prospect as a requirement of civicase.
 *
 * @param array $angularModules
 *   Angular Modules.
 */
function _prospect_addProspectAsRequirementForCivicase(array &$angularModules) {
  if (isset($angularModules['civicase'])) {
    $angularModules['civicase']['requires'][] = 'prospect';
  }
  else {
    CRM_Core_Session::setStatus(
      'The <strong>Prospect</strong> extension requires <strong>CiviCase</strong> to be installed first.',
      'Warning',
      'no-popup'
    );
  }
}

/**
 * Glob wrapper which is guaranteed to return an array.
 *
 * The documentation for glob() says, "On some systems it is impossible to
 * distinguish between empty match and an error." Anecdotally, the return
 * result for an empty match is sometimes array() and sometimes FALSE.
 * This wrapper provides consistency.
 *
 * @link http://php.net/glob
 *
 * @param string $pattern
 *   Pattern.
 *
 * @return array
 *   possibly empty
 */
function _prospect_civix_glob($pattern) {
  $result = glob($pattern);
  return is_array($result) ? $result : [];
}

/**
 * Inserts a navigation menu item at a given place in the hierarchy.
 *
 * @param array $menu
 *   Menu hierarchy.
 * @param string $path
 *   Path to parent of this item, e.g. 'my_extension/submenu'
 *   'Mailing', or 'Administer/System Settings'.
 * @param array $item
 *   The item to insert (parent/child attributes will be filled for you).
 */
function _prospect_civix_insert_navigation_menu(array &$menu, $path, array $item) {
  // If we are done going down the path, insert menu.
  if (empty($path)) {
    $menu[] = [
      'attributes' => array_merge([
        'label'      => CRM_Utils_Array::value('name', $item),
        'active'     => 1,
      ], $item),
    ];
    return TRUE;
  }
  else {
    // Find an recurse into the next level down.
    $found = FALSE;
    $path = explode('/', $path);
    $first = array_shift($path);
    foreach ($menu as $key => &$entry) {
      if ($entry['attributes']['name'] == $first) {
        if (!isset($entry['child'])) {
          $entry['child'] = [];
        }
        $found = _prospect_civix_insert_navigation_menu($entry['child'], implode('/', $path), $item, $key);
      }
    }
    return $found;
  }
}

/**
 * Delegated - Implements hook_civicrm_navigationMenu().
 */
function _prospect_civix_navigationMenu(&$nodes) {
  if (!is_callable(['CRM_Core_BAO_Navigation', 'fixNavigationMenu'])) {
    _prospect_civix_fixNavigationMenu($nodes);
  }
}

/**
 * Fix Navigation Menu.
 *
 * Given a navigation menu, generate navIDs for any items which are
 * missing them.
 */
function _prospect_civix_fixNavigationMenu(&$nodes) {
  $maxNavID = 1;
  array_walk_recursive($nodes, function ($item, $key) use (&$maxNavID) {
    if ($key === 'navID') {
      $maxNavID = max($maxNavID, $item);
    }
  });
  _prospect_civix_fixNavigationMenuItems($nodes, $maxNavID, NULL);
}

/**
 * Fix Navigation Menu Items.
 */
function _prospect_civix_fixNavigationMenuItems(&$nodes, &$maxNavID, $parentID) {
  $origKeys = array_keys($nodes);
  foreach ($origKeys as $origKey) {
    if (!isset($nodes[$origKey]['attributes']['parentID']) && $parentID !== NULL) {
      $nodes[$origKey]['attributes']['parentID'] = $parentID;
    }
    // If no navID, then assign navID and fix key.
    if (!isset($nodes[$origKey]['attributes']['navID'])) {
      $newKey = ++$maxNavID;
      $nodes[$origKey]['attributes']['navID'] = $newKey;
      $nodes[$newKey] = $nodes[$origKey];
      unset($nodes[$origKey]);
      $origKey = $newKey;
    }
    if (isset($nodes[$origKey]['child']) && is_array($nodes[$origKey]['child'])) {
      _prospect_civix_fixNavigationMenuItems($nodes[$origKey]['child'], $maxNavID, $nodes[$origKey]['attributes']['navID']);
    }
  }
}

/**
 * Delegated - Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function _prospect_civix_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  static $configured = FALSE;
  if ($configured) {
    return;
  }
  $configured = TRUE;

  $settingsDir = __DIR__ . DIRECTORY_SEPARATOR . 'settings';
  if (is_dir($settingsDir) && !in_array($settingsDir, $metaDataFolders)) {
    $metaDataFolders[] = $settingsDir;
  }
}

/**
 * Delegated - Implements hook_civicrm_entityTypes().
 *
 * Find any *.entityType.php files, merge their content, and return.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function _prospect_civix_civicrm_entityTypes(&$entityTypes) {
  $entityTypes = array_merge($entityTypes, [
    'CRM_Prospect_DAO_ProspectConverted' =>
    [
      'name' => 'ProspectConverted',
      'class' => 'CRM_Prospect_DAO_ProspectConverted',
      'table' => 'civicrm_prospect_converted',
    ],
  ]);
}
