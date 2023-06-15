<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Options List
 *
 * Handle option save and edit option
 * 
 * @package WP Option List Table
 * @since 1.0.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Ww_Olt_Options_List extends WP_List_Table {

	public $model;
	
	function __construct(){
	
        global $ww_olt_model, $page;
                
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'option',     //singular name of the listed records
							            'plural'    => 'options',    //plural name of the listed records
							            'ajax'      => false        //does this table support ajax?
							        ) );   
		
		$this->model = $ww_olt_model;
		
    }
    
    /**
	 * Displaying Options List
	 *
	 * Does prepare the data for displaying the options in the table.
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */	
	function display_options() {
		
		//if search is call then pass searching value to function for displaying searching values
		$search = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';
		
		//in case of search make parameter for retriving search data
		$search_arr = array(
									'search'		=>	$search
								);
							
		
		//call function to retrive data from table
		$data = $this->model->get_all_option_data( $search_arr );
		$resultdata = array();
		
		if( !empty( $data ) ) {
			
			foreach ($data as $key => $value) {
				
				$resultdata[$key]['ID']				= $key;
				$resultdata[$key]['name']			= $value['name'];
				$resultdata[$key]['description']	= $value['description'];
				$resultdata[$key]['price']			= $value['price'];
			}
		}
		
		return $resultdata;
	}
	
	/**
	 * Mange column data
	 *
	 * Default Column for listing table
	 * 
	 * @package WP Option List Table
	 * @since 1.0.0
	 */
	function column_default( $item, $column_name ){
	
        switch( $column_name ){
            case 'name':
            case 'description':
            case 'price' :
				return $item[ $column_name ];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
	
   
    /**
     * Manage Edit/Delete Link
     * 
     * Does to show the edit and delete link below the column cell
     * function name should be column_{field name}
     * For ex. I want to put Edit/Delete link below the post title 
     * so i made its name is column_post_title
     * 
     * @package WP Option List Table
     * @since 1.0.0
     */
    function column_name($item){
    
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&optid=%s">'.esc_html__('Edit', 'wwolt').'</a>','ww_olt_add_form','edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&option[]=%s">'.esc_html__('Delete', 'wwolt').'</a>',$_REQUEST['page'],'delete',$item['ID']),
         );
         
        //Return the title contents	        
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['name'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     *
     * Handles which columns to show in table
     * 
	 * @package WP Option List Table
	 * @since 1.0.0
     */
	function get_columns(){
	
        $columns = array(
	        					'cb'      		=> '<input type="checkbox" />', //Render a checkbox instead of text
					            'name'			=> esc_html__( 'Option Name', 'wwolt' ),
					            'description'	=> esc_html__( 'Description', 'wwolt' ),
					            'price'			=> esc_html__( 'Price', 'wwolt' )
					        );
        return $columns;
    }
	
    /**
     * Sortable Columns
     *
     * Handles soratable columns of the table
     * 
	 * @package WP Option List Table
	 * @since 1.0.0
     */
	function get_sortable_columns() {
		
		
        $sortable_columns = array(
							            'name'	=> array( 'name', true ),     //true means its already sorted
							            'price'	=> array( 'price', true ),
							        );
        return $sortable_columns;
    }
	
	function no_items() {
		
		//message to show when no records in database table
		esc_html_e( 'No Options found.', 'wwolt' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
     * 
	 * @package WP Option List Table
	 * @since 1.0.0
     */
	function get_bulk_actions() {
		
		//bulk action combo box parameter
		//if you want to add some more value to bulk action parameter then push key value set in below array
        $actions = array(
					            'delete'    => 'Delete'
					        );
        return $actions;
    }
    
	function process_bulk_action() {
    
        //Detect when a bulk action is being triggered...
        if( 'delete' === $this->current_action() ) {
            
        	wp_die(esc_html__( 'Items deleted (or they would be if we had items to delete)!', 'wwolt' ));
        } 
        
    }
	
	function prepare_items() {
        
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns	= $this->get_columns();
        $hidden		= array();
        $sortable	= $this->get_sortable_columns();
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array( $columns, $hidden, $sortable );
        
         /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
		$data = $this->display_options();
		
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder( $a, $b ) {
            
        	$orderby	= ( !empty($_REQUEST['orderby']) ) 	? $_REQUEST['orderby'] 	: 'ID'; 	// If no sort, default to title
            $order		= ( !empty($_REQUEST['order']) ) 	? $_REQUEST['order'] 	: 'desc'; 	// If no order, default to asc
            
            // If field value is integer
            $ww_olt_int_sort = array( 'ID', 'price' );
            
            // Integer value sorting else string sorting
            if( in_array($orderby, $ww_olt_int_sort) ) {
			    
            	if ($a[$orderby] == $b[$orderby]) {
			        return 0;
			    }
			    $result = ($a[$orderby] < $b[$orderby]) ? -1 : 1;
			    
            } else {
            	$result		= strcmp( $a[$orderby], $b[$orderby] ); // Determine sort order
            }
            
            return ( $order==='asc' ) ? $result : -$result; // Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
		
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args	(	array(
									            'total_items' => $total_items,                  //WE have to calculate the total number of items
									            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
									            'total_pages' => ceil( $total_items/$per_page ) //WE have to calculate the total number of pages
										)
									);
    }
    
}

//Create an instance of our package class...
$OptionListTable = new Ww_Olt_Options_List();

//Fetch, prepare, sort, and filter our data...
$OptionListTable->prepare_items();

$manage_option_page = add_query_arg( array( 'page' => 'ww_olt_add_form' ), admin_url( 'admin.php' ) );
?>

<div class="wrap">
    <h2>
    	<?php esc_html_e( 'Options', 'wwolt' ); ?>
    	<a class="add-new-h2" href="<?php echo $manage_option_page; ?>"><?php esc_html_e( 'Add New','wwolt' ); ?></a>
    </h2>
   	<?php 
   		$html = '';
		if(isset($_GET['message']) && !empty($_GET['message']) ) { //check message
			
			if( $_GET['message'] == '1' ) { //check insert message
				$html .= '<div class="updated settings-error" id="setting-error-settings_updated">
							<p><strong>'.esc_html__("Option Inserted Successfully.",'wwolt').'</strong></p>
						</div>';
			} else if($_GET['message'] == '2') {//check update message
				$html .= '<div class="updated" id="message">
							<p><strong>'.esc_html__("Option Updated Successfully.",'wwolt').'</strong></p>
						</div>';
			} else if($_GET['message'] == '3') {//check delete message
				$html .= '<div class="updated" id="message">
							<p><strong>'.esc_html__("Option deleted Successfully.",'wwolt').'</strong></p>
						</div>';
			} else if($_GET['message'] == '4') {//check delete message
				$html .= '<div class="updated" id="message">
							<p><strong>'.esc_html__("Status Changed Successfully.",'wwolt').'</strong></p>
						</div>';
			}
		}
		echo $html;
	?>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="option-filter" method="get">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        
        <!-- Search Title -->
        <?php $OptionListTable->search_box( esc_html__( 'Search', 'wwolt' ), 'ww_olt_search' ); ?>
        
        <!-- Now we can render the completed list table -->
        <?php $OptionListTable->display(); ?>
        
    </form>
	
</div>