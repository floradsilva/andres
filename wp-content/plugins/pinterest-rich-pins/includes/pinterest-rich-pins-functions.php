<?php

function add_log_message($message=""){
	// Logging class initialization
	$log = new Logging();

	$log->lfile();

	// write message to the log file
	$log->lwrite($message);

	// close log file
	$log->lclose();
}

/*
 * This function is to empty whole log data from log file
 */
function empty_log_message(){
	
	$return = "";
	
	// Logging class initialization
	$log = new Logging();

	$log->lfile();

	// write message to the log file
	$return = $log->emptyContent();

	// close log file
	$log->lclose();
	
	return $return;
}

function view_log_message($path=""){
	if(empty($path)){
		$upload_dir = wp_upload_dir();
		$path = $upload_dir["basedir"] . '/pinterest_rich_pins/log.txt';
	}
	
	if(file_exists($path)){
		return nl2br(file_get_contents( $path ));
	}
	else{
		return false;
	}
}


/*
 * Check the token if valid
 */

function prp_validate_token()
{
	/*
	 * Setting up the pinterest class to access the endpoints
	 */
	$appId = get_option("pinterest_wcrp_app_id");
	$appSecKey = get_option("pinterest_wcrp_app_secret_id");

	/*
	 * Set the pinterest to active to use the API end points
	 * @var $appId, $appSecKey
	 */
	if(!empty($appId) && !empty($appSecKey))
	{

		$pinterest = new Vsourz\Pinterest\Pinterest($appId, $appSecKey);

	}

	/*
	 * Setting the token to access endpoints
	 * @var $token
	 *
	 */
	$pinterest_token = get_option("pinterest_wcrp_token");
	if(!empty($pinterest_token))
	{
		$pinterest->auth->setOAuthToken($pinterest_token);
	}

	/*
	 * Validate the token is valid or not
	 */
	$token_check = $pinterest->users->me();

	if(!empty( $token_check ) && isset($token_check['code']) && $token_check['success'] == "false")
	{

		return false;
		//$error_message = $token_check['message'];
		//echo $error_message;
		//exit;

	}else{

		return true;

	}
}

/*
 * This function is to call db class functions
 */
function pinterest_rich_pin_call_db_function($func="",$args="",$is_return=false){
	if(class_exists("pinterest_db_class")){
		$obj = new pinterest_db_class();

		if($is_return){
			if(!empty($args)){
				
				$ret = $obj->$func($args);
				return $ret;
			}
			else{
				$ret = $obj->$func();
				return $ret;
			}
		}
		else{
			if(!empty($args)){
				$obj->$func($args);
			}
			else{
				$obj->$func();
			}
		}
	}
}

/*
 * This function is to insert the list entry data
 */
