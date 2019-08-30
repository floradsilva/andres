<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 12/4/16
 * Time: 23:40
 */

?>
<script type="text/javascript">
    (function($) {
        google.charts.setOnLoadCallback(drawTable);
        google.charts.setOnLoadCallback(drawChart);
        var dataRows = [];

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', '<?php echo __('#','openpos'); ?>');
            data.addColumn('string', '<?php echo __('Customer','openpos'); ?>');
            data.addColumn('string', '<?php echo __('Grand Total','openpos'); ?>');
            data.addColumn('string', '<?php echo __('Sale By','openpos'); ?>');
            data.addColumn('string', '<?php echo __('Created At','openpos'); ?>');
            data.addColumn('string', '<?php echo __('View','openpos'); ?>');

            data.addRows(dataRows);
            var table = new google.visualization.Table(document.getElementById('table_div'));
            table.draw(data, {allowHtml:true,showRowNumber: false, width: '100%', height: '100%'});
        }

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chart_data); ?>);

            var options = {
                title: '<?php echo __('POS Performance','openpos'); ?>',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
        
        $(document).on('board_ajax_data',function (e,data) {
            dataRows = [];
            for(var i = 0;i< data.order.length;i++)
            {
                var _order = data.order[i];
                var tmp = [_order.order_id,_order.customer_name, _order.total, _order.cashier, _order.created_at,_order.view];
                dataRows.push(tmp);
            }
            var balance = data.cash_balance;
            $('#openpos-cash-balance').html(balance);
            google.charts.setOnLoadCallback(drawTable);

        });
        $('body').on('click','#reset-balance',function () {
            if(confirm('<?php echo __('This function to reset cash balance on your cash drawer to 0. Are you sure ?','openpos'); ?>'))
            {
                $.ajax({
                    url: openpos_admin.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data:{action:'admin_openpos_reset_balance'},
                    success:function(data){
                        $('#openpos-cash-balance').text(0);
                    }
                })
            }
        })

    }(jQuery));
</script>

<div class="op-dashboard-content">
    <div class="row goto-pos-container">
        <div class="col-md-4 pull-right"><a href="<?php echo $pos_url; ?>"class="button-primary" target="_blank"><?php echo __('Goto POS','openpos'); ?></a></div>
    </div>
    <div id="curve_chart"></div>
    <div class="real-content-container">
        <div class="last-orders" >
            <div class="title"><label><?php echo __('Last Orders','openpos'); ?></label></div>
            <div id="table_div"></div>
        </div>
        <div class="total">
            <div class="title"><label><?php echo __('Cash Balance','openpos'); ?></label></div>
            <ul id="total-details">

                <li>
                    <div class="field-title" style="text-align: center;">
                       <span id="openpos-cash-balance">0</span>
                        <a href="javascript:void(0);" id="reset-balance" style="outline: none;display: block;border:none;" title="Reset Balance">
                            <img src="<?php echo OPENPOS_URL; ?>/assets/images/reset.png" height="34px" />
                        </a>
                    </div>

                </li>
            </ul>

        </div>
    </div>
</div>
