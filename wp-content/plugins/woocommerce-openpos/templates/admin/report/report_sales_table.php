<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-log-12 col-sm-12 col-xs-12">
            <div id="table_div"></div>

<!--            <button id="export" data-export="export">Export</button>-->
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        "use strict";
        //var datatable = new DataTable(document.querySelector('#my-table'), {
        //    filters: [true,false, 'select',false,'select',false],
        //    data: <?php //echo json_encode($orders_table_data); ?>//,
        //    pageSize:  <?php //echo count($orders_table_data); ?>//,
        //
        //});
        //$("#export").click(function(){
        //    $("#my-table").tableToCSV();
        //});



    })( jQuery );

</script>

<script type="text/javascript">
    (function($) {

        google.charts.setOnLoadCallback(drawTable);

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', '<?php echo __('#','openpos'); ?>');
            data.addColumn('string', '<?php echo __('Grand Total','openpos'); ?>');
            data.addColumn('string', '<?php echo __('Cashier','openpos'); ?>');
            data.addColumn('string', '<?php echo __('Created At','openpos'); ?>');

            data.addColumn('string', '<?php echo __('View','openpos'); ?>');

            var dataRows = <?php echo json_encode($orders_table_data); ?>;
            data.addRows(dataRows);
            var table = new google.visualization.Table(document.getElementById('table_div'));
            table.draw(data, {allowHtml:true,showRowNumber: false, width: '100%', height: '100%'});
        }


    }(jQuery));
</script>