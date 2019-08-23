<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!class_exists('pinterest_db_class')){
	class pinterest_db_class{
		
		/*
		 * This function is to insert new entry in rich pin queue list entry table.
		 */
		function insert_queue_list_entry($args=""){
			global $wpdb;
			$fields = '';
			$values = '';
			
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Action
			if(isset($action) && !empty($action)){
				$fields .= ' `action`';
				$values .= " '".$action."'";
			}
			else{
				return false;
			}
			
			// Status
			if(isset($status) && !empty($status)){
				$fields .= ', `status`';
				$values .= ", '".$status."'";
			}
			else{
				return false;
			}
			
			// Product Id
			if(isset($product_id) && !empty($product_id)){
				$fields .= ', `product_id`';
				$values .= ", '".$product_id."'";
			}
			else{
				return false;
			}
			
			// Image Id
			if(isset($image) && !empty($image)){
				$fields .= ', `image_id`';
				$values .= ", '".$image."'";
			}
			else{
				return false;
			}
			
			// Created By
			if(isset($created_by) && !empty($created_by)){
				$fields .= ', `created_by`';
				$values .= ", '".$created_by."'";
			}
			else{
				return false;
			}
			
			// Pinterest ID
			if(isset($pinterest_id) && !empty($pinterest_id)){
				$fields .= ', `pinterest_id`';
				$values .= ", '".$pinterest_id."'";
			}
			
			// Create Date
			if(isset($create_date) && !empty($create_date)){
				$fields .= ', `create_date`';
				$values .= ", '".$create_date."'";
			}
			else{
				return false;
			}
			
			// Execute Date
			if(isset($execute_date) && !empty($execute_date)){
				$fields .= ', `execute_date`';
				$values .= ", '".$execute_date."'";
			}
			
			// Queue Id
			if(isset($queue_id) && !empty($queue_id)){
				$fields .= ', `queue_id`';
				$values .= ", '".$queue_id."'";
			}
			else{
				return false;
			}
			
			
			$qry = 'INSERT INTO '.PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME.' ('.$fields.') VALUES ('.$values.')';
			$res = $wpdb->get_results($qry);
			return $wpdb->insert_id;
		}
		
		/*
		 * This function is to get entry from rich pin queue list table.
		 * @ parameters : $id = "", $where_append = '', $order = '',$orderby = '',$LIMIT = ''
		 */
		function get_data_queue_list_entry($args=""){
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// pinterest_rich_pin_get_data_admin
			$_and_query = '';
			if(!empty($id)){
				$_and_query .= "O.id='".$id."' AND ";
			}
			if(!empty($where_append)){
				$_and_query .= $where_append;
			}

			global $wpdb;
			$qry = "SELECT * FROM ".PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME." O
					WHERE ".$_and_query." ORDER BY O.".$orderby." ".$order." ".$LIMIT;
			
			$res = $wpdb->get_results( $qry );
			
			return $res;
		}
		
		/*
		 * This function is to remove entry from rich pin queue list table.
		 * @ parameters : $id = ""
		 */
		function remove_data_queue_list_entry($args=""){
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			global $wpdb;
			$table = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
			$where = array( "id" => $id );
			$res = $wpdb->delete($table,$where);
			
			return $res;
		}
		
		/*
		 * This function is to get total entry from rich pin queue list entry table.
		 * @ Parameters : $id = "", $where_append = '', $order = '',$orderby = '',$LIMIT = ''
		 */
		function get_data_queue_list_entry_total($args=""){
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// pinterest_rich_pin_get_data_total_admin
			$_and_query = '';
			if(!empty($id)){
				$_and_query .= "id='".$id."' AND ";
			}
			if(!empty($where_append)){
				$_and_query .= $where_append;
			}
			global $wpdb;
			$qry = "SELECT count( O.id )  FROM ".PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME." O
					WHERE ".$_and_query." ORDER BY O.".$orderby." ".$order." ".$LIMIT;
			$res = $wpdb->get_var( $qry );
						
			return $res;
		}
		
		/*
		 * This function is to insert new entry in rich pin queue list table.
		 */
		function insert_queue_list($args=""){
			global $wpdb;
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Create Date
			if(!isset($create_date) || empty($create_date)){
				return false;
			}
			
			$qry = 'INSERT INTO '.PINTEREST_RICH_PINS_QUEUE_LIST_TABLE_NAME.' (`create_date`) VALUES ('.strtotime($create_date).')';
			$res = $wpdb->get_results($qry);
			
			return $wpdb->insert_id;
		}
		
		/*
		 * This function is to get entries in rich pin attachment table.
		 */
		function get_attachment_entry($args=""){
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// pinterest_rich_pin_get_data_admin
			$_and_query = '';
			if(!empty($id)){
				$_and_query .= "O.id='".$id."' AND ";
			}
			if(!empty($where_append)){
				$_and_query .= $where_append;
			}

			global $wpdb;
			$qry = "SELECT * FROM ".PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME." O
					WHERE ".$_and_query." ORDER BY O.".$orderby." ".$order." ".$LIMIT;
			
			$res = $wpdb->get_results( $qry );
			
			return $res;
		}
		
		/*
		 * This function is to insert new entry in rich pin attachment table.
		 * New entry will insert product id and attachment id only
		 * Pinterest id will be updated later
		 */
		function insert_attachment_entry($args=""){
			global $wpdb;
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Product ID
			if(!isset($product_id) || empty($product_id)){
				return false;
			}
			
			// Attachment ID
			if(!isset($attachment_id) || empty($attachment_id)){
				return false;
			}
			
			$qry = 'INSERT INTO '.PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME.' (`product_id`,`attachment_id`) VALUES ('.$product_id.','.$attachment_id.')';
			$res = $wpdb->get_results($qry);
			
			return $wpdb->insert_id;
		}
		
		/*
		 * This function is to update entry in rich pin attachment table.
		 */
		function update_attachment_entry($args=""){
			global $wpdb;
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Product ID
			if(!isset($product_id) || empty($product_id)){
				return false;
			}
			
			// Attachment ID
			if(!isset($attachment_id) || empty($attachment_id)){
				return false;
			}
			
			// Update attachment entry
			global $wpdb;
			$table = PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME;
			$where = array(
						"attachment_id"	=>	$attachment_id,
						"product_id"	=>	$product_id
					);
			$fields = array("pinterest_id" => $pinterest_id);
			$ret = $wpdb->update($table,$fields,$where);
			
			return $ret;
		}
		
		/*
		 * This function is to get entries in rich pin attachment table.
		 */
		function get_attachment_entry_field($args=""){
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// pinterest_rich_pin_get_data_admin
			$_and_query = '';
			
			if(!empty($where_append)){
				$_and_query .= $where_append;
			}
			
			if(count($fields)>1){
				$field = implode(",",$fields);
			}
			else if(count($fields) == 1){
				$field = $fields[0];
			}
			else{
				$field = "*";
			}
			
			global $wpdb;
			$qry = "SELECT ".$field." FROM ".PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME." O
					WHERE ".$_and_query;
			
			$res = $wpdb->get_results( $qry );
			
			return $res;
		}
		
		/*
		 * This function is to update entry in rich pin attachment table.
		 */
		function remove_attachment_entry($args=""){
			global $wpdb;
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Product ID
			if(!isset($product_id) || empty($product_id)){
				return false;
			}
			
			// Attachment ID
			if(!isset($attachment_id) || empty($attachment_id)){
				return false;
			}
			
			// Update attachment entry
			global $wpdb;
			$table = PINTEREST_RICH_PINS_ATTACHMENTS_TABLE_NAME;
			$where = array(
						"attachment_id"	=>	$attachment_id,
						"product_id"	=>	$product_id
					);
			$ret = $wpdb->delete($table,$where);
			
			return $ret;
		}
		
		/*
		 * This function is to create pin
		 */
		function pinterest_create_pin(array $args){
			// Check if args is empty
			if(empty($args)){
				return false;
			}
			
			// Extract variables
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Check for pinterest object
			if(!isset($pinterest) || empty($pinterest)){
				return false;
			}
			
			// Check for parameters
			if(!isset($params) || empty($params)){
				return false;
			}
			
			$return = "";
			
			// Add the pin to selected board
			$pin_added = $pinterest->pins->create($params);
			
			// Add log
			$manage_log = get_option("pinterest_wcrp_manage_log");
			if($manage_log){
				add_log_message(" Add Pin ::  Request\n".json_encode($params)."\n\n Add Pin ::  Response\n".json_encode($pin_added));
			}
			
			$records = array();
			if( !empty( $pin_added ) && isset($pin_added['code']) && $pin_added['code'] == "200" ){
				
				$addedPinArr = $pin_added['data']->toArray();
				
				if(isset($addedPinArr["id"])){
					$pinterestId = $addedPinArr["id"];
					$this->update_attachment_entry(array( "product_id" => $product_id, "attachment_id" => $image_id, "pinterest_id" => $pinterestId));
				}
				
				// Delete queue entry
				$ret = $this->remove_data_queue_list_entry(array("id" => $queue_id));
				
				$return = "y";
			}
			else{
				
				// Update queue entry status
				global $wpdb;
				$table = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
				$fields = array(
							"status"		=>	"failed",
							"created_by"	=>	"admin"
						);
				$where = array("id" => $queue_id);
				$ret = $wpdb->update($table,$fields,$where);
				
				$return = "n";
			}
			
			return $return;
		}
		
		/*
		 * This function is to update pin
		 */
		function pinterest_update_pin(array $args){
			// Check if args is empty
			if(empty($args)){
				return false;
			}
			
			// Extract variables
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Check for pinterest id
			if(!isset($pinterest_id) || empty($pinterest_id)){
				return false;
			}
			
			// Check for pinterest object
			if(!isset($pinterest) || empty($pinterest)){
				return false;
			}
			
			// Check for parameters
			if(!isset($params) || empty($params)){
				return false;
			}
			
			$pin_updated = $pinterest->pins->edit($pinterest_id, $params);
			$pin_updated = $pin_updated->toArray();
			
			// Add log
			$manage_log = get_option("pinterest_wcrp_manage_log");
			if($manage_log){
				$logArr = array( "pinterest_id" => $pinterest_id, "params" => $params );
				add_log_message(" Update Pin ::  Request\n".json_encode($logArr)."\n\n Update Pin ::  Response\n".json_encode($pin_updated));
			}
			
			if( !empty( $pin_updated ) && isset($pin_updated['id']) && $pin_updated['id'] == $pinterest_id ){
				
				// Delete queue entry
				$ret = $this->remove_data_queue_list_entry(array("id" => $queue_id));
				
				$return = "y";
			}
			else{
				
				// Update queue entry status
				global $wpdb;
				$table = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
				$fields = array(
							"status"		=>	"failed",
							"created_by"	=>	"admin"
						);
				$where = array("id" => $queue_id);
				$ret = $wpdb->update($table,$fields,$where);
				
				$return = "n";
			}
		}
		
		/*
		 * This function is to delete pin
		 */
		function pinterest_delete_pin(array $args){
			// Check if args is empty
			if(empty($args)){
				return false;
			}
			
			// Extract variables
			if(!empty($args) && is_array($args)){
				extract($args);
			}
			
			// Check for pinterest id
			if(!isset($pinterest_id) || empty($pinterest_id)){
				return false;
			}
			
			// Check for pinterest object
			if(!isset($pinterest) || empty($pinterest)){
				return false;
			}
			
			$pin_deleted = $pinterest->pins->delete($pinterest_id);
			
			// Add log
			$manage_log = get_option("pinterest_wcrp_manage_log");
			if($manage_log){
				$logArr = array( "pinterest_id" => $pinterest_id  );
				add_log_message(" Delete Pin ::  Request\n".json_encode($logArr)."\n\n Delete Pin ::  Response\n".json_encode($pin_deleted));
			}
			
			if( $pin_deleted ){
				
				// Delete queue entry
				$ret = $this->remove_data_queue_list_entry(array("id" => $queue_id));
				delete_post_meta($product_id,"pinterest_id");
				
				$return = "y";
			}
			else{
				
				// Update queue entry status
				global $wpdb;
				$table = PINTEREST_RICH_PINS_QUEUE_ENTRY_TABLE_NAME;
				$fields = array(
							"status"		=>	"failed",
							"created_by"	=>	"admin"
						);
				$where = array("id" => $queue_id);
				$ret = $wpdb->update($table,$fields,$where);
				
				$return = "n";
			}
		}
	}
}