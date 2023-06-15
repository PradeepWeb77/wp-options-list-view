<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 * 
 * Handles all admin functinalities
 *
 * @package WP Option List Table 
 * @since 1.0.0
 */
class Ww_Olt_Admin_Pages{
	
	public $model, $scripts;
	
	function __construct(){
		
		global $ww_olt_model, $ww_olt_scripts;
		$this->model = $ww_olt_model;
		$this->scripts = $ww_olt_scripts;
		
	}
	
	/**
	 * Add Top Level Menu Page
	 *
	 * Runs when the admin_menu hook fires and adds a new
	 * top level admin page and menu item
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_admin_menu() {
	
		//main menu page	
		add_menu_page( esc_html__('WP Option List Table','wwolt'), esc_html__('WP Option List Table','wwolt'), wwoltlevel, 'ww_olt_options', '');
		
		add_submenu_page( 'ww_olt_options', esc_html__('Options List','wwolt'), esc_html__('Options List','wwolt'), wwoltlevel, 'ww_olt_options', array($this,'ww_olt_add_submenu_list_table_page') );
		
		$ww_olt_admin_add_page = add_submenu_page( 'ww_olt_options', esc_html__('Options List','wwolt'), esc_html__('Add New','wwolt'), wwoltlevel, 'ww_olt_add_form', array($this,'ww_olt_add_submenu_page') );
		
		// Add styles for plugins page
		add_action( 'admin_enqueue_scripts', array( $this->scripts, 'ww_olt_admin_styles' ) );
	}
	
	/**
	 * List of all Product
	 *
	 * Handles Function to listing all product
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_add_submenu_list_table_page() {
		
		include_once( WW_OLT_ADMIN . '/forms/ww-olt-option-list.php');
		
	}
	
	/**
	 * Adding Admin Sub Menu Page
	 *
	 * Handles Function to adding add data form
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_add_submenu_page() {
		
		include_once( WW_OLT_ADMIN . '/forms/ww-olt-add-edit-option.php');
		
	}
	
	
	/**
	 * Add action admin init
	 * 
	 * Handles add and edit functionality of product
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_admin_init() {
		
		include_once( WW_OLT_ADMIN . '/forms/ww-olt-option-save.php');
	}
	
	/**
	 * Bulk Delete
	 * 
	 * Handles bulk delete functinalities of product
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_admin_bulk_delete() {
		
		if(((isset( $_GET['action'] ) && $_GET['action'] == 'delete') || (isset( $_GET['action2'] ) && $_GET['action2'] == 'delete')) && isset($_GET['page']) && $_GET['page'] == 'ww_olt_options' ) { //check action and page
		
			// get redirect url
			$redirect_url = add_query_arg( array( 'page' => 'ww_olt_options' ), admin_url( 'admin.php' ) );	
			
			//get bulk option array from $_GET
			$action_on_id = $_GET['option'];
			
			if( count( $action_on_id ) > 0 ) { //check there is some checkboxes are checked or not 
				
				//if there is multiple checkboxes are checked then call delete in loop
				$args = array (
									'olt_ids' => $action_on_id
								);
				
				$this->model->ww_olt_bulk_delete( $args );
				
				$redirect_url = add_query_arg( array( 'message' => '3' ), $redirect_url );
				
				//if bulk delete is performed successfully then redirect 
				wp_redirect( $redirect_url ); 
				exit;
			} else {
				//if there is no checboxes are checked then redirect to listing page
				wp_redirect( $redirect_url ); 
				exit;
			}			
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function add_hooks() {
		
		//add new admin menu page
		add_action('admin_menu',array($this,'ww_olt_admin_menu'));
		
		//add admin init for saving data
		add_action( 'admin_init' , array($this,'ww_olt_admin_init'));
		
		//add admin init for bult delete functionality
		add_action( 'admin_init' , array($this,'ww_olt_admin_bulk_delete'));
		
	}
}
