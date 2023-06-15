<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $ww_olt_model, $error, $err_code;

$model = $ww_olt_model;

$page_title = esc_html__( 'Add Option', 'wwolt' );
$save_btn 	= esc_html__( 'Add', 'wwolt' );
$optid 		= 0;
$data 		= array(
	'name' 			=> '',
	'description'	=> '',
	'price'	 		=> ''
);

if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' && !empty( $_GET['optid'] ) ) { //check action & id is set or not
	
	//level page title
	$page_title = esc_html__( 'Edit Option', 'wwolt' );
	
	//level page submit button text either it is Add or Update
	$save_btn = esc_html__( 'Update', 'wwolt' );
	
	//get the level id from url to update the data and get the data of level to fill in editable fields
	$optid = $_GET['optid'];
	
	//get the data from level id
	$data = $model->get_option_data( $optid );
}

$data['name'] 			= isset( $_POST['ww_olt_option']['name'] ) ? $_POST['ww_olt_option']['name'] : $data['name'];
$data['description']	= isset( $_POST['ww_olt_option']['description'] ) ? $_POST['ww_olt_option']['description'] : $data['description'];
$data['price'] 			= isset( $_POST['ww_olt_option']['price'] ) ? $_POST['ww_olt_option']['price'] : $data['price'];

?>

<div class="wrap">
	
	<h2><?php echo $page_title; ?></h2>
	
	<div id="ww-olt-manage-option" class="post-box-container">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div id="manage-option" class="postbox">
					
					<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'wwolt' ); ?>"></div>
					<!-- product box title -->
					<h3 class="hndle"><span style="vertical-align: top;"><?php echo $page_title; ?></span></h3>
					
					<div class="inside">
						
						<form id="ww-olt-manage-option-form" action="" method="post">
							
							<input type="hidden" name="page" value="ww_olt_manage_option_add_form" />
							<table class="form-table sa-manage-level-product-box"> 
								<tbody>
									<tr>
										<th scope="row">
											<label for="ww_olt_option_name"><strong><?php esc_html_e( 'Option Name:', 'wwolt' ); ?></strong></label>
										</th>
										<td>
											<input type="text" id="ww_olt_option_name" name="ww_olt_option[name]" value="<?php echo ( $data['name'] ); ?>" class="regular-text"/>
											<span class="error_message"><?php if(isset($error['name'])){echo $error['name'];} ?></span>
											<br />
											<span class="description"><?php esc_html_e( 'Enter the option name.', 'wwolt' ); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="ww_olt_option_description"><strong><?php esc_html_e( 'Option description:', 'wwolt' ); ?></strong></label>
										</th>
										<td>
											<textarea id="ww_olt_option_description" name="ww_olt_option[description]" class="regular-text"><?php
											echo $data['description'];
											?></textarea>
											<span class="error_message"><?php if(isset($error['description'])){echo $error['description'];} ?></span>
											<br />
											<span class="description"><?php esc_html_e( 'Enter the option description.', 'wwolt' ); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="ww_olt_option_price"><strong><?php esc_html_e( 'Option Price:', 'wwolt' ); ?></strong></label>
										</th>
										<td>
											<input type="text" id="ww_olt_option_price" name="ww_olt_option[price]" value="<?php echo ( $data['price'] ); ?>" class="regular-text"/>
											<span class="error_message"><?php if(isset($error['price'])){echo $error['price'];} ?></span>
											<br />
											<span class="description"><?php esc_html_e( 'Enter the option price.', 'wwolt' ); ?></span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="hidden" name="ww_olt_optid" value="<?php echo $optid ?>" />
											<input type="submit" class="button-primary margin_button" name="ww_olt_option_save" id="ww_olt_option_save" value="<?php echo $save_btn; ?>" />
										</td>
									</tr>
								</tbody>
							</table>
						</form>
						
					</div><!-- End .inside -->
				</div><!-- End #manage-option -->
			</div><!-- End .meta-box-sortables -->
		</div><!-- End .metabox-holder -->
	</div><!-- End #ww-olt-manage-option -->
</div><!-- End .wrap -->