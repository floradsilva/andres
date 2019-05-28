<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//wp_enqueue_style( "pinterest_rich_pins_bootstrap_min_css");
//wp_enqueue_script( "pinterest_rich_pins_bootstrap_min_js");
//wp_enqueue_style( 'pinterest_rich_pins_bootstrap-datepicker-min-css' );
//wp_enqueue_script('pinterest_rich_pins_bootstrap-datepicker-min-js');
// wp_enqueue_script('pinterest_rich_pin_admin_js');

// global $pinterest_rich_pin_DEFAULT_STATUSES;

$where_append = '';
$where_append_order = '';
$search_term = '';
$fromDate='';
$toDate='';
$form_id = '';
$is_ppc = '';
$note = '';
$status = '';
$queue_status = '';
$page = 'pinterest_rich_pin';

// Set posts per page
if(isset($_GET['ppp']) && !empty($_GET['ppp'])){
	$max = $_GET['ppp'];
	$where_append .= "&ppp=".$_GET['ppp'];
	$where_append_order .= "&ppp=".$_GET['ppp'];
}
else{
	$max = 10;
	$where_append .= "&ppp=10";
	$where_append_order .= "&ppp=10";
}

/*Get the current page eg index.php?pg=4*/
if(isset($_GET['pg'])){
	$p = $_GET['pg'];
}else{
	$p = 1;
}

$limit = ($p - 1) * $max;
$prev = $p - 1;
$next = $p + 1;
$limits = (int)($p - 1) * $max;

// SET limit as per page no
$LIMIT = " LIMIT $limits,$max";

if(isset($_REQUEST['queue_status']) && $_REQUEST['queue_status'] == "trash"){
	$where = ' 1=1 AND O.endeffdt IS NOT NULL ';
}else{
	$where = ' 1=1 ';
}
// SET queue by parameters
if(isset($_GET['orderby']) && !empty($_GET['orderby'])){
	$orderby = $_GET['orderby'];
	$where_append .= "&orderby=".$_GET['orderby'];
}
else{
	$orderby = 'id';
	$where_append .= "&orderby=id";
}

// SET queue by parameters
if(isset($_GET['order']) && !empty($_GET['order'])){
	$order = $_GET['order'];
	$where_append .= "&order=".$_GET['order'];
}
else{
	$order = 'DESC';
	$where_append .= "&order=DESC";
}

if(isset($_GET['queue_status']) && !empty($_GET['queue_status'])){
	$where .= " AND ( O.status = '".$_GET['queue_status']."' ) ";
	$where_append .= "&queue_status=".$_GET['queue_status'];
	$where_append_order .= "&queue_status=".$_GET['queue_status'];
}

//Exporting the list starts from here
if(isset($_REQUEST['export_quote_list']) && $_REQUEST['export_quote_list'] == "Export"){
	//echo "hello";exit;
	//require_once(plugin_dir_path( __FILE__ ) . 'export_quote_list.php');

}

// Change ASC and DESC
if($order == "DESC"){
	$order_changed = "ASC";
}
else{
	$order_changed = "DESC";
}

$main_results = pinterest_rich_pin_get_data_admin($id='',$where,$order,$orderby,$LIMIT);
$total_results = pinterest_rich_pin_get_data_total_admin($id='',$where,$order,$orderby);
//divide it with the max value & round it up
$totalposts = ceil($total_results / $max);
$lpm1 = $totalposts - 1;

/*if( $totalposts < $p && empty($main_results) ){

	wp_redirect(menu_page_url('woo-quote-queues',false));
	exit;
}*/


