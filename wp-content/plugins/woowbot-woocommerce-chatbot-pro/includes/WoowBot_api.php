<?php
if( !defined( 'WPINC' ) ) {
    die();
}
if( !class_exists( 'WoowBot_api' ) ) {
    final class WoowBot_api extends WP_REST_Controller  {
        public function __construct() {

        }
        public function register_routes() {
            $namespace='woowbot/v1';
            register_rest_route($namespace, '/config' , array(
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'woowbot_config'),
                    'args' => array(),
                ),
            ));
            register_rest_route($namespace, '/category' , array(
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'product_category'),
                    'args' => array(),
                ),
            ));
            register_rest_route($namespace, '/category-products' , array(
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'category_products'),
                    'args' => array(),
                ),
            ));
            register_rest_route($namespace, '/search' , array(
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'product_search'),
                    'args' => array(),
                ),
            ));
            register_rest_route($namespace, '/load-more' , array(
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array($this, 'load_more'),
                    'args' => array(),
                ),
            ));
        }

        public function product_category($request){
            $params = $request->get_params();
            $result=qcld_woochatbot_produt_category_api();
            if($result['category_number'] >0){
                return new WP_REST_Response($result, 200);
            }else{
                return new WP_Error('no-categories', __('No Categories found.', 'woochatbot'), array('status' => 400));
            }

        }
        public function category_products($request){
            $params = $request->get_params();
            if(isset($params['category']) && $params['category']!=""){
                $result=qcld_woochatbot_category_products_api($params['category']);
                return new WP_REST_Response($result, 200);
            }else{
                return new WP_Error('missing-argument', __('No Argurments found.', 'woochatbot'), array('status' => 400));
            }
        }
        public function product_search($request){
            $params = $request->get_params();

            if(isset($params['keys']) && $params['keys']!=""){
                $result=qcld_woochatbot_product_search_api($params['keys']);
                return new WP_REST_Response($result, 200);
            }else{
                return new WP_Error('missing-argument', __('No Argurments found.', 'woochatbot'), array('status' => 400));
            }

        }
        public function load_more($request){
            $params = $request->get_params();
            $offset=isset($params['offset'])  ? $params['offset']     : null;
            $search_type=isset($params['search_type'])  ? $params['search_type']     : null;
            $search_term=isset($params['search_term'])  ? $params['search_term']     : null;
            if($offset!=null && $search_type!=null && $search_term!=null){
                $result=qcld_woochatbot_load_more_api($offset,$search_type,$search_term);
                return new WP_REST_Response($result, 200);
            }else{
                return new WP_Error('missing-argument', __('No Argurments found.', 'woochatbot'), array('status' => 400));
            }

        }
        public function woowbot_config(){
            $result=qcld_woochatbot_config_api();
            return new WP_REST_Response($result, 200);
        }
    }
}

function register_woowbot_rest_route(){
    if ( class_exists('WP_REST_Controller') ) {
        //if (get_option('qcld_woochat_enable_rest_api') == 1 ) {
        if (1 == 1 ) {
            $api = new WoowBot_api();
            $api->register_routes();
        }
    }
}
add_action( 'rest_api_init', 'register_woowbot_rest_route');