function pinterest_rich_pin_update_data_admin($post_id="",$text=""){
	if(empty($post_id)){
		return false;
	}
	
	if(get_post_meta($post_id,"is_pinterest_product",true) != "yes"){
		return false;
	}
	
	$queue_list_add_flag = true;
	$msg = array();
	
	if($text == "add"){
		$action = PINTEREST_RICH_PINS_ADD_PIN;
	}
	else if($text == "edit"){
		$action = PINTEREST_RICH_PINS_UPDATE_PIN;
	}
	else{
		return false;
	}
	
	$return = true;
	$create_date = date("Y-m-d H:i:s");

	$args = array(
				"action"		=>	$action,
				"status"		=>	"processing",
				"product_id"	=>	$post_id,
				"created_by"	=>	"admin",
				"pinterest_id"	=>	"",
				"create_date"	=>	$create_date
			);
	
	if(has_post_thumbnail($post_id)) {
		$featured_image = get_post_thumbnail_id( $post_id );
		$args["image"] = $featured_image;
		$p_id = get_pinterest_id($post_id,$featured_image);
		
		// Change action if required
		if($text == "edit"){
			$checkFlag = exists_in_attachments($post_id,$featured_image);
			
			if($checkFlag == "y"){
				// Pinterest Id not exists
				if(empty($p_id)){
					$action = PINTEREST_RICH_PINS_ADD_PIN;
				}
			}
			else{
				// Insert attachment data
				$params = array("product_id" => $post_id,"attachment_id" => $desired_image);
				$ret = pinterest_rich_pin_call_db_function("insert_attachment_entry",$params,true);
			}
		}
		
		// Check if image already in queue
		$where = " O.product_id = '".$post_id."' AND O.image_id = '".$featured_image."' AND O.status = 'processing' ";
		$order = " DESC ";
		$orderby = " id ";
		$LIMIT = "";
		$main_results = pinterest_rich_pin_get_data_admin($id='',$where,$order,$orderby,$LIMIT);
		if(!empty($main_results)){
			$return = false;
			
			foreach($main_results as $result){
				$prod_id = $result->product_id;
				$attach_id = $result->image_id;
				$perform_action = $result->action;
				
				if(!empty($prod_id) && !empty($attach_id) && !empty($perform_action)){
					$msg[] = '<div class="pinterest_rich_pin_error_message">
								<span class="image"><img src="'.wp_get_attachment_url($attach_id).'" /></span>
								<span class="message_text">'.__("This image is already exists in Queue List with",PINTEREST_RICH_PINS_TEXT_DOMAIN).' <strong>'.rtrim($perform_action," Item").'</strong> '.__("action.",PINTEREST_RICH_PINS_TEXT_DOMAIN).'</span>
							</div>';
				}
			}
		}
		else{
			// Insert list data
			$queueId = pinterest_rich_pin_call_db_function("insert_queue_list",array( "create_date"	=>	$create_date ),true);
			if($queueId){
				$args["queue_id"] = $queueId;
			}
			
			// Insert list entry data
			$args["pinterest_id"] = $p_id;
			$ret = pinterest_rich_pin_call_db_function("insert_queue_list_entry",$args,true);
			if(!$ret){
				$return = false;
			}
		}
	}
	
	if(!empty($msg)){
		$return = $msg;
	}
	
	return $return;
}

/*
 * This function is to insert the list entry data
 */