/////// Including datepicker
//wp_enqueue_script( 'bootstrap-datepicker-min-js' );
//wp_enqueue_style( 'bootstrap-datepicker-min-css' );
?><div class="wrap">
	<h1 class="wp-heading-inline"><?php _e('Queue List',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></h1>
	<hr class="wp-header-end">
	<div class="pinterest-queue-screen">
	<!--  Main display starts here   -->
		<!-- Message display when error received from ajax JS -->
		<div id="display_message" class="pinterest-error-message"></div>
		<!-- Main listing starts from here -->
		<div class="pinterest_rich_pin-listing-main">
			<div class="pinterest_rich_pin-listing">
				<form method="GET" id="custom_filter_form">
					<!--  For initial parameters  -->
					<input type="hidden" name="page" value="pinterest_rich_pin" />
					<input type="hidden" name="pg" value="1" />
					<input type="hidden" name="orderby" value="<?php echo $orderby; ?>" />
					<input type="hidden" name="order" value="<?php echo $order; ?>" />
					<input type="hidden" name="queue_status" value="<?php if(isset($_REQUEST['queue_status']) && $_REQUEST['queue_status'] != ''){ echo $_REQUEST['queue_status']; } ?>" />
					<!-- Data filteration starts Added the class bold to make the font bold -->
					
						<?php
							if(!empty($total_results)){
								?><ul class="subsubsub"><?php
									if((isset($_REQUEST['queue_status']) && $_REQUEST['queue_status'] != "") || (isset($_REQUEST['status'])  && $_REQUEST['status'] != "")){
										$bold_class = "";
									}else{
										$bold_class = "class='bold'";
									}
									$queue_count_all = pinterest_rich_pin_total_by_status('');
									?><li>
										<a href="<?php echo menu_page_url('pinterest_rich_pin',false); ?>" <?php if($bold_class){ echo 'class="current"'; } ?>>
											<?php _e('All',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?> <span class="count">(<?php echo $queue_count_all; ?>)</span>
										</a>
									</li><?php
									if(isset($_REQUEST['queue_status']) && $_REQUEST['queue_status'] != "" && $_REQUEST['queue_status'] == 'processing'){
										$bold_class = "class='bold'";
									}else{
										$bold_class = "";
									}
									$queue_count = pinterest_rich_pin_total_by_status('processing');
									if($queue_count > 0){
										?> | <li>
											<a href="<?php echo menu_page_url('pinterest_rich_pin',false)."&queue_status=processing"; ?>" <?php if($bold_class){ echo 'class="current"'; } ?>>
												<?php _e('Processing',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?> <span class="count">(<?php echo $queue_count; ?>)</span>
											</a>
										</li><?php
									}
									if(isset($_REQUEST['queue_status']) && $_REQUEST['queue_status'] != '' && $_REQUEST['queue_status'] == 'failed'){
											$bold_class = "class='bold'";
									}else{
											$bold_class = "";
									}
										
									$queue_count = pinterest_rich_pin_total_by_status('failed');
									if($queue_count > 0){
										?> | <li>
											<a href="<?php echo menu_page_url('pinterest_rich_pin',false)."&queue_status=failed"; ?>">
												<?php _e('Failed',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?> <span class="count">(<?php echo $queue_count; ?>)</span>
											</a>
										</li><?php 
									}
								?></ul><?php
							}
						?>
					<!-- Data filteration ends -->
					<!-- Increase data records dropdown and bulk action  --><?php
					if(!empty($total_results)){
						?><div class="vsz-post-per-page">
							<label for="ppp" class="padding-top:5px;"><?php _e('Show',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label>
							<select name="ppp" id="ppp" >
										<!-- <option <?php if($max == 1 ){ echo 'selected="selected"'; } ?>>1</option> -->
										<option <?php if($max == 10 ){ echo 'selected="selected"'; } ?>>10</option>
										<option <?php if($max == 25 ){ echo 'selected="selected"'; } ?>>25</option>
										<option <?php if($max == 50 ){ echo 'selected="selected"'; } ?>>50</option>
										<option <?php if($max == 100 ){ echo 'selected="selected"'; } ?>>100</option>
							</select>
							<!-- <input type="submit" name="export_prescriptions" value="Export" class="button button-primary" style="margin-left:15px;" /> -->
						</div><?php
					}
				?></form>
				<div class="clearfix"></div>
				<form name="quote_publish_status" id="quote_publish_status" method="post">
					<div class="vsz-paginate-bar">
						<div class="tablenav">
							<?php
								if(!empty($total_results)){
									?><div class="alignleft actions bulkactions">
										<label for="ticketform-publish-status" class="screen-reader-text"><?php _e('Bulk Action',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label>
										<select name="queue-bulk-action" id="queue-bulk-action" >
											<option value=""><?php _e('Bulk Action',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option><?php 
											if(isset($_REQUEST['status']) && $_REQUEST['status'] == "processing"){ 
												?><option value="remove"><?php _e('Remove',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option><?php
											}
											else if(isset($_REQUEST['status']) && $_REQUEST['status'] == "failed"){ 
												?><option value="retry"><?php _e('Retry',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option><?php 
											}
											else{
												?><option value="remove"><?php _e('Remove',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
												<option value="retry"><?php _e('Retry',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option><?php 
											}
										?></select>
										<input type="button" class="button action" id="bulk_queue_action" name="bulk_queue_action" value="<?php _e('Apply',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" title="<?php _e('Apply Selected Bulk Action',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>">
									</div>
									<?php
								}
							?>
							<!-- Pagination starts -->
							<div class="pagination-and-totle">
								<div class="pinterest_rich_pin_total"><?php _e('Total',PINTEREST_RICH_PINS_TEXT_DOMAIN); echo ' :'; echo $total_results; echo " "; _e('Entrie(s)',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></div>
								<div class="paginate-list"><?php
									$pagination = pinterest_rich_pin_queue_pagination_admin ($totalposts,$p,$lpm1,$prev,$next,$page,3,$orderby,$order,$where_append);
									echo $pagination;
								?></div>
							</div>
						</div>
					</div>
					<!-- Increase data records dropdown and bulk action  -->
				<table class="pinterest_rich_pin-listing-table wp-list-table widefat fixed striped posts" width="100%">
					<thead>
						<tr>
							<th class="column-check"><input type="checkbox" name="pinterest-rich_pin-checkAll" id="pinterest-rich_pin-checkAll" value=""></th>
							<th class="sorted <?php if($orderby == "id"){
									if($order == "ASC"){
										echo "arrowdown asc";
									}
									if($order == "DESC"){
										echo "arrowup desc";
									}
								}
								else{
									if($order == "ASC"){
										echo "arrowup-hover asc";
									}
									if($order == "DESC"){
										echo "arrowdown-hover desc";
									}
								}
							?>" >
								<a href="<?php echo admin_url()."admin.php?page=".$page."&pg=1&orderby=id&order=".$order_changed.$where_append_order; ?>"><span><?php _e('ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></span><span class="sorting-indicator"></span></a>
							</th>
							<th class="column-thumb"><?php _e('Image',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></th>
							<th class="column-name"><?php _e('Product',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></th>
							<th class="column-status"><?php _e('Status',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></th>
							<th class="column-date">
								<?php _e('Create Date',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>
							</th>
							<th class="column-action">
								<?php _e('Action To Perform',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>
							</th>
							<th class="column-btns"><?php _e('',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></th>
						</tr>
					</thead>
					<tbody><?php
						$no = 0;

						if(!empty($main_results)){
							foreach($main_results as $pinterest_rich_pin_queue){
								$queue_id = '';
								$action = '';
								$name = '';
								$queue_status = '';
								$product_id = "";
								$image_id = "";
								$created_by = '';
								$queue_note = '';
								$queue_session_key = '';
								
								$no++;
								// For User Details :-
								$queue_id = $pinterest_rich_pin_queue->id;
								// For name
								$product_id = $pinterest_rich_pin_queue->product_id;
								$image_id = $pinterest_rich_pin_queue->image_id;
								$name = ucfirst(get_the_title($product_id));
								$product_link = get_the_permalink($product_id);
								
								// For email
								$action = $pinterest_rich_pin_queue->action;
								
								// Status
								$queue_status = $pinterest_rich_pin_queue->status;
								$queue_status_class = $pinterest_rich_pin_queue->status;
								
								
								
								// queue Date time
								$queue_datetime = $pinterest_rich_pin_queue->create_date;
								$queue_created_by = $pinterest_rich_pin_queue->created_by;
								// queue Note
								$queue_note_data = "";
								if(isset($queue_note_data['note']) && !empty($queue_note_data)){
									$queue_note = $queue_note_data['note'];
								}
								// queue Session key
								$queue_session_key = "";
								$currency = "";
								if(empty($currency)){
									$currency = get_woocommerce_currency_symbol();
								}
								//Include the item-parts fro the re usability if needed further
								include 'pinterest-rich-pins-list-item-parts.php';
							}

						}else{

							echo "<tr><td colspan='10' class='text-center'>".__('Queue list is empty.',PINTEREST_RICH_PINS_TEXT_DOMAIN)."</td></tr>";

						}
					?></tbody>
				</table>
			</form>
			<div class="textright"><?php 
				echo pinterest_rich_pin_queue_pagination_admin ($totalposts,$p,$lpm1,$prev,$next,$page,3,$orderby,$order,$where_append);
			?></div>
		</div>
	</div>
</div>
