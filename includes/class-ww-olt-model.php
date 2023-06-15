<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Ww_Olt_Model{
	
	function __construct(){
		
	}
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_object_to_array( $result ) {
	    
		$array = array();
	    
		foreach( $result as $key => $value ) {	
	        
			if ( is_object( $value ) ) {
	            
				$array[$key] = $this->ww_olt_object_to_array($value);
	        } else {
	        	
	        	$array[$key] = $value;
	        }
	    }
		
	    return $array;
	}
	
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_escape_attr( $data ) {
		
		return esc_attr( stripslashes( $data ) );
	}
	
	/**
	 * Stripslashes 
 	 * 
  	 * It will strip slashes from the content
	 *
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_escape_slashes_deep( $data = array(), $flag=false, $limited = false ) { 
		
		if( $flag != true ) {
			
			$data = $this->ww_olt_nohtml_kses($data);
			
		} else {
			
			if( $limited == true ) {
				$data = wp_kses_post( $data );
			}
			
		}
		
		$data = stripslashes_deep($data);
		return $data;
	}
	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function ww_olt_nohtml_kses( $data = array() ) {
		
		
		if ( is_array($data) ) {
			
			$data = array_map(array($this,'ww_olt_nohtml_kses'), $data);
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}
	
	/**
	 * Get Option Data
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function get_option_data( $optid = '' ) {
		
		$default_option_data	= array(
									'name' 			=> '',
									'description'	=> '',
									'price'	 		=> ''
								);
		
		if( !empty( $optid ) ) {
			
			$all_options	= $this->get_all_option_data();
			$option_data	= isset( $all_options[$optid] ) ? $all_options[$optid] : $default_option_data;
		} else {
			
			$option_data	= $default_option_data;
		}
		
		return $option_data;
	}
	
	/**
	 * Get All Option Data
	 * 
	 * handle to get all options data
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function get_all_option_data( $args = array() ) {
		
		
		if( empty( $args ) ) {
			
			return get_option( WW_OLT_OPTION, array() );
		} else {
			
			return get_option( WW_OLT_OPTION, array() );
		}
	}
	
	/**
	 * Update All Option Data
	 * 
	 * handle to update all options data
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function update_all_option_data( $all_option_data = array() ) {
		
		return update_option( WW_OLT_OPTION, $all_option_data );
	}
	
	/**
	 * Bulk Deletion
	 *
	 * Does handle deleting options from the
	 * wp_options table.
	 *
	 * @package WP Option List Table
 	 * @since 1.0.0
	 */
	function ww_olt_bulk_delete( $args = array() ) {
		
		if( isset( $args['olt_ids'] ) && !empty( $args['olt_ids'] ) ) {
			
			$get_all_options = $this->get_all_option_data();
			
			foreach ( $args['olt_ids'] as $olt_id ) {
				
				if( isset( $get_all_options[$olt_id] ) ) {
					
					unset( $get_all_options[$olt_id] );
				}
			}
			
			$this->update_all_option_data( $get_all_options );
		}
	}
	
}
