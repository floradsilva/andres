<div class="container-fluid">
    <div class="row" id="summary-list">
        <div class="col-md-3 col-log-3 col-sm-3 col-xs-3">
            <div class="summary-block">
                <dl>
                    <dt><?php echo __('Total Orders','openpos'); ?></dt>
                    <dd><?php echo $summaries['total_order'];?></dd>
                </dl>
            </div>
        </div>
        <div class="col-md-3 col-log-3 col-sm-3 col-xs-3">
            <div class="summary-block">
                <dl>
                    <dt><?php echo __('Total Sales','openpos'); ?></dt>
                    <dd><?php echo wc_price($summaries['total_sale']);?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-log-12 col-sm-12 col-xs-12">
            <div id="curve_chart"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($) {

        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chart_data); ?>);

            var options = {
                title: '',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }


    }(jQuery));
</script>