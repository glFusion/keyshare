<?php
/**
 * Apply updates to KeyShare during development.
 * Calls KEYSHARE_upgrade() with "ignore_errors" set so repeated SQL statements
 * won't cause functions to abort.
 *
 * Only updates from the previous released version.
 *
 * @author      Lee Garner <lee@leegarner.com>
 * @copyright   Copyright (c) 2022 Lee Garner <lee@leegarner.com>
 * @package     keyshare
 * @version     v0.0.1
 * @license     http://opensource.org/licenses/gpl-2.0.php
 *              GNU Public License v2 or later
 * @filesource
 */

require_once '../../../lib-common.php';

if (!SEC_inGroup('Root')) {
    // Someone is trying to illegally access this page
    COM_accessLog("Someone has tried to access the Birthdays Development Code Upgrade Routine without proper permissions.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: " . $_SERVER['REMOTE_ADDR']);
    COM_404();
}

use KeyShare\Config;
require_once Config::get('path') . '/upgrade.php';   // needed for set_version()
if (function_exists('CACHE_clear')) {
    CACHE_clear();
}

// Force the plugin version to the previous version and do the upgrade
$_PLUGIN_INFO['keyshare']['pi_version'] = '0.0.1';
KEYSHARE_do_upgrade(true);

// need to clear the template cache so do it here
if (function_exists('CACHE_clear')) {
    CACHE_clear();
}
header('Location: '.$_CONF['site_admin_url'].'/plugins.php?msg=600');
exit;
