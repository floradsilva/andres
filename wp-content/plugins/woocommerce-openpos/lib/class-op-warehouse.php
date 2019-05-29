<?php
if(!class_exists('OP_Warehouse'))
{
    class OP_Warehouse{
        public $_post_type = '_op_warehouse';
        public $_meta_field = array();
        public $_meta_product_qty = '_op_qty_warehouse';

        public function __construct()
        {
            $this->_meta_field = array(
                'address' => '_op_address',
                'city' => '_op_city',
                'postal_code' => '_op_postal_code',
                'country' => '_op_country',
                'phone' => '_op_phone',
                'email' => '_op_email',
                'facebook' => '_op_facebook'
            );
        }
        public function warehouses(){
            $result = array();
            $result[] = array(
                'id' => 0,
                'name' => __('Default online store','openpos'),
                'address' => '',
                'city' => '',
                'postal_code' => '',
                'country' => '',
                'phone' => '',
                'email' => '',
                'facebook' => '',
                'status' => 'publish',
                'total_qty' => ''
            );
            $posts = get_posts([
                'post_type' => $this->_post_type,
                'post_status' => array('publish','draft'),
                'numberposts' => -1
            ]);
            foreach($posts as $p)
            {
                $result[] = $this->get($p->ID);
            }
            return $result;
        }
        public function get($id = 0){
            if($id == 0)
            {
                return array(
                    'id' => 0,
                    'name' => __('Default online store','openpos'),
                    'address' => '',
                    'city' => '',
                    'postal_code' => '',
                    'country' => '',
                    'phone' => '',
                    'email' => '',
                    'facebook' => '',
                    'status' => 'publish',
                    'total_qty' => 0
                );
            }
            $post = get_post($id);
            $name = $post->post_title;

            $result = array(
                'id' => $id,
                'name' => $name,
                'total_qty' => 100,
                'status' => $post->post_status
            );
            foreach($this->_meta_field as $field => $meta_key)
            {
                $result[$field] = get_post_meta($id,$meta_key,true);
            }
            return apply_filters('op_warehouse_get_data',$result,$this);

        }
        public function delete($id){
            $post = get_post($id);
            if($post->post_type == $this->_post_type)
            {
                wp_trash_post( $id  );
            }
        }
        public function save($params){
            $id  = 0;
            if(isset($params['id']) && $params['id'] > 0)
            {
                $id = $params['id'];
            }
            $args = array(
                'ID' => $id,
                'post_title' => $params['name'],
                'post_type' => $this->_post_type,
                'post_status' => $params['status'],
                'post_parent' => 0
            );
            $post_id = wp_insert_post($args);
            if(!is_wp_error($post_id)){

                foreach($this->_meta_field as $field => $meta_key)
                {
                    if($meta_value = $params[$field])
                    {
                        update_post_meta($post_id,$meta_key,$meta_value);
                    }
                }
                return $post_id;
            }else{
                //there was an error in the post insertion,
                throw new Exception($post_id->get_error_message()) ;
            }
        }
        public function set_qty($warehouse_id = 0,$product_id,$qty = 0){
            global $OPENPOS_CORE;
            $OPENPOS_CORE->addProductChange($product_id,$warehouse_id);
            $qty = (float)$qty;
            if($warehouse_id > 0)
            {
                $meta_key = $this->_meta_product_qty.'_'.$warehouse_id;
                update_post_meta($product_id,$meta_key,$qty);

               // update_option('_openpos_product_version_'.$warehouse_id,time());
            }else{

            }

        }
        public function get_qty($warehouse_id = 0,$product_id){
            $qty = 0;
            if($warehouse_id > 0)
            {
                $meta_key = $this->_meta_product_qty.'_'.$warehouse_id;
                $qty = get_post_meta($product_id,$meta_key,true);
                if(!$qty)
                {
                    $qty = 0;
                }
            }else{
                $product = wc_get_product($product_id);
                $qty = $product->get_stock_quantity();
            }
            return 1*$qty;
        }
        public function get_order_meta_key(){
            $option_key = '_pos_order_warehouse';
            return $option_key;
        }
        public function get_transaction_meta_key(){
            $option_key = '_pos_transaction_warehouse';
            return $option_key;
        }
    }
}
?>