<?php
global $op_warehouse;
$warehouses = $op_warehouse->warehouses();
$openpos_type = $this->settings_api->get_option('openpos_type','openpos_pos');

?>
<style type="text/css">
    .warehouse-name ul{
        list-style: none;
        display: block;
        margin:0;
        padding:0;
    }
    .warehouse-name ul li{
        float:left;
        padding:3px;
        display: inline-block;
    }
    .register-frm{
        background-color: #ccccccb3;
    }
    .status-draft{
        color: red;
    }
    .status-publish{
        color: green;
    }
</style>
<div class="wrap">
    <h1><?php echo __( 'Outlets', 'openpos' ); ?></h1>
    <br class="clear" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12" style="margin-bottom: 5px;">
                <a type="button" href="<?php echo admin_url('admin.php?page=op-warehouses&op-action=new'); ?>" class="btn btn-primary pull-right"><?php echo __('Add New Outlet','openpos');?></a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">

                <div class="table-responsive">
                    <table class="table register-list">
                        <tr>
                            <th><?php echo __( 'Name', 'openpos' ); ?></th>
                            <th><?php echo __( 'Address', 'openpos' ); ?></th>
                            <th><?php echo __( 'Contacts', 'openpos' ); ?></th>
                            <th><?php echo __( 'Status', 'openpos' ); ?></th>
<!--                            <th>--><?php //echo __( 'Total Qty', 'openpos' ); ?><!--</th>-->
                            <th><?php echo __( 'Action', 'openpos' ); ?></th>
                        </tr>
                        <?php foreach($warehouses as $warehouse): ?>
                            <tr>
                                <td class="warehouse-name">
                                    <p><?php echo $warehouse['name']; ?></p>
                                    <ul>
                                        <?php if($warehouse['id'] > 0):  ?>

                                            <li><a href="<?php echo admin_url('admin.php?page=op-warehouses&op-action=edit&id='.esc_attr($warehouse['id'])); ?>"><?php echo __( 'Edit', 'openpos' ); ?></a></li>
                                            <li>|</li>
                                            <li><a href="javascript:void(0);" class="delete-warehouse-btn" data-id="<?php echo esc_attr($warehouse['id']); ?>"><?php echo __( 'Delete', 'openpos' ); ?></a></li>
                                            <li>|</li>
                                            <li><a href="<?php echo admin_url('admin.php?page=op-transactions&warehouse='.esc_attr($warehouse['id'])); ?>"><?php echo __('Transactions','openpos'); ?></a></li>
                                            <li>|</li>
                                            <li><a href="<?php echo admin_url('edit.php?post_type=shop_order&warehouse='.esc_attr($warehouse['id'])); ?>"><?php echo __('Orders','openpos'); ?></a></li>
                                        <?php else: ?>

                                                <li><a href="<?php echo admin_url('admin.php?page=op-transactions&warehouse='.esc_attr($warehouse['id'])); ?>"><?php echo __('Transactions','openpos'); ?></a></li>
                                                <li>|</li>
                                                <li><a href="<?php echo admin_url('edit.php?post_type=shop_order&warehouse='.esc_attr($warehouse['id'])); ?>"><?php echo __('Orders','openpos'); ?></a></li>
                                        <?php endif; ?>
                                        <?php if($openpos_type =='restaurant'): ?>
                                            <li>|</li>
                                            <li><a target="_blank" href="<?php echo OPENPOS_URL.'/kitchen/index.php?id='.esc_attr($warehouse['id']);  ?>"><?php echo __('Kitchen Screen','openpos'); ?></a></li>
                                        <?php endif; ?>

                                    </ul>
                                </td>
                                <td class="address">
                                    <address>
                                        <?php echo $address = WC()->countries->get_formatted_address( $op_warehouse->getStorePickupAddress( $warehouse['id'] ) ); ?>
                                    </address>
                                </td>
                                <td>
                                    <address>
                                        <?php  echo $warehouse['phone'] ? '<abbr title="Phone">P:</abbr>'.$warehouse['phone'].'<br>':'' ?>
                                        <?php  echo $warehouse['email'] ? '<a href="mailto:#">'.$warehouse['email'].'</a><br>':'' ?>
                                        <?php  echo $warehouse['facebook'] ? '<abbr title="Facebook">Fb:</abbr>'.$warehouse['facebook'].'<br>':'' ?>
                                    </address>
                                </td>
                                <td>
                                    <span class="status-<?php echo esc_attr($warehouse['status']); ?>"><?php echo $warehouse['status'] == 'publish' ? 'Active' : 'Inactive'; ?></span>
                                </td>
<!--                                <td>-->
<!--                                    --><?php //echo $warehouse['total_qty']; ?>
<!--                                </td>-->
                                <td>

                                    <ul>
                                        <li><a href="<?php echo admin_url('admin.php?page=op-warehouses&op-action=inventory&id='.esc_attr($warehouse['id'])); ?>"><?php echo __('Inventory','openpos'); ?></a></li>
                                    </ul>

                                </td>

                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="6"></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        "use strict";
        $(document).ready(function(){


            $(document).on('click','.delete-warehouse-btn',function(){
                var id = $(this).data('id');

                if(confirm('Are you sure ? '))
                {
                    $.ajax({
                        url: openpos_admin.ajax_url,
                        type: 'post',
                        dataType: 'json',
                        //data:$('form#op-product-list').serialize(),
                        data: {action: 'openpos_delete_warehouse',id:id},
                        beforeSend:function(){
                            $('body').addClass('op_loading');
                        },
                        success:function(data){
                            if(data.status == 1)
                            {
                                location.reload();
                            }else {
                                alert(data.message);
                                $('body').removeClass('op_loading');
                            }
                        },
                        error:function(){
                            $('body').removeClass('op_loading');
                        }
                    });
                }
            });

        });



    })( jQuery );
</script>