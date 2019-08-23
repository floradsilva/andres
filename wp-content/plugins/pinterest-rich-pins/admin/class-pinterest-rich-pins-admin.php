<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/admin
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Pinterest_Rich_Pins_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pinterest_Rich_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pinterest_Rich_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pinterest-rich-pins-admin.css', array(), $this->version, 'all' );
		wp_register_style( "pinterest_rich_pins_bootstrap_min_css", plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_register_style( "pinterest_rich_pins_bootstrap-datepicker-min-css", plugin_dir_url( __FILE__ ) . 'css/bootstrap-datepicker.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "pinterest_rich_pins_bootstrap-font-awesome-css", plugin_dir_url( __FILE__ ) . 'css/font-awesome.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pinterest_Rich_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pinterest_Rich_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pinterest-rich-pins-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script( "pinterest_rich_pins_bootstrap_min_js", plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( "pinterest_rich_pins_bootstrap-datepicker-min-js", plugin_dir_url( __FILE__ ) . 'js/bootstrap-datepicker.min.js', array( 'jquery' ), $this->version, false );

	}

	//Display plugin active or inactive error message
	function pinterest_rich_pin_plugin_display_message(){
		$text_domain = PINTEREST_RICH_PINS_TEXT_DOMAIN;
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if (! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			echo '<div class="notice error"><p>';
			printf(__('If you want to use "Woo Quote A Product" plugin then, you must install and activate <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">Woocommerce</a> plugin first.',$text_domain));
			echo '</p></div>';
		}
	}

	/*
	 * Adding the menu page
	 */

	public function register_pinterest_rich_pin_custom_submenu_page(){
		add_menu_page("Rich Pins","Pinterest Rich Pins", "manage_options", "pinterest_rich_pin", array( $this , 'pinterest_rich_pin_queue_callback'), 'dashicons-sticky', 30 );
		add_submenu_page( "pinterest_rich_pin", __("Rich Pins",PINTEREST_RICH_PINS_TEXT_DOMAIN), __("Queue List",PINTEREST_RICH_PINS_TEXT_DOMAIN), "manage_options", "pinterest_rich_pin", array( $this , 'pinterest_rich_pin_queue_callback'));
		add_submenu_page( "pinterest_rich_pin", __("Rich Pins",PINTEREST_RICH_PINS_TEXT_DOMAIN), __("Settings",PINTEREST_RICH_PINS_TEXT_DOMAIN), "manage_options", "pinterest_rich_pin_settings", array( $this , 'pinterest_rich_pin_callback'));
		add_submenu_page( "pinterest_rich_pin", __("Rich Pins",PINTEREST_RICH_PINS_TEXT_DOMAIN), __("Log",PINTEREST_RICH_PINS_TEXT_DOMAIN), "manage_options", "pinterest_rich_pin_log", array( $this , 'pinterest_rich_pin_log_callback'));
	}

	// Added menu pages callback functions
	public function pinterest_rich_pin_callback(){

		// Submission of the data starts from here
		// Save api settings for the Pinterest
		if(isset($_POST["pinterest_wcrp_submit"])){
			$appId = isset($_POST["pinterest_wcrp_app_id"]) ? $_POST["pinterest_wcrp_app_id"] : "";
			$appSecKey = isset($_POST["pinterest_wcrp_app_secret_id"]) ? $_POST["pinterest_wcrp_app_secret_id"] : "";

			if(empty($appId)){
				$errorMsg = __("Please insert App ID",PINTEREST_RICH_PINS_TEXT_DOMAIN);
			}
			else if(empty($appSecKey)){
				$errorMsg = __("Please insert App Secret ID",PINTEREST_RICH_PINS_TEXT_DOMAIN);
			}
			else{
				$appId = $_POST["pinterest_wcrp_app_id"];
				$appSecKey = $_POST["pinterest_wcrp_app_secret_id"];

				update_option("pinterest_wcrp_app_id",$appId);
				update_option("pinterest_wcrp_app_secret_id",$appSecKey);

				/*
				 * Calling the object of the pinterest
				 */
				$pinterest = new Vsourz\Pinterest\Pinterest($appId, $appSecKey);
				$redirect_url = menu_page_url('pinterest_rich_pin_settings',false);
				$pinterest_app_redirect = $pinterest->auth->getLoginUrl($redirect_url, array('read_public', 'write_public'));
				wp_redirect($pinterest_app_redirect);
				exit;

			}

		}
		// Save general settings
		if(isset($_POST["pinterest_wcrp_general_setting_submit"])){

			$this->save_general_settings_pinterest($_POST);

		}

		// Create pinterest class object
		$pinterest = get_pinterest_object();

		/*
		 * Checking the code that if the code found is valid or not
		 * If not valid then throw the error of the
		 */
		if (isset($_GET["code"]) && isset($pinterest) && !empty($pinterest)) {

		    $token = $pinterest->auth->getOAuthToken($_GET["code"]);
		    $token_data = $token;
		    if (is_array($token_data)) {

		    	if ($token_data['success'] == 'false'){

			    	delete_option("pinterest_wcrp_app_is_valid");
			    	$error_message = $token_data['message'];

			    } else {
			    	/*
			    	 * Updating the values as token received
			    	 */
			    	update_option("pinterest_wcrp_code",$_GET["code"]);
			    	update_option("pinterest_wcrp_token",$token_data['data']->access_token);
			    	update_option("pinterest_wcrp_app_is_valid",true);
				    $pinterest->auth->setOAuthToken($token_data['data']->access_token);
				    wp_redirect(menu_page_url('pinterest_rich_pin_settings',false));
			    }

		    }

		}

		$pinterest_code = get_option("pinterest_wcrp_code");
		$pinterest_token = get_option("pinterest_wcrp_token");
		if( !empty($pinterest_token) && !empty( $pinterest ) ){
			$pinterest->auth->setOAuthToken($pinterest_token);
		}

		$isValid = get_option("pinterest_wcrp_app_is_valid");
		if($isValid){
			/*
			 * Checking if the token is valid
			 */

			//$token_check = $pinterest->users->me();

			/*
			 * Get the boards from the Pinterest API
			 */
			$boards_response = $pinterest->users->getMeBoards();

			if( !empty( $boards_response ) && isset($boards_response['code']) && $boards_response['code'] == "200" ){
				foreach ($boards_response['data']->all() as $board_response) {

					$board_records[] = $board_response->toArray();

				}
			} else if(!empty( $boards_response ) && isset($boards_response['code']) && $boards_response['success'] == "false") {
				$error_message = $boards_response['message'];
				delete_option("pinterest_wcrp_app_is_valid");
			}
		}
		$isValid = get_option("pinterest_wcrp_app_is_valid");
		$appId = get_option("pinterest_wcrp_app_id");
		$appSecKey = get_option("pinterest_wcrp_app_secret_id");
		$manage_log = get_option("pinterest_wcrp_manage_log");
		$time_gap = get_option("pinterest_wcrp_time_gap");
		$manage_metatags = get_option("pinterest_wcrp_manage_metatags");
		$manage_boards = get_option("pinterest_wcrp_manage_board");

		include PINTEREST_RICH_PINS_DIR_PATH.'admin/partials/pinterest_rich_pin_settings.php';

	}

	public function save_general_settings_pinterest($POST){
		$manage_log = isset($POST["pinterest_wcrp_manage_log"]) ? sanitize_text_field($POST["pinterest_wcrp_manage_log"]) : "";
		$time_gap = isset($POST["pinterest_wcrp_time_gap"]) ? (int) sanitize_text_field($POST["pinterest_wcrp_time_gap"]) : "";
		$manage_metatags = isset($POST["pinterest_wcrp_manage_metatags"]) ? sanitize_text_field($POST["pinterest_wcrp_manage_metatags"]) : "";
		$manage_boards = isset($POST["pinterest_wcrp_manage_board"]) ? sanitize_text_field($POST["pinterest_wcrp_manage_board"]) : "";

		if(!is_int($time_gap) || empty($time_gap)){
			$time_gap = 1;
		}
		else if( $time_gap>3 ){
			$time_gap = 3;
		}

		update_option("pinterest_wcrp_manage_log",$manage_log);
		update_option("pinterest_wcrp_time_gap",$time_gap);
		update_option("pinterest_wcrp_manage_metatags",$manage_metatags);
		update_option("pinterest_wcrp_manage_board",$manage_boards);
	}

	public function pinterest_rich_pin_log_callback(){
		include PINTEREST_RICH_PINS_DIR_PATH.'admin/partials/pinterest_rich_pin_log.php';
	}

	public function pinterest_rich_pin_queue_callback(){
		include PINTEREST_RICH_PINS_DIR_PATH.'admin/partials/pintrest_rich_pin_queue.php';
	}

	/*
	 * This function is to execute page init actions. Like save any form.
	 */
	public function pinterest_rich_pin_init_action(){
		// include PINTEREST_RICH_PINS_DIR_PATH . 'includes/class/init_actions.php';
	}

	/*
	 * This function is to add a new column for "Products"
	 */
	function pinterest_rich_pin_products_add_columns($columns){
		$result = $columns + array("pinterest_rich_pin" => __( 'Pinterest Actions',PINTEREST_RICH_PINS_TEXT_DOMAIN ) );
		$columns = $result;
		return $columns;
	}

	function pinterest_rich_pin_products_columns($column , $post_id){
		//////// display cuystom values for custom columns
		global $post;
		$arrMeta = get_post_meta( $post_id );
		switch( $column ) {
			// For Sortorder column
			case 'pinterest_rich_pin':
				$ret = pinterest_rich_pin_get_data_admin( "", ' product_id = "'.$post_id.'" AND status = "processing" ' , "ASC" , "id" );

				if(empty($ret)){

					if(isset($arrMeta['is_pinterest_product'][0]) && !empty($arrMeta['is_pinterest_product'][0]) && $arrMeta['is_pinterest_product'][0] == "yes"){
						echo '<a href="javascript:void(0);" class="pinterest_update_pin pinterest_rich_pin_action-btn" data-id="'.$post->ID.'" name="pinterest_update_pin['.$post->ID.']" title="'.__("Update to Pinterest",PINTEREST_RICH_PINS_TEXT_DOMAIN).'"><span class="fa fa-refresh"></span></a>';
						echo '<a href="javascript:void(0);" class="pinterest_remove_pin pinterest_rich_pin_action-btn" data-id="'.$post->ID.'" name="pinterest_remove_pin['.$post->ID.']" title="'.__("Delete from Pinterest",PINTEREST_RICH_PINS_TEXT_DOMAIN).'"><span class="fa fa-trash"></span></a>';
					}
					else{
						echo '<a herf="javascript:void(0);" class="pinterest_add_pin pinterest_rich_pin_action-btn" data-id="'.$post->ID.'" name="pinterest_add_pin['.$post->ID.']" title="'.__("Add to Pinterest",PINTEREST_RICH_PINS_TEXT_DOMAIN).'" ><span class="fa fa-plus"></span></a>';
					}
				}
				else{
					$addCount = 0;
					$updateCount = 0;
					$deleteCount = 0;

					foreach($ret as $queue){
						if($queue->action && $queue->action == PINTEREST_RICH_PINS_ADD_PIN){
							$addCount++;
						}
						if($queue->action && $queue->action == PINTEREST_RICH_PINS_UPDATE_PIN){
							$updateCount++;
						}
						if($queue->action && $queue->action == PINTEREST_RICH_PINS_DELETE_PIN){
							$deleteCount++;
						}
					}

					$html = "";
					if(!empty($addCount)){
						$html .= "<p>".__(PINTEREST_RICH_PINS_ADD_PIN,PINTEREST_RICH_PINS_TEXT_DOMAIN)."(".$addCount.")</p>";
					}
					if(!empty($updateCount)){
						if(empty($html)){
							$html .= "<p>".__(PINTEREST_RICH_PINS_UPDATE_PIN,PINTEREST_RICH_PINS_TEXT_DOMAIN)."(".$updateCount.")</p>";
						}
						else{
							$html .= "<p>".__(PINTEREST_RICH_PINS_UPDATE_PIN,PINTEREST_RICH_PINS_TEXT_DOMAIN)."(".$updateCount.")</p>";
						}
					}
					if(!empty($deleteCount)){
						if(empty($html)){
							$html .= "<p>".__(PINTEREST_RICH_PINS_DELETE_PIN,PINTEREST_RICH_PINS_TEXT_DOMAIN)."(".$deleteCount.")</p>";
						}
						else{
							$html .= "<p>".__(PINTEREST_RICH_PINS_DELETE_PIN,PINTEREST_RICH_PINS_TEXT_DOMAIN)."(".$deleteCount.")</p>";
						}
					}

					echo $html;

				}
				break;

			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}
	}

	/*
	 * This function is to add product to pinterest ajax callback
	 */
	function add_product_to_pinterest_pin_callback(){

		if(isset($_POST["id"]) && !empty($_POST["id"])){
			$post_id = $_POST["id"];
		}
		else{
			wp_die("Un-authorized Access.");
		}

		update_post_meta($post_id,"is_pinterest_product","yes");
		$res = pinterest_rich_pin_update_data_admin($post_id,"add");
		
		// Error Occurs
		if(is_array($res)){
			foreach($res as $msg){
				echo $msg;
			}
		}
		else{
			echo "y";
		}
		
		wp_die();
	}

	/*
	 * This function is to update product to pinterest ajax callback
	 */
	function update_product_to_pinterest_pin_callback(){

		if(isset($_POST["id"]) && !empty($_POST["id"])){
			$post_id = $_POST["id"];
		}
		else{
			wp_die("Un-authorized Access.");
		}

		update_post_meta($post_id,"is_pinterest_product","yes");
		$res = pinterest_rich_pin_update_data_admin($post_id,"edit");

		// Error Occurs
		if(is_array($res)){
			foreach($res as $msg){
				echo $msg;
			}
		}
		else{
			echo "y";
		}
		
		wp_die();
	}

	/*
	 * This function is to remove product from pinterest ajax callback
	 */
	function remove_product_to_pinterest_pin_callback(){
		if(isset($_POST["id"]) && !empty($_POST["id"])){
			$post_id = $_POST["id"];
		}
		else{
			wp_die("Un-authorized Access.");
		}


		$res = pinterest_rich_pin_product_detail_update_data_admin($post_id,"all","delete",false);
		update_post_meta($post_id,"is_pinterest_product","no");
		
		echo "y";
		wp_die();
	}

	/*
	 * This function is to empty log file
	 */
	function empty_log_pinterest_rich_pin_callback(){
		$ret = empty_log_message();

		if($ret){
			echo "y";
		}
		else{
			echo "n";
		}

		wp_die();
	}

	/*
	 * This function is to remove action from queue
	 */
	function remove_action_from_queue_callback(){

		if(isset($_POST["id"]) && !empty($_POST["id"])){
			$ret = remove_action_from_queue($_POST["id"]);

			if($ret){
				echo "y";
			}
			else{
				echo "n";
			}
		}

		wp_die();
	}
	
	/*
	 * This function is to retry action from queue
	 */
	function retry_action_from_queue_callback(){

		if(isset($_POST["id"]) && !empty($_POST["id"])){
			$ret = retry_action_from_queue($_POST["id"]);
			
			if($ret){
				echo "y";
			}
			else{
				echo "n";
			}
		}

		wp_die();
	}

	/*
	 * This function is to remove product from pinterest ajax callback
	 */
	function queue_bulk_action_submit_admin_callback(){
		if(!isset($_POST["queueids"]) || empty($_POST["queueids"])){
			wp_die("Un-authorized Access.");
		}
		if(!isset($_POST["queue_action"]) || empty($_POST["queue_action"])){
			wp_die("Un-authorized Access.");
		}

		$queueids = $_POST["queueids"];
		$queue_action = $_POST["queue_action"];

		if($queue_action == "remove"){
			$ret = remove_action_from_queue($queueids);
			if($ret){
				echo "y";
			}
			else{
				echo "n";
			}
			wp_die();
		}
		else if($queue_action == "retry"){
			foreach($queueids as $post_id){
				$ret = add_action_in_queue($post_id);

				if(!$ret){
					echo "n";
					wp_die();
				}
			}
			echo "y";
		}

		wp_die();
	}

	/*
	 * This function is to provide html for product detail page
	 */
	function add_metaboxes_product_callback(){
		$cpt_name 	= 'product';
		$text_domain = PINTEREST_RICH_PINS_TEXT_DOMAIN;

		//Display Schedule information
		add_meta_box(
			'pinterest_rich_pin_details',__( 'Rich Pin Details', $text_domain),
			array($this,'pinterest_rich_pin_details_callback'),
			$cpt_name,'normal','high');
	}


	public function pinterest_rich_pin_details_callback($post){
		include(PINTEREST_RICH_PINS_DIR_PATH . 'admin/partials/pinterest_rich_pin_details.php');
	}

	/*
	 * This function is to remove product from pinterest ajax callback
	 */
	function save_post_product_callback($post_id){
		if(isset($_POST["is_pinterest_product"])){
			update_post_meta($post_id,"is_pinterest_product","yes");
			
			$action = isset($_POST["pinterest_action"]) ? $_POST["pinterest_action"] : "";
			$images = isset($_POST["pinterest_product_images"]) ? $_POST["pinterest_product_images"] : "";
			
			if(!empty($images)){
				$res = pinterest_rich_pin_product_detail_update_data_admin($post_id,$images,$action);
			}
			else{
				$res = pinterest_rich_pin_product_detail_update_data_admin($post_id,false,$action);
			}
		}
		else{
			update_post_meta($post_id,"is_pinterest_product","no");
		}
	}

	/*
	 * This function is to remove product from pinterest ajax callback
	 */
	function pinterest_rich_pin_custom_bulk_actions($actions){

		$actions["add_to_pin"] = __("Add/Update To Pinned",PINTEREST_RICH_PINS_TEXT_DOMAIN);
		$actions["remove_from_pin"] = __("Remove From Pinned",PINTEREST_RICH_PINS_TEXT_DOMAIN);
		return $actions;

	}

	function pinterest_rich_pin_custom_bulk_actions_handler( $redirect_to, $doaction, $post_ids ){
		if ( $doaction !== 'add_to_pin' && $doaction !== 'remove_from_pin' ){
			return $redirect_to;
		}

		foreach ( $post_ids as $post_id ) {

			if($doaction == "add_to_pin"){
				update_post_meta($post_id,"is_pinterest_product","yes");
				if(has_post_thumbnail($post_id)) {
					$ret = pinterest_rich_pin_update_data_admin($post_id,"edit");
				}
			}
			else if($doaction == "remove_from_pin"){
				remove_product_from_pinterest_pin($post_id);
			}
		}

		return $redirect_to;
	}

	/*
	 * This is cron call back function
	 * All the pinterest requests will be sent from here.
	 */
	function pinterest_rich_pin_cron_schedules_hooks_callback(){
		// if(!isset($_GET["p_test"])){ return; }
		// Get some records from queue list
		$board = "";
		$error_message = "";
		$id='';
		$where = " 1=1 ";
		$order = " ASC ";
		$orderby = " id ";
		$LIMIT = "LIMIT 10";
		$queue_list = pinterest_rich_pin_get_data_admin($id,$where,$order,$orderby,$LIMIT);

		if(!empty($queue_list)){

			// Check if app is valid
			$isValid = get_option("pinterest_wcrp_app_is_valid");

			// Create pinterest class object
			$pinterest = get_pinterest_object();

			if(!$isValid){
				$pinterest_code = get_option("pinterest_wcrp_code");
				$token = $pinterest->auth->getOAuthToken($pinterest_code);

				// Add log
				$manage_log = get_option("pinterest_wcrp_manage_log");
				if($manage_log){
					add_log_message("Get Token :: \n".json_encode($token));
				}

				if (is_array($token)) {
					if ($token['success'] == 'false'){
						delete_option("pinterest_wcrp_app_is_valid");
						$error_message = $token['message'];
					}
					else {
						/*
						 * Updating the values as token received
						 */
						update_option("pinterest_wcrp_token",$token['data']->access_token);
						update_option("pinterest_wcrp_app_is_valid",true);
						$isValid = true;
					}
				}
			}

			// If app is valid
			if($isValid){
				$manage_boards = get_option("pinterest_wcrp_manage_board");

				// Set auth for pinterest
				$pinterest_code = get_option("pinterest_wcrp_code");
				$pinterest_token = get_option("pinterest_wcrp_token");
				if( !empty($pinterest_token) && !empty( $pinterest ) ){
					$pinterest->auth->setOAuthToken($pinterest_token);
				}

				// Get boards
				$boards_response = $pinterest->users->getMeBoards();

				// Add log
				$manage_log = get_option("pinterest_wcrp_manage_log");
				if($manage_log){
					add_log_message("Get Boards :: \n".json_encode($boards_response));
				}

				if( !empty( $boards_response ) && isset($boards_response['code']) && $boards_response['code'] == "200" ){

					foreach ($boards_response['data']->all() as $board_response) {
						$boards = $board_response->toArray();

						if($manage_boards == $boards['id']){
							$url = $boards["url"];
							$pinterest_url = "https://www.pinterest.com/";
							$board = rtrim(str_ireplace($pinterest_url,"",$url),"/");
							break;
						}
					}
				}
				else if(!empty( $boards_response ) && isset($boards_response['code']) && $boards_response['success'] == "false") {
					$error_message = $boards_response['message'];
					delete_option("pinterest_wcrp_app_is_valid");
				}
			}

			if(!empty($board)){
				foreach($queue_list as $queue_item){

					$action = $queue_item->action;
					$product_id = $queue_item->product_id;
					$image_id = $queue_item->image_id;
					$note =  get_the_title($queue_item->product_id);
					$created_at = time();
					$image_url = wp_get_attachment_url($image_id);
					$original_link = get_the_permalink($product_id);

					$params = array(
								"board"			=>	$board,
								"image_url"		=>	$image_url,
								"note"			=>	$note,
								"created_at"	=>	$created_at,
								"link"			=>	$original_link
							);
					
					
					if( $action == PINTEREST_RICH_PINS_ADD_PIN ){
						$args = array(
									"params"		=>	$params,
									"pinterest"		=>	$pinterest,
									"product_id"	=>	$product_id,
									"queue_id"		=>	$queue_item->id,
									"image_id"		=>	$image_id
								);
								
						$res = pinterest_rich_pin_call_db_function("pinterest_create_pin",$args,true);

					}
					else if( $action == PINTEREST_RICH_PINS_UPDATE_PIN ){
						$pinterest_id = get_pinterest_id($product_id,$image_id);
						
						if(!empty($pinterest_id)){
							$args = array(
										"params"		=>	$params,
										"pinterest"		=>	$pinterest,
										"pinterest_id"	=>	$pinterest_id,
										"queue_id"		=>	$queue_item->id
									);

							$res = pinterest_rich_pin_call_db_function("pinterest_update_pin",$args,true);
						}
					}
					else if( $action == PINTEREST_RICH_PINS_DELETE_PIN ){

						$pinterest_id = $queue_item->pinterest_id;
						$args = array(
									"pinterest"		=>	$pinterest,
									"pinterest_id"	=>	$pinterest_id,
									"queue_id"		=>	$queue_item->id
								);
						
						$res = pinterest_rich_pin_call_db_function("pinterest_delete_pin",$args,true);
						
						// Remove attachment data
						$params = array("product_id" => $product_id,"attachment_id" => $image_id);
						$ret = pinterest_rich_pin_call_db_function("remove_attachment_entry",$params,true);
					}

					// Getting execution delay
					$delayMin = (int) get_option("pinterest_wcrp_time_gap");
					if(empty($delayMin) || !is_int($delayMin)){
						$time_gap = 60;
					}
					else{
						$time_gap = $delayMin*60;
					}

					sleep($time_gap);
					// sleep(20);
				}
			}
		}
	}
	
	/*
	 * This function is to add loader images
	 */
	function pinterest_rich_pin_add_loader(){
		if((isset($_GET["page"]) && $_GET["page"] == "pinterest_rich_pin") || ( isset($_GET["post_type"]) && $_GET["post_type"] == "product" )){
			?><div class="pinterest_rich_pin_loader-image"></div><?php

			$isValid = get_option("pinterest_wcrp_app_is_valid");
			if(!$isValid){
				?><div class="pinterest_rich_pin-expired-token error-overlay">
					<div class="error-msg-box">
						<div class="error-msg"><strong><span class="fa fa-exclamation-circle"></span><?php _e('It seems that your App token is expired. Please save API details once to generate new token.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></strong></div>
					</div>
				</div><?php
			}

			?><script>

			pinterest_rich_pin_api_check();
			function pinterest_rich_pin_api_check(){
				var action = { 'action':'pinterest_rich_pin_api_check' };

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data : action,
					async: false,
					success: function(data) {

						if(data == "y"){
							jQuery(".pinterest_rich_pin-expired-token").remove();
						}
						else{

							if(jQuery(".pinterest_rich_pin-expired-token").length<1){
								jQuery("#footer").html('<div class="pinterest_rich_pin-expired-token error-overlay">'+
															'<div class="error-msg-box">'+
																'<div class="error-msg">'+
																	'<strong>'+
																		'<span class="fa fa-exclamation-circle"></span>'+
																		'<?php _e("It seems that your App token is expired. Please save API details once to generate new token.",PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>'+
																	'</strong>'+
																'</div>'+
															'</div>'+
														'</div>');
							}
							var url = data.trim();
							var redirectWindow = window.open(data);

							if(redirectWindow){
								//Browser has allowed it to be opened
								redirectWindow.focus();
							}else{
								//Broswer has blocked it
								alert('<?php _e("Please allow popups for this site.",PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>');
							}
						}
					},
					error: function(data){
						if(jQuery(".pinterest_rich_pin-expired-token").length<1){
							jQuery("#footer").html('<div class="pinterest_rich_pin-expired-token error-overlay">'+
															'<div class="error-msg-box">'+
																'<div class="error-msg">'+
																	'<strong>'+
																		'<span class="fa fa-exclamation-circle"></span>'+
																		'<?php _e("It seems that your App token is expired. Please save API details once to generate new token.",PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>'+
																	'</strong>'+
																'</div>'+
															'</div>'+
														'</div>');
						}
					}
				});

				// Recall the ajax call
				setTimeout(function() {
					pinterest_rich_pin_api_check();
				}, 60000);
			}

			</script><?php
		}
	}

	/*
	 * This function is to check if api token is valid
	 */
	function pinterest_rich_pin_api_check_callback(){
		$isValid = get_option("pinterest_wcrp_app_is_valid");
		if($isValid){
			echo "y";
		}
		else{

			$appId = get_option("pinterest_wcrp_app_id");
			$appSecKey = get_option("pinterest_wcrp_app_secret_id");

			/*
			 * Calling the object of the pinterest
			 */
			$pinterest = new Vsourz\Pinterest\Pinterest($appId, $appSecKey);
			$redirect_url = admin_url().'admin.php?page=pinterest_rich_pin_settings';
			$pinterest_app_redirect = $pinterest->auth->getLoginUrl($redirect_url, array('read_public', 'write_public'));

			echo $pinterest_app_redirect;

		}

		wp_die();

	}
	
	/*
	 * This function is to display server side validation message
	 */
	function pinterest_rich_pin_error_notice($m){
		global $post;
		if(empty($post)){
			return;
		}
		if(isset($post->post_type) && $post->post_type != "product"){
			return;
		}

		$notice = get_option('pinterest_rich_pin_error_messages');
		
		if (!empty($notice)){
			$class = 'notice error is-dismissible pinterest-error';
			echo '<div id="message" class="'.$class.' pinterest-rich-pin-error">';
			foreach($notice as $pid => $m){
				
				echo $m;
				
				//make sure to remove notice after its displayed so its only displayed when needed.
				unset($notice[$pid]);
				update_option('pinterest_rich_pin_error_messages',$notice);
			}
			echo '</div>';
		}
	}
}