function pinterest_rich_pin_product_detail_update_data_admin($post_id="",$imagesArr="",$text="",$is_return=true){
	if(empty($post_id)){
		return false;
	}
	
	if(get_post_meta($post_id,"is_pinterest_product",true) != "yes"){
		return false;
	}
	
	$queue_list_add_flag = true;
	
	if($text == "add"){
		$action = PINTEREST_RICH_PINS_ADD_PIN;
	}
	else if($text == "update"){
		$action = PINTEREST_RICH_PINS_UPDATE_PIN;
	}
	else if($text == "delete"){
		$action = PINTEREST_RICH_PINS_DELETE_PIN;
	}
	else{
		return false;
	}

	$return = true;
	$msg = array();
	$create_date = date("Y-m-d H:i:s");
	
	$args = array(
				"action"		=>	$action,
				"status"		=>	"processing",
				"product_id"	=>	$post_id,
				"created_by"	=>	"admin",
				"pinterest_id"	=>	"",
				"create_date"	=>	$create_date
			);
	
	if(!empty($imagesArr)){
		if($imagesArr == "all"){
			// List out all entries
			$where = " O.product_id = '".$post_id."' ";
			$order = " DESC ";
			$orderby = " id ";
			$LIMIT = "";
			$remove_images = get_from_attachments($id='',$where,$order,$orderby,$LIMIT);
			
			if(!empty($remove_images)){
				foreach($remove_images as $remove_image){
					$desired_images[] = $remove_image->attachment_id;
				}
			}
		}
		else{
			$desired_images = $imagesArr;
		}
	}
	else{
		$desired_images = "";
	}
	
	if(!empty($desired_images)){
		foreach($desired_images as $desired_image){
			$isError = false;
			$args["image"] = $desired_image;
			$p_id = get_pinterest_id($post_id,$desired_image);
			
			// Change action if required
			$checkFlag = exists_in_attachments($post_id,$desired_image);
			
			if($text == "add"){
				// Pinterest Id exists
				if(!empty($p_id)){
					if(!empty($post_id) && !empty($desired_image)){
						$msg[] = '<div class="pinterest_rich_pin_error_message">
									<span class="image"><img src="'.wp_get_attachment_url($desired_image).'" height="25" width="25" /></span>
									<span class="message_text">'.__("This image is already exists in Pinterest.",PINTEREST_RICH_PINS_TEXT_DOMAIN).'</span>
								</div>';
						$isError = true;
						continue;
					}
				}
				// Pinterest Id not exists
				else{
					if($checkFlag == "y"){
						$existIds[] = $desired_image;
					}
					else{
						$existIds[] = $desired_image;
						
						// Insert attachment data
						$params = array("product_id" => $post_id,"attachment_id" => $desired_image);
						$ret = pinterest_rich_pin_call_db_function("insert_attachment_entry",$params,true);
					}
				}
			}
			else if($text == "update"){
				// Pinterest Id exists
				if(empty($p_id)){
					if(!empty($post_id) && !empty($desired_image)){
						$msg[] = '<div class="pinterest_rich_pin_error_message">
									<span class="image"><img src="'.wp_get_attachment_url($desired_image).'" height="25" width="25" /></span>
									<span class="message_text">'.__("This image is not exists in Pinterest.",PINTEREST_RICH_PINS_TEXT_DOMAIN).'</span>
								</div>';
						$isError = true;
						continue;
					}
				}
				// Pinterest Id not exists
				else{
					if($checkFlag != "y"){
						if(!empty($post_id) && !empty($desired_image)){
							$msg[] = '<div class="pinterest_rich_pin_error_message">
										<span class="image"><img src="'.wp_get_attachment_url($desired_image).'" height="25" width="25" /></span>
										<span class="message_text">'.__("This image is not added in Pinterest.",PINTEREST_RICH_PINS_TEXT_DOMAIN).'</span>
									</div>';
							$isError = true;
							continue;
						}
					}
				}
			}
			else if($text == "delete"){
				// Pinterest Id exists
				if(empty($p_id)){
					if(!empty($post_id) && !empty($desired_image)){
						$msg[] = '<div class="pinterest_rich_pin_error_message">
									<span class="image"><img src="'.wp_get_attachment_url($desired_image).'" height="25" width="25" /></span>
									<span class="message_text">'.__("This image is not exists in Pinterest.",PINTEREST_RICH_PINS_TEXT_DOMAIN).'</span>
								</div>';
						$isError = true;
						continue;
						
					}
				}
				// Pinterest Id not exists
				else{
					if($checkFlag != "y"){
						if(!empty($post_id) && !empty($desired_image)){
							$msg1[] = '<div class="pinterest_rich_pin_error_message">
										<span class="image"><img src="'.wp_get_attachment_url($desired_image).'" height="25" width="25" /></span>
										<span class="message_text">'.__("This image is not added in Pinterest.",PINTEREST_RICH_PINS_TEXT_DOMAIN).'</span>
									</div>';
							$isError = true;
							continue;
						}
					}
				}
			}
			else{
				continue;
			}
			
			if(!$isError){
			
				// Check if image already in queue
				$where = " O.product_id = '".$post_id."' AND O.image_id = '".$desired_image."' AND O.status = 'processing' ";
				$order = " DESC ";
				$orderby = " id ";
				$LIMIT = "";
				$main_results = pinterest_rich_pin_get_data_admin($id='',$where,$order,$orderby,$LIMIT);
				
				if(empty($main_results)){
					if($queue_list_add_flag){
						// Insert list data
						$queueId = pinterest_rich_pin_call_db_function("insert_queue_list",array( "create_date"	=>	$create_date ),true);
						if($queueId){
							$args["queue_id"] = $queueId;
						}
						$queue_list_add_flag = false;
					}
				
					$args["pinterest_id"] = $p_id;
					// Insert list entry data
					$ret = pinterest_rich_pin_call_db_function("insert_queue_list_entry",$args,true);
					
					if($text == "delete"){
						if(!$ret){
							$params = array("product_id" => $product_id,"attachment_id" => $desired_image);
							$ret = pinterest_rich_pin_call_db_function("remove_attachment_entry",$params,true);
							
							if(!$ret){
								$return = false;
							}
						}
					}
				}
				else{
					foreach($main_results as $result){
						$prod_id = $result->product_id;
						$attach_id = $result->image_id;
						$perform_action = $result->action;
						
						if(!empty($prod_id) && !empty($attach_id) && !empty($perform_action)){
							$msg[] = '<div class="pinterest_rich_pin_error_message">
										<span class="image"><img src="'.wp_get_attachment_url($attach_id).'" /></span>
										<span class="message_text">'.__('This image is already exists in Queue List with',PINTEREST_RICH_PINS_TEXT_DOMAIN).'<strong>'.rtrim($perform_action," Item").'</strong> '.__('action',PINTEREST_RICH_PINS_TEXT_DOMAIN).'</span>
									</div>';
							continue;
						}
					}
				}
			}
		}// End foreach
		
		if($is_return && !empty($msg)){
			update_option('pinterest_rich_pin_error_messages',$msg);
		}
	}// End if
	
	/*
	else{
		// List out all entries
		$where = " O.product_id = '".$post_id."' ";
		$order = " DESC ";
		$orderby = " id ";
		$LIMIT = "";
		$remove_images = get_from_attachments($id='',$where,$order,$orderby,$LIMIT);
		
		if(!empty($remove_images)){
			foreach($remove_images as $remove_image){
				$remove_image_id = $remove_image->attachment_id;
				
				// Remove attachment data
				$params = array("product_id" => $post_id,"attachment_id" => $remove_image_id);
				$ret = pinterest_rich_pin_call_db_function("remove_attachment_entry",$params,true);
				
				$remove_args = $args;
				$action = PINTEREST_RICH_PINS_DELETE_PIN;
				$remove_args["action"] = $action;
				$remove_args["image"] = $remove_image_id;
				$remove_args["product_id"] = $post_id;
				
				// Check if image already in queue
				$where = " O.product_id = '".$post_id."' AND O.image_id = '".$remove_image_id."' AND O.action = '".$action."' AND O.status = 'processing' ";
				$order = " DESC ";
				$orderby = " id ";
				$LIMIT = "";
				$main_results = pinterest_rich_pin_get_data_admin($id='',$where,$order,$orderby,$LIMIT);
				
				if(empty($main_results)){
					
					if($queue_list_add_flag){
						// Insert list data
						$queueId = pinterest_rich_pin_call_db_function("insert_queue_list",array( "create_date"	=>	$create_date ),true);
						if($queueId){
							$remove_args["queue_id"] = $queueId;
							$args["queue_id"] = $queueId;
						}
						$queue_list_add_flag = false;
					}
					
					// Insert list entry data
					$ret = pinterest_rich_pin_call_db_function("insert_queue_list_entry",$remove_args,true,true);
					
					if(!$ret){
						$return = false;
					}
				}
				else{
					$return = true;
				}
			}
		}
	}
	
	if(!empty($existIds)){
		$notInAddIds = implode("','",$existIds);
		$where = " O.product_id = '".$post_id."' AND O.attachment_id NOT IN ( '".$notInAddIds."' ) ";
		$order = " DESC ";
		$orderby = " id ";
		$LIMIT = "";
		$added_images = get_from_attachments($id='',$where,$order,$orderby,$LIMIT);
		
		if(!empty($added_images)){
			foreach ($added_images as $pint_attach){
				$delete_ids[] = $pint_attach->attachment_id;
			}
		}
	}
	
	if(isset($delete_ids) && !empty($delete_ids)){
		foreach($delete_ids as $desired_image){
			$action = PINTEREST_RICH_PINS_DELETE_PIN;
			$args["action"] = $action;
			$args["image"] = $desired_image;
			
			// Check if image already in queue
			$where = " O.product_id = '".$post_id."' AND O.image_id = '".$desired_image."' AND O.action = '".$action."' AND O.status = 'processing' ";
			$order = " DESC ";
			$orderby = " id ";
			$LIMIT = "";
			$main_results = pinterest_rich_pin_get_data_admin($id='',$where,$order,$orderby,$LIMIT);
			
			// Remove attachment data
			$params = array("product_id" => $post_id,"attachment_id" => $desired_image);
			$ret = pinterest_rich_pin_call_db_function("remove_attachment_entry",$params,true);
			
			if(empty($main_results)){
				
				if($queue_list_add_flag){
					// Insert list data
					$queueId = pinterest_rich_pin_call_db_function("insert_queue_list",array( "create_date"	=>	$create_date ),true);
					if($queueId){
						$args["queue_id"] = $queueId;
					}
					$queue_list_add_flag = false;
				}
				
				// Insert list entry data
				$ret = pinterest_rich_pin_call_db_function("insert_queue_list_entry",$args,true);
				if(!$ret){
					$return = false;
				}
			}
			else{
				$return = true;
			}
		}
	}
	*/
	return $return;
}

