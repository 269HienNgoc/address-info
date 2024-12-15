<?php
/*
Plugin Name: Add Address Plugin
Version: 1.0
Author: HenryDang
Author URI: hiendn.io.vn
Text Domain: options-plugin-address
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('HDMC_VERSION', '1.0');
define('HDMC_VERSION_CSS_JS', time());
define('HDMC__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HDMC__PLUGIN_URL', plugin_dir_url(__FILE__));


// Create Table Database.
require_once HDMC__PLUGIN_DIR . 'database/createDB.php';

// Add menu Address in Admin
require_once HDMC__PLUGIN_DIR . 'view/admin/admin-menu.php';

/**
 * We need hook activation and deactivation
 */
// register_activation_hook(__FILE__, '');