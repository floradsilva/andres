<?php
global $op_woo;
$cashiers = $op_woo->get_cashiers();
$payment_methods = array();
$payment_methods[] = array(
        'code' => 'cash',
        'title' => __('Cash','openpos')
);
$payment_methods[] = array(
    'code' => 'chip_pin',
    'title' => __('Chip & PIN','openpos')
);
$payment_methods[] = array(
    'code' => 'stripe',
    'title' => __('POS - Stripe','openpos')
);

$payment_gateways = WC()->payment_gateways->payment_gateways();
foreach($payment_gateways as $code => $gateway)
{
    $payment_methods[] = array(
            'code' => $code,
            'title' => $gateway->method_title
    );
}

?>
<div class="container-fluid" style="margin-top:15px;">
    <div class="row">
        <div class="col-md-6 col-lg-6 col-lg-offset-3 col-md-offset-3">
            <form class="form-horizontal" type="get" id="report-frm">
                <input type="hidden" name="page" value="op-reports" />
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Type', 'openpos' ); ?></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="report_type">
                            <option value="sales" <?php echo $report_type == 'sales' ? 'selected':''; ?>><?php echo __( 'Sales Report', 'openpos' ); ?></option>
                            <?php if(count($cashiers) > 0): ?>
                            <option value="sale_by_seller" <?php echo $report_type == 'sale_by_seller' ? 'selected':''; ?>><?php echo __( 'Sales By Seller Report', 'openpos' ); ?></option>
                            <option value="sale_by_agent" <?php echo $report_type == 'sale_by_agent' ? 'selected':''; ?>><?php echo __( 'Sales By Shop Agent Report', 'openpos' ); ?></option>
                            <?php endif; ?>
                            <option value="transactions" <?php echo $report_type == 'transactions' ? 'selected':''; ?>><?php echo __( 'Transactions Report', 'openpos' ); ?></option>
                            <option value="sale_by_payment" <?php echo $report_type == 'sale_by_payment' ? 'selected':''; ?>><?php echo __( 'Sales By Payment Method', 'openpos' ); ?></option>
                            <option value="sale_by_product" <?php echo $report_type == 'sale_by_product' ? 'selected':''; ?>><?php echo __( 'Sales By Product', 'openpos' ); ?></option>
                           <option value="z_report"<?php echo $report_type == 'z_report' ? 'selected':''; ?> ><?php echo __( 'Z Report', 'openpos' ); ?></option>

                        </select>
                    </div>
                </div>
                <div class="form-group" id="outlet-form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Outlet', 'openpos' ); ?></label>
                    <div class="col-sm-10">
                        <select class="form-control report-attr" name="report_outlet">
                            <option value="-1" selected><?php echo __( 'All Outlets', 'openpos' ); ?></option>
                            <?php foreach($op_warehouse->warehouses() as $warehouse):?>
                                <option value="<?php echo $warehouse['id']; ?>"><?php echo $warehouse['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" id="register-form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Register', 'openpos' ); ?></label>
                    <div class="col-sm-10">
                        <select class="form-control report-attr" name="report_register">
                            <option value="0" selected><?php echo __( 'All Registers', 'openpos' ); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group" id="seller-form-group" style="display: none;">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Seller', 'openpos' ); ?></label>
                    <div class="col-sm-10">
                        <select class="form-control report-attr" name="report_seller">
                            <?php foreach($cashiers as $cashier): ?>
                            <option value="<?php echo $cashier->ID; ?>" selected><?php echo $cashier->display_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="payment-form-group" style="display: none;">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Payment Method', 'openpos' ); ?></label>
                    <div class="col-sm-10">
                        <select class="form-control report-attr" name="report_payment">
                            <option value="" selected="selected"><?php echo __('Choose method','openpos'); ?></option>
                            <?php foreach($payment_methods as $payment): ?>
                                <option value="<?php echo $payment['code']; ?>"><?php echo $payment['title']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Duration', 'openpos' ); ?></label>
                    <div class="col-sm-10">
                        <select class="form-control" name="report_duration">
                            <option value="today" <?php echo $report_duration == 'today' ? 'selected':''; ?>><?php echo __( 'Today', 'openpos' ); ?></option>
                            <option value="yesterday" <?php echo $report_duration == 'yesterday' ? 'selected':''; ?>><?php echo __( 'Yesterday', 'openpos' ); ?></option>
                            <option value="this_week" <?php echo $report_duration == 'this_week' ? 'selected':''; ?>><?php echo __( 'This Week', 'openpos' ); ?></option>
                            <option value="last_7_days" <?php echo $report_duration == 'last_7_days' ? 'selected':''; ?>><?php echo __( 'Last 7 Days', 'openpos' ); ?></option>
                            <option value="last_30_days" <?php echo $report_duration == 'last_30_days' ? 'selected':''; ?>><?php echo __( 'Last 30 Days', 'openpos' ); ?></option>
                            <option value="this_month" <?php echo $report_duration == 'this_month' ? 'selected':''; ?>><?php echo __( 'This month', 'openpos' ); ?></option>
                            <option value="custom" <?php echo $report_duration == 'custom' ? 'selected':''; ?>><?php echo __( 'custom', 'openpos' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="custom-date-container" style="<?php echo $report_duration == 'custom' ? 'display: block':'display: none'; ?>;">
                    <label for="inputPassword3" class="col-sm-2 control-label"><?php echo __( 'Date', 'openpos' ); ?></label>
                    <div class="col-sm-10">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <label class="sr-only"><?php echo __( 'From', 'openpos' ); ?></label>
                                    <input type="text" class="form-control datepicker" value="<?php echo $custom_from; ?>" autocomplete="false" id="from_date" name="custom_from" placeholder="From">
                                </div>
                                <div class="form-group col-sm-2 text-center">
                                   -
                                </div>
                                <div class="form-group col-sm-5">
                                    <label class="sr-only"><?php echo __( 'To', 'openpos' ); ?></label>
                                    <input type="text" class="form-control datepicker"  value="<?php echo $custom_to; ?>"  autocomplete="false" id="to_date" name="custom_to" placeholder="To">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button"  class="btn btn-default pull-right report-btn" data-export="true" style="margin-left: 5px;"><?php echo __( 'Export CSV', 'openpos' ); ?></button>
                        <button type="button"  class="btn btn-default pull-right report-btn" data-export="false" ><?php echo __( 'Get Report', 'openpos' ); ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>

</div>


<script type="text/javascript">

    (function($) {
        "use strict";

        function reportBtn(is_export){
            var form_data = $('#report-frm').serialize();
            var report_type = $('select[name="report_type"]').val();

            var offset = new Date().getTimezoneOffset();
            var request_data = form_data+'&report_action=load_data&action=op_ajax_report&time_offset='+offset;

            if(is_export)
            {
                request_data += '&export=1'
            }
            $.ajax({
                url: openpos_admin.ajax_url,
                type: 'post',
                dataType: 'json',
                data: request_data,
                beforeSend:function(){

                    $('#summary-list').html('');
                },
                success:function(data){


                    if(data['chart_data'])
                    {
                        var options = {
                            title: '',
                            curveType: 'function',
                            legend: { position: 'bottom' }
                        };
                        $('.report-chart').show();
                       if(data['chart_data'].length > 0)
                       {
                           var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                           var chart_data = google.visualization.arrayToDataTable(data['chart_data']);
                           chart.draw(chart_data, options);
                       }else {
                           $('.report-chart').hide();
                       }


                    }
                    if(data['table_data'])
                    {
                        var data_table = new google.visualization.DataTable();
                        if(report_type == 'transactions')
                        {
                            data_table.addColumn('number', '<?php echo __('#','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Ref','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('IN','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('OUT','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Method','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Created At','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Created By','openpos'); ?>');
                        }
                        if(report_type == 'sales')
                        {
                            data_table.addColumn('string', '<?php echo __('#','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Grand Total','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Cashier','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Created At','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('View','openpos'); ?>');
                        }

                        if(report_type == 'sale_by_seller' || report_type == 'sale_by_agent' )
                        {
                            data_table.addColumn('string', '<?php echo __('#','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Grand Total','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Seller Amount','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Cashier','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Created At','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('View','openpos'); ?>');
                        }
                        if(report_type == 'sale_by_payment' )
                        {
                            data_table.addColumn('string', '<?php echo __('#','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Grand Total','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Method Amount','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Cashier','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Created At','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('View','openpos'); ?>');
                        }
                        if(report_type == 'z_report' )
                        {
                            data_table.addColumn('string', '<?php echo __('#','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Session','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Clock IN','openpos'); ?>');
                            data_table.addColumn('string', '<?php echo __('Clock OUT','openpos'); ?>');
                            data_table.addColumn('number', '<?php echo __('Open Cash','openpos'); ?>');
                            data_table.addColumn('number', '<?php echo __('Close Cash','openpos'); ?>');
                            data_table.addColumn('number', '<?php echo __('Total Sales','openpos'); ?>');
                            //data_table.addColumn('string', '<?php echo __('Print','openpos'); ?>');
                        }
                        var dataRows = data['table_data'];
                        data_table.addRows(dataRows);
                        var table = new google.visualization.Table(document.getElementById('table_div'));
                        table.draw(data_table, {allowHtml:true,showRowNumber: false, width: '100%', height: '100%'});
                    }

                    if(data['summary_html'])
                    {
                        $('#summary-list').html(data['summary_html']);
                    }
                    if(is_export && data['export_file'])
                    {
                        document.location = data['export_file'];
                    }
                    console.log(data);
                }
            })
        }

        $(document).on('click','.report-btn',function(){
            var is_export = $(this).data('export');
            reportBtn(is_export);
        });

        var dateFormat = "yy-mm-dd",
            from = $( "#from_date" )
                .datepicker({
                    dateFormat: dateFormat
                })
                .on( "change", function() {
                    to.datepicker( "option", "minDate", getDate( this ) );
                }),
            to = $( "#to_date" ).datepicker({
                dateFormat: dateFormat
            })
                .on( "change", function() {
                    from.datepicker( "option", "maxDate", getDate( this ) );
                });
        function getDate( element ) {
            var date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            }

            return date;
        }

        $('select[name="report_duration"]').on('change',function(){
            if($(this).val() == 'custom')
            {
                $('#custom-date-container').show();
            }else {
                $('#custom-date-container').hide();
            }
        });
        $('select[name="report_type"]').on('change',function(){
            var report_type = $(this).val();
            if(report_type == 'sale_by_seller' || report_type == 'sale_by_agent')
            {
                $('#outlet-form-group').hide();
                $('#register-form-group').hide();
                $('#seller-form-group').show();
            }else {
                $('#outlet-form-group').show();
                $('#register-form-group').show();
                $('#seller-form-group').hide();
            }

            if(report_type == 'sale_by_payment')
            {

                $('#payment-form-group').show();
            }else {
                $('#payment-form-group').hide();

            }

        });
        $('select.report-attr').on('change',function(){
            var form_data = $('#report-frm').serialize();
            var current_name = $(this).attr('name');

            $.ajax({
                url: openpos_admin.ajax_url,
                type: 'post',
                dataType: 'json',
                data: form_data+'&report_action=load_form&action=op_ajax_report',
                beforeSend:function(){
                    if(current_name != 'report_register')
                    {
                        $("select[name='report_register']").find('option').each(function(){
                            if($(this).val() > 0)
                            {
                                $(this).remove();
                            }
                        })
                    }

                },
                success:function(data){
                    if(current_name != 'report_register') {
                        if (data.registers.length > 0) {
                            for (var i in data.registers) {
                                var register = data.registers[i];
                                var o = new Option(register['name'], register['id']);
                                $("select[name='report_register']").append(o);
                            }
                        }
                    }
                    // console.log(data);
                }
            })
        });


    })( jQuery );

</script>