/*
 * This function is to check if product id and images id exists in attachment table
 */
function exists_in_attachments($product_id="",$attachment_id=""){
	
	if(empty($product_id)){
		return false;
	}
	
	if(empty($attachment_id)){
		return false;
	}
	
	// Check if image already in queue
	$where = " O.product_id = '".$product_id."' AND O.attachment_id = '".$attachment_id."' ";
	$order = " DESC ";
	$orderby = " id ";
	
	$args = array(
				"where_append"	=>	$where,
				"order"			=>	$order,
				"orderby"		=>	$orderby,
				"LIMIT"			=>	" LIMIT 1"
			);
	
	$main_results = pinterest_rich_pin_call_db_function("get_attachment_entry",$args,true);
	
	if(!empty($main_results)){
		return "y";
	}
	else{
		return "n";
	}
}

/*
 * This function is to check if product id and images id exists in attachment table
 */
function add_pinterest_id($product_id="",$attachment_id="", $pinterest_id=""){
	
	if(empty($product_id)){
		return false;
	}
	
	if(empty($attachment_id)){
		return false;
	}
	
	if(empty($pinterest_id)){
		return false;
	}
	
	// Check if image already in queue
	$where = " O.product_id = '".$product_id."' AND O.attachment_id = '".$attachment_id."' ";
	$order = " DESC ";
	$orderby = " id ";
	
	$args = array(
				"where"		=>	$where,
				"fields"	=>	array("pinterest_id" => $pinterest_id) 
			);
	
	$main_results = pinterest_rich_pin_call_db_function("update_attachment_entry",$args,true);
	
	if(!empty($main_results)){
		return "y";
	}
	else{
		return "n";
	}
}

