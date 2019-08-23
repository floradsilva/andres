<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 9/14/18
 * Time: 21:54
 */
class OP_Woo{
    private $settings_api;
    private $_core;
    private $_session;

    public function __construct()
    {
        $this->_session = new OP_Session();
        $this->settings_api = new Openpos_Settings();
        $this->_core = new Openpos_Core();

    }

    public function init(){
        add_filter( 'posts_where', array($this,'title_filter'), 10, 2 );
        add_filter('woocommerce_payment_complete_reduce_order_stock',array($this,'op_payment_complete_reduce_order_stock'),10,2);
        add_action( 'woocommerce_payment_complete', array($this,'op_maybe_reduce_stock_levels'),100,1 );
        add_action( 'parse_query', array( $this, 'order_table_custom_fields' ) );
        add_action( 'woocommerce_order_refunded', array( $this, 'woocommerce_order_refunded' ),10,2 );
        add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'woocommerce_hidden_order_itemmeta' ),10,1 );
        add_filter( 'woocommerce_available_payment_gateways', array( $this, 'woocommerce_available_payment_gateways' ),10,1 );
        add_filter( 'woocommerce_order_get_payment_method_title', array( $this, 'woocommerce_order_get_payment_method_title' ),10,2 );

        add_action( 'woocommerce_product_options_sku', array( $this, 'woocommerce_product_options_sku_after' ),100);
        add_action( 'woocommerce_variation_options_dimensions', array( $this, 'woocommerce_variation_options_dimensions_after' ),100,3);
        add_action('woocommerce_save_product_variation',array($this,'woocommerce_save_product_variation'),10,2);
        add_action('woocommerce_admin_process_product_object',array($this,'woocommerce_admin_process_product_object'),10,1);
        add_action('woocommerce_after_order_itemmeta',array($this,'woocommerce_after_order_itemmeta'),10,3);

        add_action('woocommerce_email_recipient_customer_completed_order',array($this,'woocommerce_email_recipient_customer_completed_order'),10,2);

        add_action('woocommerce_admin_order_data_after_shipping_address',array($this,'woocommerce_admin_order_data_after_shipping_address'),10,1);


        add_filter('manage_edit-shop_order_columns', array($this,'order_columns_head'),10,1);
        add_action('manage_shop_order_posts_custom_column', array($this,'order_columns_content'), 10, 2);

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );

        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            add_action( 'restrict_manage_posts', array( $this, 'filter_orders_by_source' ) );
            add_filter( 'parse_query', array( $this,'filter_request_query') , 10,1);


        }
    }

    public function order_columns_head($defaults){

        $result = array();
        foreach($defaults as $key => $value)
        {
            $result[$key] = $value;
            if($key == 'cb')
            {
                $result['op_source']  = __('Source','openpos');

            }
        }
        return $result;
    }
    public function order_columns_content($column_name, $post_ID){
        if ($column_name == 'op_source') {
            $source = get_post_meta($post_ID,'_op_order_source',true);
            if($source == 'openpos')
            {
                echo '<img style="width: 16px;" alt="from openpos" src="'.OPENPOS_URL.'/assets/images/shop.png">';
            }else{
                echo '<img style="width: 16px;" alt="from online website" src="'.OPENPOS_URL.'/assets/images/woocommerce.png">';
            }
        }
    }

    public function woocommerce_hidden_order_itemmeta($meta){
        $meta[] = '_op_local_id';
        $meta[] = '_op_seller_id';
        return $meta;
    }
    public function woocommerce_order_refunded($order_id,$refund_id)
    {

        global $op_warehouse;

        $warehouse_id = get_post_meta($order_id,'_pos_order_warehouse',true);

        if($warehouse_id > 0)
        {
                $refund = new WC_Order_Refund($refund_id );
                $line_items = $refund->get_items();
                foreach($line_items as $item)
                {
                    $product_id = $item->get_product_id();
                    $variation_id = $item->get_variation_id();

                    $post_product_id = $product_id;
                    if($variation_id  > 0)
                    {
                        $post_product_id = $variation_id;
                    }
                    $refund_qty = $item->get_quantity();
                    $current_qty = $op_warehouse->get_qty($warehouse_id,$post_product_id);
                    $new_qty = $current_qty - $refund_qty;
                    $op_warehouse->set_qty($warehouse_id,$post_product_id,$new_qty);
                }
        }

    }
    public function order_pos_payment($order){
        global $op_warehouse;
        global $op_register;
        $id = $order->get_id();
        $source = get_post_meta($id,'_op_order_source',true);
        if($source == 'openpos')
        {
            $payment_methods = get_post_meta($id,'_op_payment_methods',true);
            $_op_sale_by_person_id = get_post_meta($id,'_op_sale_by_person_id',true);
            $_pos_order_id = get_post_meta($id,'_pos_order_id',true);
            $warehouse_meta_key = $op_warehouse->get_order_meta_key();
            $register_meta_key = $op_register->get_order_meta_key();
            $warehouse_id = get_post_meta($id,$warehouse_meta_key,true);
            $register_id = get_post_meta($id,$register_meta_key,true);
            $warehouse = $op_warehouse->get($warehouse_id);
            $register = $op_register->get($register_id);
            ?>
            <?php if($_op_sale_by_person_id): $person = get_user_by('id',$_op_sale_by_person_id);  ?>

            <p class="form-field form-field-wide">
                <label><?php esc_html_e( 'POS Order Number:', 'openpos' ); ?> <b><?php echo esc_html($_pos_order_id)?></b></label>
            </p>
        <?php endif; ?>
            <?php if($_op_sale_by_person_id): $person = get_user_by('id',$_op_sale_by_person_id);  ?>
            <p class="form-field form-field-wide">
                <label><?php esc_html_e( 'Shop Agent:', 'openpos' ); ?> <b><?php echo esc_html($person->get('display_name') )?></b></label>
            </p>
            <?php endif; ?>
            <?php if(!empty($warehouse)): ?>
            <p class="form-field form-field-wide">
                <label><?php esc_html_e( 'Outlet:', 'openpos' ); ?> <b><?php echo esc_html($warehouse['name'] )?></b></label>
            </p>
            <?php endif; ?>
            <?php if(!empty($warehouse)): ?>
            <p class="form-field form-field-wide">
                <label><?php esc_html_e( 'Register:', 'openpos' ); ?> <b><?php echo isset($register['name']) ?  esc_html($register['name'] ) : __('Unknown','openpos'); ?></b></label>
            </p>
            <?php endif; ?>
            <?php if($payment_methods): ?>
            <p class="form-field form-field-wide">
                <label><?php esc_html_e( 'POS Payment method:', 'openpos' ); ?></label>
                <ul>
                <?php foreach($payment_methods as $method): ?>
                    <li><?php echo esc_html($method['name']); ?>: <?php echo wc_price($method['paid']); ?> <?php echo $method['ref'] ? '('.esc_html($method['ref']).')':''; ?></li>
                <?php endforeach; ?>
                </ul>
            </p>
            <?php endif; ?>
            <?php
        }

    }
    public function get_cashiers(){
        $args = array(
            'meta_key' => '_op_allow_pos',
            'meta_value' => '1',
            'fields' => array('ID', 'display_name','user_email','user_login','user_status'),
            'number' => -1
        );
        $cashiers =  get_users( $args);
        $result = array();
        foreach($cashiers as $cashier)
        {
            $result[] = $cashier;
        }
        return $result;
    }
    public function op_payment_complete_reduce_order_stock($result,$order_id){
        global $op_warehouse;

        $warehouse_meta_key = $op_warehouse->get_order_meta_key();
        $warehouse_id = get_post_meta($order_id,$warehouse_meta_key,true);
        if($warehouse_id > 0)
        {
            $result = false;
        }
        return $result;
    }
    public function op_maybe_reduce_stock_levels($order_id)
    {
        global $_op_warehouse_id;
        global $op_warehouse;
        if ( is_a( $order_id, 'WC_Order' ) ) {
            $order    = $order_id;
            $order_id = $order->get_id();
        } else {
            $order = wc_get_order( $order_id );
        }
        $warehouse_id = $_op_warehouse_id;

        if($warehouse_id > 0)
        {
            foreach ( $order->get_items() as $item ) {
                if ( ! $item->is_type( 'line_item' ) ) {
                    continue;
                }

                $product = $item->get_product();
                $item_data = $item->get_data();
                $variation_id = isset($item_data['variation_id']) ? $item_data['variation_id'] : 0;

                if ( $product ) {
                    $product_id = $product->get_id();
                    if($variation_id > 0)
                    {
                        $product_id = $variation_id;
                    }

                    $qty       = apply_filters( 'woocommerce_order_item_quantity', $item->get_quantity(), $order, $item );
                    $current_qty = $op_warehouse->get_qty($warehouse_id,$product_id);
                    if(!$current_qty)
                    {
                        $current_qty = 0;
                    }
                    $new_qty = $current_qty - $qty;
                    $op_warehouse->set_qty($warehouse_id,$product_id,$new_qty);

                }
            }

        }
    }
    public function order_table_custom_fields($wp){
        global $pagenow;

        if ( 'edit.php' !== $pagenow  || 'shop_order' !== $wp->query_vars['post_type']  ) { // WPCS: input var ok.
            return;
        }
        if(isset( $_GET['warehouse'] ))
        {
            $query_vars = $wp->query_vars;
            $query_vars['meta_key'] = '_pos_order_warehouse';
            $query_vars['meta_value'] = (int)$_GET['warehouse'];
            $wp->query_vars = $query_vars;
            return;
        }
        if(isset( $_GET['register'] ))
        {
            $query_vars = $wp->query_vars;
            $query_vars['meta_key'] = '_pos_order_cashdrawer';
            $query_vars['meta_value'] = (int)$_GET['register'];
            $wp->query_vars = $query_vars;
            return;
        }

    }
    public function get_variations($product_id,$warehouse_id = 0){
        $core = $this->_core;
        $variation = new WC_Product_Variable($product_id);
        $item_variations = $variation->get_available_variations();
        $variant_products_with_attribute = array();
        $variation_attributes          = $variation->get_variation_attributes();

        $price_list = array();
        $variations = array();
        $qty_list = array();
        foreach($item_variations as $a_p)
        {
            // $variant_product = new WC_Product_Variable($a_p['variation_id']);

            $variant_product = wc_get_product($a_p['variation_id']);
            $a_p_price =  wc_get_price_including_tax($variant_product);


            //end update price

                $v_tmp = array(
                    'value_id' => $a_p['variation_id'],
                    'price' => $a_p_price
                );
                $variation_prices[] = $v_tmp;

                $variant_products_with_attribute[] = array(
                    'value_id' => $a_p['variation_id'],
                    'price' => $a_p_price,
                    'attributes' => $a_p['attributes']
                );


        }

        foreach($variation_attributes as $key => $variants)
        {

            if(strpos($key,'pa_') === false)
            {
                $key = strtolower(esc_attr(sanitize_title($key)));
            }

            $options = array();
            foreach($variants as $v)
            {
                $option_label = $v;
                $values = array();
                $values_price = array();





                foreach($variant_products_with_attribute as $vp)
                {
                    $attribute_key_1 = strtolower('attribute_'.$key);

                    if(isset($vp['attributes'][$attribute_key_1]) && ($vp['attributes'][$attribute_key_1] == $v || $vp['attributes'][$attribute_key_1] == ''))
                    {
                        if($vp['value_id'])
                        {
                            $taxonomy = $key;
                            $term = get_term_by('slug', $v, $taxonomy);
                            if($term)
                            {
                                $option_label = $term->name;
                            }
                            $barcode = $core->getBarcode($vp['value_id']);
                            if($barcode)
                            {
                                $product_post = get_post($vp['value_id']);
                                $child_data = $this->get_product_formatted_data($product_post,$warehouse_id,true);
                                $values_price[$barcode] = $child_data['price_included_tax'] ? ($child_data['final_price'] + $child_data['tax_amount']) : $child_data['final_price'];
                                $values[] = $barcode;
                                $price_list[] =  $child_data['final_price'];
                                $qty_list[$barcode] = 1 * $child_data['qty'];
                            }

                        }

                    }
                }
                if(!empty($values))
                {
                    $values = array_unique($values);
                }
                $option_tmp = array(
                    'title' => $option_label,
                    'slug' => $v,
                    'values' => $values,
                    'prices' => $values_price
                );
                $options[] = $option_tmp;
            }
            $variant = array(
                'title' => wc_attribute_label( $key ),
                'slug' => $key,
                'options' => $options
            );
            $variations[] = $variant;
        }
        /*
        $variations = array(
            0 => array(
                'title' => 'Variation Color',
                'slug' => 'color',
                'options' => array(
                    0 => array(
                        'title' => 'Red',
                        'slug' => 'red',
                        'values' => array(100,101),
                        'prices' => array()
                    ),
                    1 => array(
                        'title' => 'Blue',
                        'slug' => 'blue',
                        'values' => array(102,103),
                        'prices' => array()
                    )
                )
            )
        );
        */
        $result = array(
            'variations' => $variations,
            'price_list' => $price_list,
            'qty_list' => $qty_list
        );

        return $result;
    }
    public function get_product_formatted_data($_product,$warehouse_id,$ignore_variable = false){
        global $op_warehouse;
        $product_id = $_product->ID;
        $product = wc_get_product($product_id);
        $options = array();
        $bundles = array();
        $variations = array();
        if(!$product)
        {
            return false;
        }
        $image =  wc_placeholder_img_src() ;

        if ( has_post_thumbnail( $product->get_id() ) ) {
            $attachment_id =  get_post_thumbnail_id( $product->get_id() );
            $size = 'shop_thumbnail';
            $custom_width = $this->settings_api->get_option('pos_image_width','openpos_pos');
            $custom_height = $this->settings_api->get_option('pos_image_height','openpos_pos');
            if($custom_width && $custom_height)
            {
                $size = array($custom_width,$custom_height);
            }
            $image_attr = wp_get_attachment_image_src($attachment_id, $size);

            if(is_array($image_attr))
            {
                $image = $image_attr[0];
            }
        }

        $type = $product->get_type();
        $post_type = get_post_type($product->get_id());
        if($type == 'grouped')
        {
            return false;
        }

        $qty = $product->get_stock_quantity();
        $manage_stock = $product->get_manage_stock();
        $product_id = $product->get_id();

        if($warehouse_id > 0)
        {
            $manage_stock = true;
            $qty = 1 * $op_warehouse->get_qty($warehouse_id,$product_id);
        }

        $group = array();
        $price_display_html = '';
        if(!$ignore_variable)
        {
            switch ($type)
            {

                case 'grouped':
                    $group = $product->get_children();
                    break;
                case 'variable':
                    if($post_type == 'product')
                    {
                        $variations_result = $this->get_variations($product->get_id(),$warehouse_id);
                        $variations = $variations_result['variations'];
                        $price_list = $variations_result['price_list'];
                        $qty_list = $variations_result['qty_list'];
                        $qty = array_sum($qty_list);
                        if(!empty($price_list))
                        {
                            $price_list_min = min($price_list);
                            $price_list_max = max($price_list);
                            if($price_list_min != $price_list_max)
                            {
                                $price_list_min = wc_price($price_list_min,array('currency'=> '&nbsp;'));
                                $price_list_max = wc_price($price_list_max,array('currency'=> '&nbsp;'));
                                $price_display_html = implode(' - ',array($price_list_min,$price_list_max));
                            }else{
                                $price_display_html = wc_price($price_list_min,array('currency'=> '&nbsp;'));
                            }
                        }

                    }
                    break;
            }
        }

        $price_display_html = $product->get_price_html();

        $final_price = $product->get_price();
        if(!$final_price)
        {
            $final_price = 0;
        }

        $tax_amount = 0;
        $setting_tax_class = $this->settings_api->get_option('pos_tax_class','openpos_general');
        $tmp_tax_rates = array();
        $tax_rate = array(
                'code' => 'openpos', // in percentage
                'rate' => 0, // in percentage
                'shipping' => 'no',
                'compound' => 'no',
                'rate_id' => 0,
                'label' => __('Tax on POS','openpos')
        );

        if(wc_tax_enabled() )
        {

            if( $setting_tax_class != 'op_notax')
            {
                if($setting_tax_class == 'op_productax')
                {
                    $tax_rates = $this->getTaxRates( $product->get_tax_class() );

                    if(!empty($tax_rates))
                    {
                        $keys = array_keys($tax_rates);
                        $rate_id = max($keys);
                        $rate = $tax_rates[$rate_id];

                        $tax_amount = array_sum(@WC_Tax::calc_tax( $final_price, array($rate_id => $rate), wc_prices_include_tax() ));

                        $tax_rate['code'] = $product->get_tax_class() ? $product->get_tax_class().'_'.$rate_id : 'standard_'.$rate_id;
                        $tax_rate['rate_id'] = $rate_id;
                        if($rate['label'])
                        {
                            $tax_rate['label'] = $rate['label'];
                        }
                        if(isset($rate['shipping']))
                        {
                            $tax_rate['shipping'] = $rate['shipping'];
                        }
                        if(isset($rate['compound']))
                        {
                            $tax_rate['compound'] = $rate['compound'];
                        }
                        if(isset($rate['rate']))
                        {
                            $tax_rate['rate'] = $rate['rate'];
                        }
                    }
                }else{
                    $tax_rates = $this->getTaxRates( $setting_tax_class );

                    if(!empty($tax_rates))
                    {
                        $keys = array_keys($tax_rates);
                        $rate_id = max($keys);

                        $setting_tax_rate_id = $this->settings_api->get_option('pos_tax_rate_id','openpos_general');
                        if($setting_tax_rate_id)
                        {
                            $rate_id = $setting_tax_rate_id;
                        }
                        $rate = $tax_rates[$rate_id];

                        $tax_amount = array_sum(@WC_Tax::calc_tax( $final_price, array($rate_id => $rate), wc_prices_include_tax() ));

                        $tax_rate['code'] = $setting_tax_class ? $setting_tax_class.'_'.$rate_id : 'standard'.'_'.$rate_id;
                        $tax_rate['rate_id'] = $rate_id;
                        if($rate['label'])
                        {
                            $tax_rate['label'] = $rate['label'];
                        }
                        if(isset($rate['shipping']))
                        {
                            $tax_rate['shipping'] = $rate['shipping'];
                        }
                        if(isset($rate['compound']))
                        {
                            $tax_rate['compound'] = $rate['compound'];
                        }
                        if(isset($rate['rate']))
                        {
                            $tax_rate['rate'] = $rate['rate'];
                        }
                    }
                    // custom tax
                }
            }
        }
        $tmp_tax_rates[] = $tax_rate;
        $price_without_tax = $product->get_price();

        $price_included_tax = false;

        if(wc_tax_enabled())
        {

            $price_included_tax = wc_prices_include_tax();
            if($price_included_tax)
            {
                    $tax_amount = wc_round_tax_total($tax_amount);
                    $price_without_tax = $final_price - $tax_amount;
            }
        }


        $display_pos = true;
        if(get_post_type($product->get_id()) == 'product_variation')
        {
            $display_pos = false;
        }

        $categories = $this->get_product_categories($product->get_id());
        if(!$categories)
        {
            $categories = array();
        }

        $tmp = array(
            'name' => $product->get_name(),
            'id' => $product->get_id(),
            'parent_id' => $product->get_id(),
            'sku' => $product->get_sku(),
            'qty' => $qty,
            'manage_stock' => $manage_stock,
            'stock_status' => $product->get_stock_status(),
            'barcode' => trim($this->_core->getBarcode($product->get_id())),
            'image' => $image,
            'price' => $price_without_tax,
            'final_price' => $price_without_tax,
            'special_price' => $product->get_sale_price(),
            'regular_price' => $product->get_regular_price(),
            'sale_from' => $product->get_date_on_sale_from(),
            'sale_to' => $product->get_date_on_sale_to(),
            'status' => $product->get_status(),
            'categories' => array_unique($categories),//$product->get_category_ids(),
            'tax' => $tmp_tax_rates,
//            'tax' => $tax_rate,
            'tax_amount' => $tax_amount,
            'price_included_tax' => $price_included_tax,
            'group_items' => $group,
            'variations' => $variations,
            'options' => $options,
            'bundles' => $bundles,
            'display_special_price' => false,
            'allow_change_price' => false,
            'price_display_html' => $price_display_html,
            'display' => $display_pos
        );
        if($this->settings_api->get_option('pos_change_price','openpos_pos') == 'yes')
        {
            $tmp['allow_change_price'] = true;
        }
        $product_data = apply_filters('op_product_data',$tmp,$_product);
        return $product_data;

    }
    public function getTaxRates($tax_class){
        global $wpdb;
        $criteria = array();
        $criteria[] = $wpdb->prepare( 'tax_rate_class = %s', sanitize_title( $tax_class ) );
        $found_rates = $wpdb->get_results( "
			SELECT tax_rates.*
			FROM {$wpdb->prefix}woocommerce_tax_rates as tax_rates
			WHERE 1=1 AND " . implode( ' AND ', $criteria ) . "
			GROUP BY tax_rates.tax_rate_id
			ORDER BY tax_rates.tax_rate_priority
		" );

        $matched_tax_rates = array();

        foreach ( $found_rates as $found_rate ) {

            $matched_tax_rates[ $found_rate->tax_rate_id ] = array(
                'rate'     => (float) $found_rate->tax_rate,
                'label'    => $found_rate->tax_rate_name,
                'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
                'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no',
            );
        }
        return $matched_tax_rates;
    }
    public function stripe_charge($amount,$source){
        global $OPENPOS_SETTING;
        $stripe_secret_key = $OPENPOS_SETTING->get_option('stripe_secret_key','openpos_payment');
        if($stripe_secret_key)
        {
            \Stripe\Stripe::setApiKey($stripe_secret_key);
            $currency = get_woocommerce_currency();
            $charge = \Stripe\Charge::create(['amount' => $amount, 'currency' => strtolower($currency), 'source' => $source]);
            return $charge->__toArray(true);
        }else{
            return array();
        }
    }

    public function stripe_refund($charge_id){
        global $OPENPOS_SETTING;
        $stripe_secret_key = $OPENPOS_SETTING->get_option('stripe_secret_key','openpos_payment');
        if($stripe_secret_key)
        {
            \Stripe\Stripe::setApiKey($stripe_secret_key);

            $refund = \Stripe\Refund::create([
                'charge' => $charge_id,
            ]);
            return $refund->__toArray(true);
        }else{
            return array();
        }
    }

    public function get_pos_categories(){
        global $OPENPOS_SETTING;
        $result = array();
        $category_ids = $OPENPOS_SETTING->get_option('pos_categories','openpos_pos');

        if(is_array($category_ids))
        {

            foreach($category_ids as $cat_id)
            {
                $term = get_term_by( 'id', $cat_id, 'product_cat', 'ARRAY_A' );
                if($term && !empty($term))
                {
                    $parent_id =  $term['parent'];
                    if(!in_array($parent_id,$category_ids))
                    {
                        $parent_id = 0;
                    }
                    $tmp  = array(
                        'id' => $cat_id,
                        'name' => $term['name'],
                        'image' => OPENPOS_URL.'/assets/images/category_placehoder.png',
                        'description' => '',
                        'parent_id' => $parent_id,
                        'child' => array()
                    );

                    $thumbnail_id = get_term_meta( $cat_id, 'thumbnail_id', true );
                    $image = wp_get_attachment_url( $thumbnail_id );
                    if ( $image ) {
                         $tmp['image'] = $image;
                    }

                    $result[] = apply_filters('op_category_data',$tmp,$category_ids);
                }
            }
        }
        if(!empty($result))
        {
            $tree = $this->buildTree($result);
        }else{
            $tree = [];
        }


        return apply_filters('op_category_tree_data',$tree,$result);
    }


    function buildTree($items) {
        $childs = array();
        foreach($items as &$item) $childs[$item['parent_id']][] = &$item;
        unset($item);
        foreach($items as &$item) if (isset($childs[$item['id']]))
            $item['child'] = $childs[$item['id']];
        return $childs[0];
    }

    public function get_product_categories($product_id){
        global $OPENPOS_SETTING;
        $product = wc_get_product($product_id);
        $categories = $product->get_category_ids();

        $category_ids = $OPENPOS_SETTING->get_option('pos_categories','openpos_pos');
        
        foreach($categories as $cat_id)
        {
            $tmp = $this->_cat_parent_ids($cat_id);
            $categories = array_merge($categories,$tmp);
        }
        $categories = array_unique($categories);
        if(!is_array($category_ids))
        {
            $cats = array();
        }else{
            $cats = array_intersect($category_ids,$categories);
        }

        if(!empty($cats))
        {
            $rest_cats = array_values($cats);
            return $rest_cats;
        }
        return $cats;
    }
    private function _cat_parent_ids($cat_id){
        $term = get_term_by( 'id', $cat_id, 'product_cat', 'ARRAY_A' );

        $result = array();
        if($term && $term['parent'] > 0 && $term['parent'] != $cat_id)
        {
            $result[] = $term['parent'];
            $tmp = $this->_cat_parent_ids($term['parent']);
            $result = array_merge($result,$tmp);
        }
        return $result;
    }

    public function get_shipping_method_by_code($code){
        $shipping_methods = WC()->shipping()->get_shipping_methods();
        $result = array(
                'code' => 'openpos',
                'title' => __('Custom Shipping','openpos')
        );
        foreach ($shipping_methods as $shipping_method)
        {
            $shipping_code = $shipping_method->id;
            if($code == $shipping_code)
            {
                $title = $shipping_method->method_title;
                if(!$title)
                {
                    $title = $code;
                }
                $result = array(
                    'code' =>$code,
                    'title' => $title
                );
            }

        }
        return $result;
    }

    public function woocommerce_available_payment_gateways($payment_methods){
        $order_id = absint( get_query_var( 'order-pay' ) );
        if($order_id > 0)
        {
            $pos_payment = get_post_meta($order_id,'pos_payment',true);
            if($pos_payment && is_array($pos_payment) && isset($pos_payment['code']))
            {
                $payment_code = $pos_payment['code'];
                if(isset($payment_methods[$payment_code]))
                {
                    $new_payment_method = array();
                    $new_payment_method[$payment_code] = $payment_methods[$payment_code];

                    return apply_filters( 'openpos_woocommerce_available_payment_gateways',$new_payment_method, $payment_methods );

                }

            }

        }
        return $payment_methods;
    }
    public function woocommerce_order_get_payment_method_title($value, $object){
        $payment_code = $object->get_payment_method();
        if($payment_code == 'pos_multi')
        {
            $methods = get_post_meta($object->get_id(), '_op_payment_methods', true);
            $method_values = array();
            if(!is_array($methods))
            {
                $methods = array();
            }
            foreach($methods as $code => $method)
            {
                $paid = isset($method['paid']) ? $method['paid'] : 0;
                if($paid > 0 && isset($method['name']))
                {
                    $return_paid = isset($method['return']) ? $method['return'] : 0;
                    $ref = isset($method['ref']) ? trim($method['ref']) : '';
                    if($return_paid > 0)
                    {
                        $paid = $paid - $return_paid;

                    }
                    if($ref)
                    {
                        $method_values[] = $method['name'].': '.strip_tags(wc_price($paid)).'('.$ref.')';
                    }else{
                        $method_values[] = $method['name'].': '.strip_tags(wc_price($paid));
                    }

                }

            }
            if(!empty($method_values))
            {
                return implode(', ',$method_values);
            }

        }
        return $value;
    }
    public function woocommerce_admin_order_data_after_shipping_address($order){
        $is_pos = get_post_meta($order->get_id(),'_op_order_source',true);

        if($is_pos == 'openpos' )
        {
            $_pos_shipping_phone = get_post_meta($order->get_id(),'_pos_shipping_phone',true);
            if($_pos_shipping_phone)
            {
                echo sprintf('<p><label>%s</label> : <span>%s</span></p>',__('Shipping Phone'),$_pos_shipping_phone);
            }
        }
    }
    // get formatted customer shipping address
    public function getCustomerShippingAddress($cutomer_id){
            $result = array();

            $customer = new WC_Customer($cutomer_id);
            $first_name = $customer->get_shipping_first_name();
            $last_name = $customer->get_shipping_last_name();
            if(!$first_name && !$last_name)
            {
                $first_name = $customer->get_first_name();
                $last_name = $customer->get_last_name();
            }
            $address_1 = $customer->get_shipping_address_1();
            $address_2 = $customer->get_shipping_address_2();
            $address = '';
            if($address_1 || $address_2)
            {
                $address = $address_1;
                if(!$address)
                {
                    $address = $address_2;
                }
            }else{
                $address = $customer->get_address();
            }
            $phone = $customer->get_billing_phone();
            $address = array(
                'id' => 1,
                'title' => $address,
                'name' => implode(' ',array($first_name,$last_name)),
                'address' => $address,
                'address_2' => $customer->get_shipping_address_2(),
                'state' => $customer->get_shipping_state(),
                'postcode' => $customer->get_shipping_postcode(),
                'city' => $customer->get_shipping_city(),
                'country' => $customer->get_shipping_country(),
                'phone' => $phone,
            );
            $result[] = $address;
            return $result;
    }
    public function woocommerce_product_options_sku_after(){
        global $post;
        global $product_object;
        $barcode_field = $this->settings_api->get_option('barcode_meta_key','openpos_label');
        $allow = false;
        if(!$barcode_field)
        {
            $barcode_field = '_op_barcode';
        }
        if($barcode_field == '_op_barcode' )
        {
            $allow = true;
        }

        if($allow) {
            $value = '';
            $product_id = $product_object->get_id();
            if($product_id)
            {
                $value = get_post_meta($product_id,$barcode_field,true);

            }
            echo '<div class="options_group hide_if_variable hide_if_grouped">';
            woocommerce_wp_text_input(
                array(
                    'id' => '_op_barcode',
                    'value' => $value,
                    'label' => '<abbr title="' . esc_attr__('Stock Keeping Unit', 'woocommerce') . '">' . esc_html__('OP Barcode', 'openpos') . '</abbr>',
                    'desc_tip' => true,
                    'description' => __('Barcode refers to use in POS panel.', 'openpos'),
                )
            );
            echo '</div>';
        }
    }

    public function woocommerce_variation_options_dimensions_after($loop, $variation_data, $variation){

            $barcode_field = $this->settings_api->get_option('barcode_meta_key','openpos_label');
            $allow = false;
            if(!$barcode_field)
            {
                $barcode_field = '_op_barcode';
            }
            if($barcode_field == '_op_barcode' )
            {
                $allow = true;
            }

            if($allow)
            {

                $value = '';
                if($variation && isset($variation->ID))
                {
                   $variation_id = $variation->ID;
                   $value = get_post_meta($variation_id,$barcode_field,true);

                }

                woocommerce_wp_text_input(
                    array(
                        'id'                => "_op_barcode{$loop}",
                        'name'              => "_op_barcode[{$loop}]",
                        'label'       => '<abbr title="' . esc_attr__( 'POS Barcode', 'openpos' ) . '">' . esc_html__( 'OP Barcode', 'openpos' ) . '</abbr>',
                        'desc_tip'    => true,
                        'value' => $value,
                        'description' => __( 'Barcode refers to use in POS panel.', 'openpos' ),
                        'wrapper_class' => 'form-row form-row-full',
                    )
                );
            }

    }
    public function woocommerce_save_product_variation($variation_id, $i){
        $barcode = isset( $_POST['_op_barcode'][ $i ] ) ? sanitize_text_field($_POST['_op_barcode'][ $i ]) : '';

        update_post_meta($variation_id,'_op_barcode',$barcode);
    }
    public function woocommerce_admin_process_product_object($product){
        $barcode = isset( $_POST['_op_barcode'] ) ? wc_clean( wp_unslash( $_POST['_op_barcode'] ) ) : '';
        $product_id = $product->get_id();
        $product_type = empty( $_POST['product-type'] ) ? WC_Product_Factory::get_product_type( $product_id ) : sanitize_title( wp_unslash( $_POST['product-type'] ) );
        if($product_type == 'variable')
        {

            $barcode = '';
        }
        update_post_meta($product_id,'_op_barcode',$barcode);
    }

    public function filter_orders_by_source(){
        global $typenow;
        if ( 'shop_order' === $typenow ) {
            $current = isset($_GET['_op_order_source']) ? esc_attr($_GET['_op_order_source']) : '';
             ?>
                <select name="_op_order_source" id="dropdown_order_source">
                    <option value="">
                        <?php esc_html_e( 'Filter by Source', 'openpos' ); ?>
                    </option>
                    <option <?php echo ($current == 'online') ? 'selected':''; ?> value="online"><?php esc_html_e( 'Online Order', 'openpos' ); ?></option>
                    <option <?php echo ($current == 'pos') ? 'selected':''; ?> value="pos"><?php esc_html_e( ' POS Orders', 'openpos' ); ?></option>

                </select>
            <?php
        }
    }
    public function add_order_filterable_where($where, $wp_query){
        global $typenow, $wpdb;

        if ( 'shop_order' === $typenow && isset( $_GET['_op_order_source'] ) && ! empty( $_GET['_op_order_source'] ) ) {
            // Main WHERE query part
            $source = isset($_GET['_op_order_source']) ? esc_attr($_GET['_op_order_source']) : '';
            if($source == 'online')
            {
                $where .= " AND $wpdb->postmeta.meta_value <> 'openpos'";
                //$where .= $wpdb->prepare( " AND woi.order_item_type='coupon' AND woi.order_item_name='%s'", wc_clean( $_GET['_coupons_used'] ) );
            }else{
                $where .= " AND $wpdb->postmeta.meta_value = 'openpos'";
            }

        }
        return $where;
    }
    public function filter_request_query($query){
        global $typenow, $wpdb;
        if ( 'shop_order' === $typenow && isset( $_GET['_op_order_source'] ) && ! empty( $_GET['_op_order_source'] ) ) {
            $source = $_GET['_op_order_source'];
            $meta_query = $query->meta_query;

            if($source == 'online')
            {
                $meta_arr = array(
                    'field' => '_op_order_source',
                    'compare' => 'NOT EXISTS'
                );
                $query->query_vars['meta_key'] = $meta_arr['field'];
                $query->query_vars['meta_compare'] = $meta_arr['compare'];
            }else{
                $meta_arr = array(
                    'field' => '_op_order_source',
                    'value' => 'openpos',
                    'compare' => '='
                );
                $query->query_vars['meta_key'] = $meta_arr['field'];
                $query->query_vars['meta_value'] = $meta_arr['value'];
                $query->query_vars['meta_compare'] = $meta_arr['compare'];
            }
        }

        return $query;
    }
    public function woocommerce_after_order_itemmeta( $item_id, $item, $product){


        $seller_id =  $item->get_meta( '_op_seller_id');
        $_op_local_id =  $item->get_meta( '_op_local_id');
        if($_op_local_id)
        {
            $has_seller = false;
            if($seller_id)
            {
                $user = get_user_by('id',$seller_id);
                if($user)
                {
                    echo '<p>'.__('Seller: ','openpos').'<strong>'.$user->display_name.'</strong></p>';
                    $has_seller = true;
                }

            }
            if(!$has_seller)
            {
                echo '<p>'.__('Sold By Shop Agent','openpos').'</p>';
            }
        }

    }
    public function getProductChanged($local_ver,$warehouse_id = 0){
        global $wpdb;
        global $op_warehouse;
        $meta_key = '_openpos_product_version_'.$warehouse_id;
        $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '".$meta_key."' AND meta_value >".($local_ver - 1)." ORDER BY meta_value ASC LIMIT 0,30", ARRAY_A);

        $result = array(
                'current_version' => $local_ver,
                'data' => array()
        );
        $db_version = get_option('_openpos_product_version_'.$warehouse_id,0);

        if(count($rows) == 0)
        {

            $result['current_version'] = $db_version;
        }
        foreach ($rows as $row)
        {
            $product_id = $row['post_id'];
            $product_verion = $row['meta_value'];
            $qty = $op_warehouse->get_qty($warehouse_id,$product_id);
            $result['current_version'] = $product_verion;

            $barcode = $product_id; //$this->_core->getBarcode($product_id);
            $result['data'][$barcode] = $qty;
        }
        return $result;
    }
    public function title_filter( $where, $wp_query )
    {
        global $wpdb;
        if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
            $where .= ' OR ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(  $search_term ) . '%\'';
        }
       
        return $where;
    }
    public function searchProductsByTerm($term,$limit=10){
        $args = array(
            'posts_per_page'   => $limit,
            'search_prod_title' => $term,
            'post_type'        => array('product','product_variation'),
            'post_status'      => 'publish',
            'suppress_filters' => false,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key'     => '_sku',
                    'value'   =>  trim($term) ,
                    'compare' => 'LIKE'
                )

            ),
        );
        $query = new WP_Query($args);
        $posts = $query->get_posts();

        return $posts;

    }
    public function getDefaultContry(){
        $store_country_state = get_option( 'woocommerce_default_country', '' );
        $store_country = '';
        $store_country_tmp = explode(':',$store_country_state);
        if($store_country_state && count($store_country_tmp) > 1)
        {
            $store_country = $store_country_tmp[0];
        }
        return $store_country;
    }
    public function getDefaultState(){
        $store_country_state = get_option( 'woocommerce_default_country', '' );
        $store_state = '';
        $store_country_tmp = explode(':',$store_country_state);
        if($store_country_state && count($store_country_tmp) == 2)
        {
            $store_state = $store_country_tmp[1];
        }
        return $store_state;
    }
    public function getCustomerAdditionFields(){

        $address_2_field = array(
            'code' => 'address_2',
            'type' => 'text',
            'label' =>  __('Address 2','openpos'),
            'options' => array(),
            'placeholder' => __('Address 2','openpos'),
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );


        $postcode_field = array(
            'code' => 'postcode',
            'type' => 'text',
            'label' =>  __('PostCode / Zip','openpos'),
            'options' => array(),
            'placeholder' => __('PostCode / Zip','openpos'),
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );

        $city_field = array(
            'code' => 'city',
            'type' => 'text',
            'label' =>  __('City','openpos'),
            'options' => array(),
            'placeholder' => __('City','openpos'),
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );

        $state_field = array(
            'code' => 'state',
            'type' => 'text',
            'label' =>  __('State','openpos'),
            'options' => array(),
            'placeholder' => __('State','openpos'),
            'description' => '',
            'onchange_load' => false,
            'allow_shipping' => 'yes',
        );

        $store_country = $this->getDefaultContry();
        $store_state  = $this->getDefaultState();
        $states = array();
        if($store_country)
        {
            $tmp_states     = WC()->countries->get_states( $store_country );
            foreach($tmp_states as $key => $val)
            {
                $_tmp_state = array(
                        'value' => $key,
                        'label' => $val
                );
                $states[] = $_tmp_state;
            }
        }
        if(!empty($states))
        {
            $state_field = array(
                'code' => 'state',
                'type' => 'select',
                'label' =>  __('State','openpos'),
                'options' => $states,
                'placeholder' => __('State','openpos'),
                'description' => '',
                'onchange_load' => false,
                'allow_shipping' => 'yes',
                'default' => $store_state
            );
        }
        $fields = array(
                $address_2_field,
                $city_field,
                $postcode_field,
                $state_field
        );

        return apply_filters( 'op_customer_addition_fields',$fields );
    }
    public function woocommerce_email_recipient_customer_completed_order($recipient,$_order){

        $is_pos = get_post_meta($_order->get_id(),'_op_order_source',true);
        $_op_email_receipt = get_post_meta($_order->get_id(),'_op_email_receipt',true);
        if($is_pos == 'openpos' && $_op_email_receipt == 'no')
        {
            $recipient = '';
        }
        return $recipient;
    }
    // format order to work with POS
    public function formatWooOrder($order_id){
        $order = wc_get_order($order_id);
        $order_number = $order_id;
        if($_pos_order_id = get_post_meta($order_id,'_pos_order_id',true))
        {
            // $order_number = $_pos_order_id.' ('.$order_id.')';
        }
        $grand_total = $order->get_total('ar');



        $billing_address = $order->get_address( 'billing' );

        $customer_data = array(
                'id' => $order->get_customer_id(),
                'name' => implode(' ',array($billing_address['first_name'],$billing_address['last_name'])),
                'address' => $billing_address['address_1'],
                'firstname' => $billing_address['first_name'],
                'lastname' => $billing_address['last_name'],
        );
        $customer_data = array_merge($customer_data,$billing_address);

        $item_ids = $order->get_items();
        $order_status = $order->get_status();
        $payment_status = $order_status;
        if($order_status == 'processing' || $order_status == 'completed')
        {
            $payment_status = 'paid';
        }

        $items = array();
        $qty_allow_refund = false;
        foreach($item_ids as $item_id)
        {
            $item = $order->get_item($item_id);

            $items_data = $item->get_data();

            $product = $item->get_product();


            $refund_qty = $order->get_qty_refunded_for_item( $items_data['id'] );
            if($refund_qty < 0)
            {
                $refund_qty = 0 - $refund_qty;
            }
            $refund_total = $order->get_total_refunded_for_item($items_data['id']);

            $items_data['options'] = array();
            $subtotal = $items_data['subtotal'];
            $total = $items_data['total'];

            $total_tax = $items_data['total_tax'];

            $discount = ($subtotal   - $total) > 0 ? ($subtotal   - $total) : 0;



            $item_price = ($subtotal /$items_data['quantity']);

            $item_formatted_data = array(
                'id' => $items_data['id'],
                'name' => $items_data['name'],
                'sub_name' => '',
                'price' =>  $item_price,
                'price_incl_tax' =>  $item_price, //
                'product_id' =>  $items_data['product_id'],
                'final_price' =>  $item_price,
                'final_price_incl_tax' =>  $item_price, //
                'options' => array(),
                'bundles' =>  array(),
                'variations' => array(),
                'discount_amount' =>  $discount,
                'discount_type' => 'fixed',
                'final_discount_amount' =>  $discount,
                'qty' =>  $items_data['quantity'],
                'refund_qty' =>  $refund_qty,
                'exchange_qty' =>  0,
                'tax_amount' =>  $total_tax > 0 ? ($total_tax / $items_data['quantity']) : 0 ,
                'refund_total' =>  $refund_total,
                'total_tax'=> $total_tax,
                'total'=>  $items_data['total'],
                'total_incl_tax'=>  $items_data['total'], //
                'product'=> array(),
                'option_pass' =>  true,
                'note' => '',
                'seller_id' => 0,
                'seller_name' => ''
            );
            if(($item_formatted_data['qty'] - $item_formatted_data['refund_qty']) > 0 )
            {
                $qty_allow_refund = true;
            }
            $items[] = $item_formatted_data;
        }
        $user_id = $order->get_meta('_op_sale_by_person_id');
        $sale_person_name = '';
        if($user_id)
        {
            $userdata = get_user_by('id', $user_id);
            if($userdata)
            {
                $sale_person_name = $userdata->data->display_name;
            }

        }
        if(!$sale_person_name && !$_pos_order_id)
        {
            $sale_person_name = __('Done via website','openpos');
        }
        $sub_total = $order->get_subtotal();
        $shipping_cost = (float)$order->get_shipping_total();
        $final_discount_amount = $order->get_discount_total();
        $tax_totals = $order->get_tax_totals();
        $tax_amount = 0;
        foreach($tax_totals as $tax)
        {
            $tax_amount += $tax->amount;
        }

        $allow_pickup = $this->allowPickup($order_id);
        $allow_refund = $this->allowRefundOrder($order_id);
        if($grand_total <= 0)
        {
            $allow_refund = false;
        }
        $payments = array();
        if($_pos_order_id)
        {
            $payments = $order->get_meta('_op_payment_methods');
        }else{
            $method_title = $order->get_payment_method_title();
            $method_paid = $order->is_paid() ? $grand_total : 0;

            $payments[] = array(
                'name' => $method_title,
                'paid' => $method_paid,
                'return' => 0,
                'ref' => '',
            );
        }
        if($allow_refund && !$qty_allow_refund)
        {
            $allow_refund = false;
        }
        if($payment_status != 'paid')
        {
            $allow_refund = false;
            $allow_pickup = false;
        }
        $continue_pay_url = $order->get_checkout_payment_url(false);
        $result = array(
            'order_number' => $order_number,
            'order_id' => $order_id,
            'system_order_id' => $order_id,
            'sale_person_name' => $sale_person_name,
            'payment_method' => $payments, //ref , paid , return
            'pos_order_id' => $_pos_order_id,
            'customer' => $customer_data,
            'items' => $items,
            'sub_total' => $sub_total,
            'sub_total_incl_tax' => $sub_total, //
            'shipping_cost' => $shipping_cost,
            'final_discount_amount' => (float)$final_discount_amount,
            'discount_amount' => (float)$final_discount_amount,
            'tax_amount' => $tax_amount,
            'grand_total' => $grand_total,
            'created_at' => wc_format_datetime($order->get_date_created()),
            'checkout_url' => $continue_pay_url,
            'allow_refund' => $allow_refund,
            'allow_pickup' => $allow_pickup,
            'payment_status' => $payment_status,
            'custom_tax_rate' => '',
            'custom_tax_rates' => array(),
            'note' => '',
            'state' => ($payment_status == 'paid') ? 'completed' : 'pending_payment',
        );
        return apply_filters('op_get_online_order_data',$result);
    }
    public function allowRefundOrder($order_id){
        $allow_refund_duration = $this->settings_api->get_option('pos_allow_refund','openpos_general');
        if($allow_refund_duration == 'yes')
        {
            return true;
        }
        if($allow_refund_duration == 'no')
        {
            return false;
        }
        $refund_duration = $this->settings_api->get_option('pos_refund_duration','openpos_general');
        $post = get_post($order_id);
        $order = wc_get_order($order_id);
        $_pos_order_id = get_post_meta($order_id,'_pos_order_id',true);
        if(!$_pos_order_id)
        {
            return false;
        }

        $created = date_create($post->post_date)->getTimestamp();
        $today = time();
        $diff_time = $today - $created;
        $refund_duration = (float)$refund_duration;
        return ($diff_time < (86400 * $refund_duration));
    }
    public function allowPickup($order_id){
        $order = wc_get_order($order_id);
        $status = $order->get_status();
        $allow = false;
        if($status == 'processing')
        {
            $allow =  true;
        }
        return apply_filters('op_allow_order_pickup',$allow,$order_id);
    }
    public function inclTaxMode(){
        $pos_tax_class = $this->settings_api->get_option('pos_tax_class','openpos_general');

        return ( $pos_tax_class == 'op_productax'  && 'yes' === get_option( 'woocommerce_prices_include_tax' ) )  ? 'yes' : 'no';
    }
    public function getCustomItemTax(){
        $result = array();
        $pos_custom_item = $this->settings_api->get_option('pos_allow_custom_item','openpos_pos');
        $pos_custom_tax_class = $this->settings_api->get_option('pos_custom_item_tax_class','openpos_pos');
        $pos_custom_tax_rate = $this->settings_api->get_option('pos_custom_item_tax_rate','openpos_pos');
        $pos_tax_class = $this->settings_api->get_option('pos_tax_class','openpos_general');
        if($pos_custom_item == 'yes' && $pos_tax_class != 'op_notax')
        {
            if($pos_custom_tax_class != 'op_notax' && $pos_custom_tax_rate)
            {
                $tax_rates = $this->getTaxRates( $pos_custom_tax_class );

                if(!empty($tax_rates))
                {
                    $rate_id = $pos_custom_tax_rate;
                    if(isset($tax_rates[$rate_id]))
                    {
                        $rate = $tax_rates[$rate_id];
                        $tax_rate = array();
                        $tax_rate['code'] = $pos_custom_tax_class ? $pos_custom_tax_class.'_'.$rate_id : 'standard'.'_'.$rate_id;
                        $tax_rate['rate_id'] = $rate_id;
                        if($rate['label'])
                        {
                            $tax_rate['label'] = $rate['label'];
                        }
                        if(isset($rate['shipping']))
                        {
                            $tax_rate['shipping'] = $rate['shipping'];
                        }
                        if(isset($rate['compound']))
                        {
                            $tax_rate['compound'] = $rate['compound'];
                        }
                        if(isset($rate['rate']))
                        {
                            $tax_rate['rate'] = $rate['rate'];
                        }

                        $result[] = $tax_rate;
                    }

                }

            }
        }

        return apply_filters( 'op_custom_item_tax',$result );
    }
    public function getAllUserRoles(){
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        $roles =  array_keys($all_roles);
        return apply_filters('op_customer_roles',$roles);
    }

    public function add_meta_boxes(){
        global $post;
        $source = get_post_meta($post->ID,'_op_order_source',true);
        if($source == 'openpos')
        {
            add_meta_box( 'look-openpos-order-setting',__('POS Information','openpos'), array($this,'add_order_boxes'), 'shop_order', 'side', 'default' );
        }

    }
    public function add_order_boxes(){
        global $post;
        $order = wc_get_order($post->ID);
        $pos_order = get_post_meta($post->ID,'_op_order',true);
        ?>

        <div class="openpos-order-meta-setting">
            <?php if($pos_order):  ?>
            <div style="width: 100%; float: left;">
                <a href="<?php echo admin_url('admin-ajax.php?action=print_receipt&id='.(int)$post->ID); ?>" target="_blank" style="background: transparent;padding: 0; float: right;border: none;"><image style="width: 28px;" src="<?php echo OPENPOS_URL.'/assets/images/print.png'; ?>" /></a>
            </div>
            <?php endif; ?>
            <?php
                $this->order_pos_payment($order);
            ?>

        </div>

        <?php
    }

}
