<?php
/**
 * Plugin Name: WP Option List Table
 * Plugin URI: https://www.wpwebelite.com/
 * Description:  This plugin handles Add,Edit and Delete functinalities with WordPress Table and with using WP Options. 
 * Version: 1.0.0
 * Author: WPWeb
 * Author URI: https://www.wpwebelite.com/
 * Text Domain: wwolt
 * Domain Path: languages
 * 
 * @package WP Option List Table
 * @category Core
 * @author WPWeb
 */

/**
 * Define Some needed predefined variables
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'WW_OLT_DIR' ) ) {
	define( 'WW_OLT_DIR', dirname( __FILE__ ) ); // plugin dir
}
if(!defined('WW_OLT_TEXT_DOMAIN')) { //check if variable is not defined previous then define it
	define('WW_OLT_TEXT_DOMAIN','wwolt'); //this is for multi language support in plugin
}
if( !defined( 'WW_OLT_ADMIN' ) ) {
	define( 'WW_OLT_ADMIN', WW_OLT_DIR . '/includes/admin' ); // plugin admin dir
}
if(!defined('wwoltlevel')) { //check if variable is not defined previous then define its
	define('wwoltlevel','manage_options'); //this is capability in plugin
}
if(!defined('WW_OLT_URL')) {
	define('WW_OLT_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}

if(!defined('WW_OLT_OPTION')) {
	define('WW_OLT_OPTION', 'ww_olt_option' ); // plugin url
}
if( !defined( 'WW_OLT_PLUGIN_BASENAME' ) ) {
	define( 'WW_OLT_PLUGIN_BASENAME', basename( WW_OLT_DIR ) ); //Plugin base name
}
/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */
function ww_olt_load_textdomain() {
	
 // Set filter for plugin's languages directory
	$ww_olt_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$ww_olt_lang_dir	= apply_filters( 'ww_olt_languages_directory', $ww_olt_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'wwolt' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'wwolt', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $ww_olt_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . WW_OLT_PLUGIN_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/wp-option-list-table folder
		load_textdomain( 'wwolt', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/wp-option-list-table/languages/ folder
		load_textdomain( 'wwolt', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'wwolt', false, $ww_olt_lang_dir );
	}
  
}
/**
 * Plugin Activation hook
 * 
 * This hook will call when plugin will activate
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'ww_olt_install' );

function ww_olt_install() {
	
	global $wpdb;
	
}


/**
 * Plugin Deactivation hook
 * 
 * This hook will call when plugin will deactivate
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'ww_olt_uninstall' );

function ww_olt_uninstall() {
	
	global $wpdb;
	
}
/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */
function ww_olt_plugin_loaded() {
 
	// load first plugin text domain
	ww_olt_load_textdomain();
}

//add action to load plugin
add_action( 'plugins_loaded', 'ww_olt_plugin_loaded' );

/**
 * Includes Class Files
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */
global $ww_olt_model,$ww_olt_scripts,$ww_olt_admin;


//includes model class
require_once( WW_OLT_DIR . '/includes/class-ww-olt-model.php');
$ww_olt_model = new Ww_Olt_Model();

//includes scripts class file
require_once ( WW_OLT_DIR .'/includes/class-ww-olt-scripts.php');
$ww_olt_scripts = new Ww_Olt_Scripts();
$ww_olt_scripts->add_hooks();

//includes admin pages
require_once( WW_OLT_ADMIN . '/class-ww-olt-admin.php');
$ww_olt_admin = new Ww_Olt_Admin_Pages();
$ww_olt_admin->add_hooks();