/*
 * This function is to check if product id and images id exists in attachment table
 */
function get_pinterest_id($product_id="",$attachment_id=""){
	
	if(empty($product_id)){
		return false;
	}
	
	if(empty($attachment_id)){
		return false;
	}
	
	// Check if image already in queue
	$where = " O.product_id = '".$product_id."' AND O.attachment_id = '".$attachment_id."' ";
	$order = " DESC ";
	$orderby = " id ";
	
	$args = array(
				"where_append"		=>	$where,
				"fields"			=>	array("pinterest_id")
			);
	
	$main_results = pinterest_rich_pin_call_db_function("get_attachment_entry_field",$args,true);
	
	if(!empty($main_results)){
		return $main_results[0]->pinterest_id;
	}
	else{
		return "";
	}
}

/*
 * This function is to check if product id and images id exists in attachment table
 */
function get_from_attachments($id='',$where="",$order="",$orderby="",$LIMIT=""){
	
	$args = array(
				"id"			=>	$id,
				"where_append"	=>	$where,
				"order"			=>	$order,
				"orderby"		=>	$orderby,
				"LIMIT"			=>	$LIMIT
			);

	$res = pinterest_rich_pin_call_db_function("get_attachment_entry",$args,true);
	return $res;
}

/*
 * Based on the search the data will be populated for the listing screen
 */
