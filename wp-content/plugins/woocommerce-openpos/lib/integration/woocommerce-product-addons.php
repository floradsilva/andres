<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 10/19/18
 * Time: 17:57
 */
class OP_Woocommerce_Product_Addons{

    public function init(){
        if(file_exists( WP_CONTENT_DIR.'/plugins/woocommerce-product-addons/woocommerce-product-addons.php'))
        {
            $this->wc_addons();
        }

    }
    // woocommerce global addons
    public function wc_addons(){
        if(is_plugin_active( 'woocommerce-product-addons/woocommerce-product-addons.php' ))
        {
            if(!class_exists('WC_Product_Addons') )
            {
                require_once WP_CONTENT_DIR.'/plugins/woocommerce-product-addons/woocommerce-product-addons.php';
            }
            add_filter('op_product_data',[$this,'wc_product_addons'],10,2);
        }

    }
    public function wc_product_addons($response_data,$_product){
        $options = array();
        $addons = array();
        $product_id = $_product->ID;
        $product = wc_get_product($_product->ID);

        $type = $product->get_type();
        if($type == 'variation')
        {
            $product_id = $product->get_parent_id();

        }
        if(function_exists('get_product_addons'))
        {
            $addons = get_product_addons($product_id);
        }else{
            $addons = WC_Product_Addons_Helper::get_product_addons( $product_id, false, false, true );
        }

        $display_mode = wc_prices_include_tax();


        foreach($addons as $a)
        {
            if(in_array($a['type'], array('radiobutton','select','checkbox','multiple_choice','custom_text')))
            {
                if($a['type'] == 'custom_text')
                {
                    $a['type'] = 'text';
                }
                if($a['type'] == 'multiple_choice')
                {
                    if($a['display'] == 'images')
                    {
                        $a['type'] =  'radiobutton';
                    }else{
                        $a['type'] = $a['display'];
                    }
                }

                if($a['type'] == 'radiobutton')
                {
                    $a['type'] = 'radio';

                }

                $radio = array(
                    'label' => $a['name'],
                    'option_id' => isset($a['field-name']) ? $a['field-name']: $a['field_name'],
                    'type' => $a['type'],
                    'require' => $a['required'],
                    'options' => array()
                );
                foreach($a['options'] as $key => $a_option)
                {
                    $a_price = $a_option['price'];
                    if($a_price)
                    {
                        if( $display_mode )
                        {
                            $product = wc_get_product($_product->ID);
                            $tax_rates = WC_Tax::get_rates( $product->get_tax_class() );

                            if(!empty($tax_rates))
                            {
                                $tax_amount = array_sum(@WC_Tax::calc_tax( $a_price, $tax_rates, true ));
                                $a_price -= $tax_amount;

                            }
                        }
                    }else{
                        $a_price = 0;
                    }

                    $tmp = array(
                        'value_id' => $a_option['label'],
                        'label' => $a_option['label'],
                        'cost' => $a_price,
                    );
                    $radio['options'][] = $tmp;
                }
                $response_data['options'][] = $radio;
            }

        }
//            $radio = array(
//                'label' => "Radio Label",
//                'option_id' => 1,
//                'type' => 'radio',
//                'require' => true,
//                'options' => array(
//                    ['value_id' => 1, 'label' => 'radio value 1','cost' => 5],
//                    ['value_id' => 4, 'label' => 'radio value 2','cost' => 6],
//                    ['value_id' => 7, 'label' => 'radio value 3','cost' => 7],
//                )
//            );
//            $options[]= $radio;
//            $response_data['options'] = $options;
        return $response_data;
    }
}