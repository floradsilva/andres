<?php
if(!class_exists('OP_Table'))
{
    class OP_Table{
        public $_post_type = '_op_table';
        public $_warehouse_meta_key = '_op_warehouse';
        public $_position_meta_key = '_op_table_position';
        public $_filesystem;
        public $_bill_data_path;
        public $_base_path;
        public function __construct()
        {
            if(!class_exists('WP_Filesystem_Direct'))
            {
                require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
                require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
            }
            $this->_filesystem = new WP_Filesystem_Direct(false);
            $this->_base_path =  WP_CONTENT_DIR.'/uploads/openpos';
            $this->_bill_data_path =  $this->_base_path.'/tables';
            $this->init();
        }
        function init(){
            // create openpos data directory
            if(!file_exists($this->_base_path))
            {
                $this->_filesystem->mkdir($this->_base_path);
            }

            if(!file_exists($this->_bill_data_path))
            {
                $this->_filesystem->mkdir($this->_bill_data_path);
            }
            //upload all table with no position
            $posts = get_posts([
                'post_type' => $this->_post_type,
                'numberposts' => -1,
                'meta_query' => array(
                    array(
                        'key' => $this->_position_meta_key,
                        'compare' => 'NOT EXISTS' // this should work...
                    ),
                )
            ]);
            foreach ($posts as $post)
            {
                $post_id = $post->ID;
                update_post_meta($post_id,$this->_position_meta_key,0);
            }

        }
        public function tables($warehouse_id = -1 ){
            $result = array();


            if($warehouse_id >= 0)
            {
                $posts = get_posts([
                    'post_type' => $this->_post_type,
                    'post_status' => array('publish'),
                    'numberposts' => -1,
                    'order'     => 'ASC',
                    'meta_key' => $this->_position_meta_key,
                    'orderby'   => 'meta_value_num'
                ]);

                foreach($posts as $p)
                {
                    $tmp = $this->get($p->ID);
                    if($tmp['warehouse'] == $warehouse_id)
                    {
                        $result[] = $tmp;
                    }

                }
            }else{
                $posts = get_posts([
                    'post_type' => $this->_post_type,
                    'post_status' => array('publish','draft'),
                    'numberposts' => -1,
                    'order'     => 'ASC',
                    'meta_key' => $this->_position_meta_key,
                    'orderby'   => 'meta_value_num',
                ]);

                foreach($posts as $p)
                {
                    $result[] = $this->get($p->ID);
                }
            }

            return $result;
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
            $warehouse_id = isset($params['warehouse']) ? $params['warehouse'] : 0;
            $position = isset($params['position']) ? (int)$params['position'] : 0;
            $args = array(
                'ID' => $id,
                'post_title' => $params['name'],
                'post_type' => $this->_post_type,
                'post_status' => $params['status'],
                'post_parent' => $warehouse_id
            );
            $post_id = wp_insert_post($args);
            if(!is_wp_error($post_id)){


                update_post_meta($post_id,$this->_warehouse_meta_key,$warehouse_id);
                update_post_meta($post_id,$this->_position_meta_key,$position);
                return $post_id;
            }else{
                //there was an error in the post insertion,
                throw new Exception($post_id->get_error_message()) ;
            }
        }
        public function get($id)
        {
            $post = get_post($id);
            if(!$post)
            {
                return array();
            }
            if($post->post_type != $this->_post_type)
            {
                return array();
            }
            $name = $post->post_title;
            $warehouse = get_post_meta($id,$this->_warehouse_meta_key,true);
            $position = get_post_meta($id,$this->_position_meta_key,true);


            $status = $post->post_status;
            $result = array(
                'id' => $id,
                'name' => $name,
                'warehouse' => $warehouse,
                'position' => (int)$position,
                'status' => $status
            );
            return $result;
        }

        public function update_bill_screen($tables_data){

            if(!empty($tables_data))
            {
                foreach($tables_data as $table_key => $table_data)
                {
                    $table_id = str_replace('desk-','',$table_key);
                    $current_data = $this->bill_screen_data($table_id);
                    $allow_update = true;
                    if(isset($current_data['ver']) && isset($table_data['ver']))
                    {
                        if($current_data['ver'] >= $table_data['ver']  )
                        {
                            $allow_update = false;
                        }

                    }
                    if($allow_update)
                    {
                        $register_file = $this->bill_screen_file_path($table_id);
                        if(file_exists($register_file))
                        {
                            $this->_filesystem->delete($register_file);
                        }
                        $file_mode = '777';
                        $this->_filesystem->put_contents(
                            $register_file,
                            json_encode($table_data),
                            apply_filters('op_file_mode',$file_mode) // predefined mode settings for WP files
                        );
                    }

                }

            }
        }
        public function update_table_bill_screen($table_id,$table_data){
            $register_file = $this->bill_screen_file_path($table_id);
            if(file_exists($register_file))
            {
                $this->_filesystem->delete($register_file);
            }
            $file_mode = '777';
            $this->_filesystem->put_contents(
                $register_file,
                json_encode($table_data),
                apply_filters('op_file_mode',$file_mode) // predefined mode settings for WP files
            );
        }
        public function bill_screen_file_path($table_id)
        {
            return $this->_bill_data_path.'/'.$table_id.'.json';
        }
        public function bill_screen_file_url($table_id)
        {
            $upload_dir = wp_upload_dir();
            $url = $upload_dir['baseurl'];
            $url = ltrim($url,'/');
            return $url.'/openpos/tables/'.$table_id.'.json';
        }
        public function bill_screen_data($table_id)
        {
            $file_path = $this->bill_screen_file_path($table_id);
            $data = $this->_filesystem->get_contents($file_path);
            $result = array();
            if($data)
            {
                $result = json_decode($data,true);
            }

            return $result;
        }
        public function tables_version(){
            $result = array();
            if ($handle = opendir( $this->_bill_data_path)) {

                while (false !== ($entry = readdir($handle))) {

                    if ($entry != "." && $entry != "..") {

                        if(strpos($entry,'.json') > 0)
                        {
                            $table_id = str_replace('.json','',$entry);
                            $file_path = $this->_bill_data_path.'/'.$entry;
                            $data = $this->_filesystem->get_contents($file_path);
                            if($data)
                            {
                                $result_table = json_decode($data,true);
                                $version = isset($result_table['ver']) ? $result_table['ver'] : 0;
                                $result[$table_id] = $version;
                            }
                        }
                    }
                }
                closedir($handle);
            }
            return $result;
        }
        public function ready_dishes(){
            $result = array();
            if ($handle = opendir( $this->_bill_data_path)) {

                while (false !== ($entry = readdir($handle))) {

                    if ($entry != "." && $entry != "..") {

                        if(strpos($entry,'.json') > 0)
                        {
                            $table_id = str_replace('.json','',$entry);
                            $file_path = $this->_bill_data_path.'/'.$entry;
                            $data = $this->_filesystem->get_contents($file_path);
                            if($data)
                            {
                                $result_table = json_decode($data,true);
                                $items = isset($result_table['items']) ? $result_table['items'] : array();
                                if(!empty($items))
                                {
                                    $table = isset($result_table['desk']) ? $result_table['desk'] : [];
                                    $table_name = isset($table['name']) ? $table['name'] : '';
                                    $table_id = isset($table['id']) ? $table['id'] : 0;
                                    foreach ($items as $_item)
                                    {
                                        if(isset($_item['done']) && $_item['done'] == 'ready')
                                        {
                                            $result[] = array(
                                                'table_id' => $table_id,
                                                'table_name' => $table_name,
                                                'item_name' => $_item['qty'].' x '.$_item['name']
                                            );
                                        }
                                    }

                                }

                            }
                        }
                    }
                }
                closedir($handle);
            }
            return $result;
        }
    }
}
?>