function pinterest_rich_pin_get_data_admin($id = "", $where_append = '', $order = '',$orderby = '',$LIMIT = ''){
	$args = array(
				"id"			=>	$id,
				"where_append"	=>	$where_append,
				"order"			=>	$order,
				"orderby"		=>	$orderby,
				"LIMIT"			=>	$LIMIT
			);
	
	$res = pinterest_rich_pin_call_db_function("get_data_queue_list_entry",$args,true);
	return $res;
}

/*
 * This function will remove particular action from queue
 */
function remove_action_from_queue($id = ""){
	// Return if id is not provided
	if(empty($id)){
		return false;
	}

	if(is_array($id)){
		global $wpdb;
		$table = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
		$ids = implode( ",", array_map( 'absint', $id ) );
		$qry = "DELETE FROM ".$table." WHERE id IN(".$ids.")";
		$res = $wpdb->query( $qry );
	}
	else{
		$args = array( "id" => $id );
		$res = pinterest_rich_pin_call_db_function("remove_data_queue_list_entry",$args,true);
	}
	return $res;
}

/*
 * This function will remove particular action from queue
 */
function retry_action_from_queue($id = ""){
	// Return if id is not provided
	if(empty($id)){
		return false;
	}

	if(is_array($id)){
		global $wpdb;
		
		// Update queue entry status
		$table = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
		$fields = array(
					"status"		=>	"processing",
					"created_by"	=>	"admin"
				);
		$where = array("id" => $id);
		$res = $wpdb->update($table,$fields,$where);
	}
	else{
		$args = array( "id" => $id );
		$res = pinterest_rich_pin_call_db_function("remove_data_queue_list_entry",$args,true);
	}
	return $res;
}

/*
 * This function will remove particular action from queue
 */
function add_action_in_queue($id = ""){
	// Return if id is not provided
	if(empty($id)){
		return false;
	}

	$where = " O.product_id = '".$id."' ";
	$order = " DESC ";
	$orderby = " id ";
	$queue_items = pinterest_rich_pin_get_data_admin($id,$where,$order,$orderby);

	if(!empty($queue_items)){
		$create_date = date("Y-m-d H:i:s");
		$time_gap = (int) get_option("pinterest_wcrp_time_gap");
		$numRequests = (int) pinterest_rich_pin_total_by_status("processing");
		$plusTime = 60*$time_gap*$numRequests; // in term of seconds
		$execute_date = date("Y-m-d H:i:s",strtotime($create_date)+$plusTime);
		
		$queue_item = $queue_items[0];
		$image_id = $queue_item->image_id;
		$action = "processing";
		
		$where = " O.product_id = '".$id."' AND O.image_id = '".$image_id."' AND O.action = '".$action."' ";
		$order = " DESC ";
		$orderby = " id ";
		$main_results = pinterest_rich_pin_get_data_admin('',$where,$order,$orderby,$LIMIT);
		
		if(empty($main_results)){
			global $wpdb;
			$table = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
			$fields = array(
						"status"		=>	"processing",
						"created_by"	=>	"admin",
						"description"	=>	"Retry attempted.",
						"execute_date"	=>	$execute_date
					);
			$where = array("id" => $id);
			$res = $wpdb->update($table,$fields,$where);
		}
		else{
			$res = true;
		}
		
		return $res;
	}
	else{
		return false;
	}
}

/*
 * Based on the search the total count data will be populated for the listing screen
 */
