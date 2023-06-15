<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Save Option
 *
 * Handle option save and edit option
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */

global $error, $err_code, $ww_olt_model;

$model = $ww_olt_model;

if( !empty( $_POST['ww_olt_option_save'] ) && !empty( $_POST['ww_olt_option'] ) ) {
		
	$error		= array();
	$err_code	= 0;

	if( empty( $_POST['ww_olt_option']['name'] )){
		$error['name'] = esc_html__( 'Please enter option name.', 'wwolt' );
		$err_code = 1;
	}
	
	if( empty( $_POST['ww_olt_option']['description'] )){
		$error['description'] = esc_html__( 'Please enter option description.', 'wwolt' );
		$err_code = 1;
	}
	
	if( empty( $_POST['ww_olt_option']['price'] )){
		$error['price'] = esc_html__( 'Please enter option price.', 'wwolt' );
		$err_code = 1;
	}
	
	if( isset( $_POST['ww_olt_optid'] ) && !empty( $_POST['ww_olt_optid'] ) && $err_code != 1 ) {
		
		$get_all_option	= $model->get_all_option_data();
		$last_key		= $_POST['ww_olt_optid'];
		
		$get_all_option[$last_key] = $model->ww_olt_escape_slashes_deep( $_POST['ww_olt_option'] );
		
		$model->update_all_option_data( $get_all_option );
		
		// Get redirect url
		$redirect_url = add_query_arg( array( 'page' => 'ww_olt_options', 'message' => '2' ), admin_url( 'admin.php' ) );
		
		wp_redirect( $redirect_url );
		
	} else {
		
		if( $err_code != 1 ) {
			
			$get_all_option	= $model->get_all_option_data();
			
			$last_key = 1;
			if( !empty( $get_all_option ) && is_array( $get_all_option ) ) {
				
				$keys = array_keys( $get_all_option );
				$last_key = end( $keys );
				$last_key = $last_key + 1;
			}
			
			$get_all_option[$last_key] = $model->ww_olt_escape_slashes_deep( $_POST['ww_olt_option'] );
			
			$model->update_all_option_data( $get_all_option );
			
			// Get redirect url
			$redirect_url = add_query_arg( array( 'page' => 'ww_olt_options', 'message' => '1' ), admin_url( 'admin.php' ) );
			
			wp_redirect( $redirect_url );
		}
	}
}