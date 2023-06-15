<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package WP Option List Table
 * @since 1.0.0
 */
class Ww_Olt_Scripts {
	
	function __construct() {
		
		
	}
	
	public function ww_olt_admin_styles( $hook_suffix ) {
		
		$hook_suffix_pages	= array( 'wp-option-list-table_page_ww_olt_add_form' );
		
		if( in_array( $hook_suffix, $hook_suffix_pages ) ) { // Check plugins page
		
			wp_register_style( 'ww-olt-admin-style', WW_OLT_URL . 'includes/css/ww-olt-admin.css' );	
			wp_enqueue_style( 'ww-olt-admin-style' );
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		
	}
}