function pinterest_rich_pin_get_data_total_admin($id = "", $where_append = '', $order = '',$orderby = '',$LIMIT = ''){

	$args = array(
				"id"			=>	$id,
				"where_append"	=>	$where_append,
				"order"			=>	$order,
				"orderby"		=>	$orderby,
				"LIMIT"			=>	$LIMIT
			);
	$res = pinterest_rich_pin_call_db_function("get_data_queue_list_entry_total",$args,true);
	return $res;
}

/*
 * This function is to get total count by status
 */
function pinterest_rich_pin_total_by_status($status=""){

	switch ($status) {

		    case "processing":

		    	$orderby = 'id';
		    	$where_append = ' 1=1 AND status="processing" ';
		    	$data =	pinterest_rich_pin_get_data_total_admin($id = "", $where_append, $order = '',$orderby,$LIMIT = '');
		        return $data;
		        break;

		    case "failed":
		    	$orderby = 'id';
		    	$where_append = ' 1=1 AND status="failed"';
		    	$data =	pinterest_rich_pin_get_data_total_admin($id = "", $where_append, $order = '',$orderby,$LIMIT = '');
		        return $data;
		        break;

			default:
				$orderby = 'id';
		    	$where_append = ' 1=1 ';
		    	$data =	pinterest_rich_pin_get_data_total_admin($id = "", $where_append, $order = '',$orderby,$LIMIT = '');
		        return $data;
		        break;
		}
}

/*
 * Custom pagination for the quote list
 */
function pinterest_rich_pin_queue_pagination_admin($totalposts,$p,$lpm1,$prev,$next,$pagename,$adjacents,$orderby,$order,$where_append){
	$pagination = '';
	if($totalposts > 1)
	{
		$pagination .= "<div class='custom_pagination text-right' style='display:inline-block;'>";
		//previous button
		if ($p > 1)
		$pagination.= "<a href=\"?page=".$pagename."&pg=".$prev."&".$where_append."\"><< </a> ";
		else
		$pagination.= "<span class=\"disabled\"><< </span> ";
		if ($totalposts < 7 + ($adjacents * 2)){
			for ($counter = 1; $counter <= $totalposts; $counter++){
				if ($counter == $p)
				$pagination.= "<span class=\"current\">$counter</span>";
				else
				$pagination.= " <a href=\"?page=".$pagename."&pg=".$counter."&".$where_append."\">$counter</a> ";}
		}elseif($totalposts > 5 + ($adjacents * 2)){
			if($p < 1 + ($adjacents * 2)){
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
					if ($counter == $p)
					$pagination.= " <span class=\"current\">$counter</span> ";
					else
					$pagination.= " <a href=\"?page=".$pagename."&pg=".$counter."&".$where_append."\">$counter</a> ";
				}
				$pagination.= " ... ";
				$pagination.= " <a href=\"?page=".$pagename."&pg=".$lpm1."&".$where_append."\">$lpm1</a> ";
				$pagination.= " <a href=\"?page=".$pagename."&pg=".$totalposts."&".$where_append."\">$totalposts</a> ";
			}
			//in middle; hide some front and some back
			elseif($totalposts - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
				$pagination.= " <a href=\"?page=".$pagename."&pg=1&".$where_append."\">1</a> ";
				$pagination.= " <a href=\"?page=".$pagename."&pg=2&".$where_append."\">2</a> ";
				$pagination.= " ... ";
				for ($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
					if ($counter == $p)
					$pagination.= " <span class=\"current\">$counter</span> ";
					else
					$pagination.= " <a href=\"?page=".$pagename."&pg=".$counter."&".$where_append."\">$counter</a> ";
				}
				$pagination.= " ... ";
				$pagination.= " <a href=\"?page=".$pagename."&pg=".$lpm1."&".$where_append."\">$lpm1</a> ";
				$pagination.= " <a href=\"?page=".$pagename."&pg=".$totalposts."&".$where_append."\">$totalposts</a> ";
			}else{
				$pagination.= " <a href=\"?page=".$pagename."&pg=1&".$where_append."\">1</a> ";
				$pagination.= " <a href=\"?page=".$pagename."&pg=2&".$where_append."\">2</a> ";
				$pagination.= " ... ";
				for ($counter = $totalposts - (2 + ($adjacents * 2)); $counter <= $totalposts; $counter++){
					if ($counter == $p)
					$pagination.= " <span class=\"current\">$counter</span> ";
					else
					$pagination.= " <a href=\"?page=".$pagename."&pg=".$counter."&".$where_append."\">$counter</a> ";
				}
			}
		}
		if ($p < $counter - 1)
		$pagination.= " <a href=\"?page=".$pagename."&pg=".$next."&".$where_append."\"> >></a>";
		else
		$pagination.= " <span class=\"disabled\"> >></span>";
		$pagination.= "\n";
		$pagination .= "</div>";
	}

	return $pagination;
}

