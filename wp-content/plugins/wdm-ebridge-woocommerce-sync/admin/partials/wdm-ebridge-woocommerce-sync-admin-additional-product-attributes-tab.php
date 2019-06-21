<?php
/**
 * Product general data panel.
 *
 * @package WooCommerce/Admin
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="add_additional_product_attributes" class="panel woocommerce_options_panel">
	<?php
		global $product_object;
		echo '<pre>';
		var_dump( $product_object->get_meta_data() );
		echo '</pre>';
	?>
</div>
