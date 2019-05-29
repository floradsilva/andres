<?php 
class WCCM_OrderDetailsPage
{
	public function __construct()
	{
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
		add_action( 'woocommerce_admin_order_data_after_order_details',  array( &$this,'display_custom_data' ));
	}
	public function add_meta_boxes()
	{
		add_meta_box( 'woocommerce-customers-manager-user-note', __('User notes', 'woocommerce-customers-manager'), array( &$this, 'add_user_note_meta_box' ), 'shop_order', 'side', 'high');
		
	}
	public function add_user_note_meta_box($post)
	{
		global $wccm_customer_model;
		$order = wc_get_order($post->ID);
		$user_id = $order->get_customer_id();
		
		if($user_id == 0)
			return;
		
		?>
			<p><?php echo $wccm_customer_model->get_user_notes($user_id); ?></p>
			<a class="button-primary" target="_blank" href="<?php echo get_admin_url()."admin.php?page=woocommerce-customers-manager&customer={$user_id}&action=customer_details"; ?>"> <?php _e('Edit', 'woocommerce-customers-manager' ); ?> </a>
		<?php
	}
	public function display_custom_data( $order ) 
	{
		global $wccm_order_model;
		$user_id = $order->get_customer_id();
		
		$user_details_page_link = $user_id != 0 ? get_admin_url()."admin.php?page=woocommerce-customers-manager&customer={$user_id}&action=customer_details" : 
												  get_admin_url()."admin.php?page=woocommerce-customers-manager&customer_email={$order->get_billing_email()}&action=customer_details";
		
		$is_guest = false;
		if($user_id != 0)
		{
			$order_number = count($wccm_order_model->get_user_orders_ids($user_id));
		}
		else 
		{
			$is_guest = true;
			$order_number = $wccm_order_model->get_guest_orders_num(false, $order->get_billing_email());
			$order_number = $order_number->total_guest_orders;
		}
		//$order_number = $user_id != 0 ? count($wccm_order_model->get_user_orders_ids($user_id)) : $wccm_order_model->get_guest_orders_num(false, $order->get_billing_email());
		
		wp_enqueue_style('wcccm-order-details', WCCM_PLUGIN_PATH.'/css/admin-order-details-page.css'); 
		?>
		<div class="wccm_order_details_column">
			<p class="form-field form-field-wide">
			  <label class="wcccm_label"><?php _e('First time customer:', 'woocommerce-customers-manager') ?></label>
			  <?php echo $order_number > 1 ? __('No', 'woocommerce-customers-manager') : __('Yes', 'woocommerce-customers-manager'); ?>		  
			</p>
			<p class="form-field form-field-wide">
			  <label class="wcccm_label"><?php _e('Number of orders: ', 'woocommerce-customers-manager') ?></label>
			  <?php echo $order_number; 
			  if($is_guest): ?>
			  <small> <?php _e('(orders with same billing email address)', 'woocommerce-customers-manager') ?></small>
			  <?php endif; ?>
			</p>		
			
			<a target="_blank" id="wccm_user_details_button" class="button-primary wccm_user_datails_button" href="<?php echo $user_details_page_link; ?>"> <?php _e('Details & Orders list', 'woocommerce-customers-manager') ?></a>
		</div>
		<?php
	}
}
?>