/*
 * This function will used to change the dat format
 * @ param :	$date : current date
				$format : current date format
				$requiredFormat : required date format
   @ return : 	Required formatted date on success
				False if failed
 */
function pinterest_rich_pin_change_date_format_admin($date,$format='d/m/Y',$requireFormat='Y-m-d'){
	$date = trim($date);
	$format = trim($format);
	$requireFormat = trim($requireFormat);

	$obj_date = date_create_from_format($format,$date);
	//return $obj_date;
	if(!empty($obj_date)){
		//Get to date information
		$date =  date_format($obj_date,$requireFormat);
	}
	else{
		$date = '';
	}
	return $date;
}

/*
 * This function is to remove product from pinterest ajax callback
 */
function remove_product_from_pinterest_pin($post_id=""){
	if(empty($post_id)){
		if(isset($_POST["id"]) && !empty($_POST["id"])){
			$post_id = $_POST["id"];
		}
		else{
			return false;
		}
	}

	update_post_meta($post_id,"is_pinterest_product","no");
}

/*
 * This function is to remove product from pinterest ajax callback
 */
function add_product_to_pinterest_pin($post_id=""){
	if(empty($post_id)){
		if(isset($_POST["id"]) && !empty($_POST["id"])){
			$post_id = $_POST["id"];
			//Sending the product data to pinterest object

			$params = array(
					"board"			=>	$board,
					"image_url"		=>	$image_url,
					"note"			=>	$note,
					"created_at"	=>	$created_at,
					"link"			=>	$original_link
				);
		}
		else{
			return false;
		}
	}
}

/*
 * This function is to get pinterest class object
 */
function get_pinterest_object(){
	/*
	 * Checking if the token is been received or not and valid
	 */

	$appId = get_option("pinterest_wcrp_app_id");
	$appSecKey = get_option("pinterest_wcrp_app_secret_id");

	/*
	 * Getting the object
	 */
	if(!empty($appId) && !empty($appSecKey)){

		return new Vsourz\Pinterest\Pinterest($appId, $appSecKey);

	}else{

		return null;

	}
}


/*
 * Submit pin to pinterest
 */
function rpvz_save_to_pinterest($params){
	if(empty($params)){

		return array(
	                    'success' => 'false' ,
	                    'code' => '400',
	                    'message' => __('Invalid data sent. No data found.',PINTEREST_RICH_PINS_TEXT_DOMAIN),
	                    'data' => ''
	                );
	}

	if(isset($params['image_url']) && empty($params['image_url'])){
		return array(
	                    'success' => 'false' ,
	                    'code' => '400',
	                    'message' => __('Image field is mandatory.',PINTEREST_RICH_PINS_TEXT_DOMAIN),
	                    'data' => ''
	                );
	}
}

/*
 * This function is used to check if a plugin is active
 */
function is_active( $plugin ) {
    $network_active = false;
    if ( is_multisite() ) {
        $plugins = get_site_option( 'active_sitewide_plugins' );
        if ( isset( $plugins[$plugin] ) ) {
            $network_active = true;
        }
    }
    return in_array( $plugin, get_option( 'active_plugins' ) ) || $network_active;
}