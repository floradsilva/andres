<?php
if(!class_exists('OP_Report'))
{
    class OP_Report{
        public $_zpost_type = 'op_z_report';
        public $_core;
        public function __construct()
        {
            global $OPENPOS_CORE;
            $this->_core = $OPENPOS_CORE;
            $this->init();
        }
        function init(){
            
            add_filter('op_report_result',array($this,'op_report_result'),10,3);
        }
        public function add_z_report($data){
            
            if(!isset($data['login_time']))
            {
                    $data['login_time'] = strtotime($data['session_data']['logged_time']);
                    $data['login_time'] = $data['login_time'] * 1000;
            }
            if(!isset($data['logout_time']))
            {
                $data['logout_time'] = time();
                $data['logout_time'] = $data['logout_time'] * 1000;
            }

            $login_time = round($data['login_time']/1000);
            $logout_time = round($data['logout_time']/1000);

            $open_balance = $data['open_balance'];
            $close_balance = $data['close_balance'];
            $sale_total = $data['sale_total'];
            $custom_transaction_total = $data['custom_transaction_total'];
            $item_discount_total = $data['item_discount_total'];
            $cart_discount_total = $data['cart_discount_total'];
            $tax = $data['tax'];

            $cashier_name = $data['session_data']['name'];
            $login_cashdrawer_id = $data['session_data']['login_cashdrawer_id'];
            $login_warehouse_id = $data['session_data']['login_warehouse_id'];
            $cash_drawers = $data['session_data']['cash_drawers'];
            
            $session_id = $data['session_data']['session'];
            $cashdrawer_name = $login_cashdrawer_id;
            foreach($cash_drawers as $c)
            {
                if($c['id'] == $login_cashdrawer_id)
                {
                    $cashdrawer_name = $c['name'];                }
            }
            $WC_DateTime_login = $this->formatTimeStamp($login_time);
            $login_date_str = $WC_DateTime_login->date_i18n( 'd/m/Y h:i:s');
            $WC_DateTime_logout = $this->formatTimeStamp($logout_time);
            $logout_date_str = $WC_DateTime_logout->date_i18n( 'd/m/Y h:i:s');
            $user_id = $data['cashier_user_id'];

            $title = $cashier_name.'@'.$cashdrawer_name;
            
            $id = wp_insert_post(
                array(
                    'post_title'=> $title,
                    'post_content'=> json_encode($data),
                    'post_type'=> $this->_zpost_type,
                    'post_author'=> $user_id,
                    'post_status'  => 'publish'
                ));
            if($id)
            {
                add_post_meta($id,'login_time',$login_time);
                add_post_meta($id,'logout_time',$logout_time);

                add_post_meta($id,'login_date',$login_date_str);
                add_post_meta($id,'logout_date',$logout_date_str);

                add_post_meta($id,'login_cashdrawer_id',$login_cashdrawer_id);
                add_post_meta($id,'login_warehouse_id',$login_warehouse_id);
                add_post_meta($id,'session_id',$session_id);

                add_post_meta($id,'open_balance',$open_balance);
                add_post_meta($id,'close_balance',$close_balance);
                add_post_meta($id,'sale_total',$sale_total);
                add_post_meta($id,'custom_transaction_total',$custom_transaction_total);

                add_post_meta($id,'item_discount_total',$item_discount_total);
                add_post_meta($id,'cart_discount_total',$cart_discount_total);
                add_post_meta($id,'tax',$tax);
            }
        }
        function formatTimeStamp($timestamp){
            $datetime = new WC_DateTime( "@{$timestamp}", new DateTimeZone( 'UTC' ) );
            // Set local timezone or offset.
            if ( get_option( 'timezone_string' ) ) {
                $datetime->setTimezone( new DateTimeZone( wc_timezone_string() ) );
            } else {
                $datetime->set_utc_offset( wc_timezone_offset() );
            }
            return $datetime;
        }
        function getZReportPosts($from_time,$to_time){
            $meta_query_args = array(
                'post_type'  => $this->_zpost_type,
                'number' => -1,
                'post_status'      => 'publish',
                'meta_query' => array(
                    array(
                        'relation' => 'AND',
                        array(
                            'key'     => 'login_time',
                            'value'   => $from_time,
                            'compare' => '>'
                        ),
                        array(
                            'key'     => 'logout_time',
                            'value'   => $to_time,
                            'compare' => '<'
                        )
                    )
                ),
                
            );
            $post_query = new  WP_Query( $meta_query_args );
            
            return $post_query->posts;
            
        }
        function op_report_result($result,$ranges,$report_type){
            if($report_type == 'z_report')
            {
                $report_outlet_id =  isset($_REQUEST['report_outlet']) ? $_REQUEST['report_outlet'] : 0;
                $report_register_id =  isset($_REQUEST['report_register']) ? $_REQUEST['report_register'] : 0;
              
                $from = $ranges['start'];
                $to = $ranges['end'];
               
                $WC_DateTime_start = wc_string_to_datetime( $from );
                $start_timestamp = $WC_DateTime_start->getTimestamp() ;

                $WC_DateTime_end = wc_string_to_datetime( $to );
                $end_timestamp = $WC_DateTime_end->getTimestamp() ;

                $posts = $this->getZReportPosts($start_timestamp,$end_timestamp);
                
                $result['table_data'] = array();
                $result['orders_export_data'] = array();
                $orders_export_data = array();
                $orders_export_data[] = array(
                    __('Session','openpos'),
                    __('Clock IN','openpos'),
                    __('Clock OUT','openpos'),
                    __('Open Cash','openpos'),
                    __('Close Cash','openpos'),
                    __('Total Sales','openpos'),
                    __('Total Custom Transaction','openpos'),
                    __('Total Item Discount','openpos'),
                    __('Total Cart Discount','openpos'),
                );
                foreach($posts as $p)
                {
                    $login_date = get_post_meta($p->ID,'login_date',true);
                    $logout_date = get_post_meta($p->ID,'logout_date',true);
                    $open_balance = get_post_meta($p->ID,'open_balance',true);
                    $close_balance =  get_post_meta($p->ID,'close_balance',true);
                    $sale_total = get_post_meta($p->ID,'sale_total',true);
                    $custom_transaction_total = get_post_meta($p->ID,'custom_transaction_total',true);
                    $item_discount_total = get_post_meta($p->ID,'item_discount_total',true);
                    $cart_discount_total = get_post_meta($p->ID,'cart_discount_total',true);

                    $login_cashdrawer_id = (int)get_post_meta($p->ID,'login_cashdrawer_id',true);
                    $login_warehouse_id = (int)get_post_meta($p->ID,'login_warehouse_id',true);
                    
                    if(!$sale_total)
                    {
                        $sale_total = 0;
                    }

                    if($report_outlet_id >= 0 &&  $report_outlet_id != $login_warehouse_id)
                    {
                        continue;
                    }

                    if($report_register_id > 0 && $report_register_id != $login_cashdrawer_id)
                    {
                        continue;
                    }

                    
                    $orders_export_data[] = array(
                        $p->post_title,
                        $login_date,
                        $logout_date,
                        (float)$open_balance,
                        (float)$close_balance,
                        (float)$sale_total,
                        (float)$custom_transaction_total,
                        (float)$item_discount_total,
                        (float)$cart_discount_total
                    );

                    $tmp = array(
                        ''.$p->ID,
                        $p->post_title,
                        $login_date,
                        $logout_date,
                        (float)$open_balance,
                        (float)$close_balance,
                        (float)$sale_total,
                        //'print',
                    );
                    $result['table_data'][] = $tmp; 
                }
                $result['orders_export_data']  =  $orders_export_data;
                
            }
            
            return $result;
           
        }
    }
}
?>