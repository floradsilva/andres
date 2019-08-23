<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 10/21/18
 * Time: 12:05
 */
global $op_in_kitchen_screen;
$op_in_kitchen_screen = true;
$base_dir = dirname(dirname(dirname(dirname(__DIR__))));
require_once ($base_dir.'/wp-load.php');
global $op_table;
$id = esc_attr($_GET['id']);

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_ready')
{
    $id_str = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
    $tmp = explode('-',$id_str);
    if(count($tmp) == 2)
    {
        $table_id = end($tmp);
        $item_id = $tmp[0];
        $table_data = $op_table->bill_screen_data($table_id);
        $ver = $table_data['ver'];
        $online_ver = $table_data['online_ver'];
        if($online_ver > $ver)
        {
            $ver = $online_ver;
        }
        $table_data['ver'] = $ver + 10;
        $table_data['online_ver'] = $ver + 10;
        $table_data['online_ver'] = $ver + 10;
        $items = array();
        foreach($table_data['items'] as $item)
        {
            if($item['id'] == $item_id)
            {
                $item['done'] = 'ready';
            }
            $items[] = $item;
        }
        $table_data['items'] = $items;
        $op_table->update_table_bill_screen($table_id,$table_data);

    }



    echo json_encode(array());exit;
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_data')
{
    $warehouse_id = isset($_REQUEST['warehouse']) ? $_REQUEST['warehouse'] : -1;
    $result = array();
    $result_formated = array();
    if($warehouse_id >= 0)
    {
        $tables = $op_table->tables((int)$warehouse_id);
        foreach($tables as $table)
        {

            $table_data = $op_table->bill_screen_data($table['id']);
            if(isset($table_data['parent']) && $table_data['parent'] == 0 && isset($table_data['items'])  && count($table_data['items']) > 0)
            {
                $items = $table_data['items'];
                foreach($items as $item)
                {

                    if(isset($item['done']) && ($item['done'] == 'done' || $item['done'] == 'done_all'))
                    {

                        continue;
                    }
                    $id = (int)$item['id'];
                    $timestamp = (int)($item['id'] / 1000);
                    $timestamp += wc_timezone_offset();

                    $order_time = '--:--';
                    if($timestamp)
                    {
                        $order_time = date('h:i',$timestamp);
                    }
                    $tmp = array(
                        'id' => $id.'-'.$table['id'],
                        'priority' => 1,
                        'item' => $item['name'],
                        'qty' => $item['qty'],
                        'table' => $table['name'],
                        'order_time' => $order_time,
                        'note' => $item['sub_name'],
                        'dining' => isset($item['dining']) ? $item['dining'] : '',
                        'done' => isset($item['done']) ? $item['done'] : ''
                    );
                    $result[$id] = $tmp;
                }
            }
        }
    }
    if(!empty($result))
    {
        $i = 1;

        foreach($result as $r)
        {
            $r['priority'] = $i;
            $result_formated[] = $r;
            $i++;
        }

    }
    echo json_encode($result_formated);exit;

}


?>
<html lang="en" style="height: calc(100% - 0px);">
<head>
    <meta charset="utf-8">
    <title>Kitchen Screen</title>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script>
        var data_url = '<?php echo OPENPOS_URL.'/kitchen/index.php' ?>';
        var data_warehouse_id = '<?php echo $id; ?>';
        var data_template= '<tr><td class="text-center"><%= priority %></td><td class="item-name"><span class="dining <%- dining %>"><%- dining %></span><%= item %><p class="item-note"><%- note %></p></td><td class="text-center"><%= qty %></td><td><%= order_time %></td><td><%= table %></td><td class="text-center"><% if (done != "ready" && done != "done" ) { %> <a data-id="<%- id %>" href="javascript:void(0);" class="is_cook_ready"> <span class="glyphicon glyphicon-bell" aria-hidden="true"></span> </a> <% } else { %> <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <% } %></td></tr>';
    </script>
    <?php
    $handes = array(
        'openpos.kitchen.style'
    );
    wp_print_styles($handes);
    ?>

</head>
<body>
<div  id="bill-content">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th>Order Time</th>
                <th>Table</th>
                <th class="text-center">Ready ?</th>
            </tr>
        </thead>
        <tbody id="kitchen-table-body">

        </tbody>
    </table>
</div>

<?php
$handes = array(
    'openpos.kitchen.script'
);
wp_print_scripts($handes);
?>
<style  type="text/css">
    .item-name{
        position: relative;
    }
    span.dining{
       display: none;
    }
    span.dining.takeaway{
        display: block;
        position: absolute;
        top: 2px;
        right: 2px;
        border:none;
        padding: 2px 10px;
        font-size: 12px;
        color: #fff;
        background: #005724;
        border-radius: 10px;
    }
</style>
</body>
</html>