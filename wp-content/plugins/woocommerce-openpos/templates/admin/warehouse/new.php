<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php
global $op_warehouse;
$default = array(
    'id' => 0,
    'name' => '',
    'address' => '',
    'city' => '',
    'postal_code' => '',
    'country' => '',
    'status' => 'publish',
    'email' => '',
    'phone' => '',
    'facebook' =>''
);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id)
{
    $default = $op_warehouse->get($id);

}

?>
<style type="text/css">
    .register-name ul{
        list-style: none;
        display: block;
        margin:0;
        padding:0;
    }
    .register-name ul li{
        float:left;
        padding:3px;
        display: inline-block;
    }
    .register-frm{
        background-color: #ccccccb3;
    }
</style>
<div class="wrap">
    <div id="wrap-loading">
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    </div>
    <h1><?php echo __( 'Outlets', 'openpos' ); ?></h1>
    <br class="clear" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12" style="margin-bottom: 5px;">
                <a type="button" href="<?php echo admin_url('admin.php?page=op-warehouses'); ?>" class="btn btn-warning pull-left"><?php echo __('Back','openpos');?></a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3 warehouse-frm-container">
                <form class="form-horizontal" id="warehouse-frm">
                    <input type="hidden" name="action" value="openpos_update_warehouse" />
                    <input type="hidden" name="id" value="<?php echo $default['id'];?>" />
                    <h4 class="text-center"><?php echo __('General Information','openpos');?></h4>
                    <div class="form-group">
                        <label for="input_name" class="col-sm-4 control-label required "><?php echo __('Outlet Name','openpos');?></label>
                        <div class="col-sm-8">
                            <input type="text" name="name" value="<?php echo $default['name'];?>"  class="form-control" id="input_name" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_address" class="col-sm-4 control-label"><?php echo __('Address','openpos');?></label>
                        <div class="col-sm-8">
                            <input type="text" name="address" value="<?php echo $default['address'];?>"  class="form-control" id="input_address" placeholder="Address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_city" class="col-sm-4 control-label"><?php echo __('City','openpos');?></label>
                        <div class="col-sm-8">
                            <input type="text" name="city" value="<?php echo $default['city'];?>"  class="form-control" id="input_city" placeholder="City">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_zip" class="col-sm-4 control-label"><?php echo __('Postal Code','openpos');?></label>
                        <div class="col-sm-4">
                            <input type="text" name="postal_code" value="<?php echo $default['postal_code'];?>"  class="form-control" id="input_zip" placeholder="Potstal code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_country" class="col-sm-4 control-label"><?php echo __('Country','openpos');?></label>
                        <div class="col-sm-8">
                            <input type="text" name="country" value="<?php echo $default['country'];?>"  class="form-control" id="input_country" placeholder="Country">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_status" class="col-sm-4 control-label"><?php echo __('Status','openpos');?></label>
                        <div class="col-sm-4">
                            <select name="status" class="form-control">
                                <option <?php echo $default['status'] == 'publish' ? 'selected':''; ?> value="publish"><?php echo __('Active','openpos');?></option>
                                <option <?php echo $default['status'] == 'draft' ? 'selected':''; ?> value="draft"><?php echo __('Inactive','openpos');?></option>
                            </select>
                        </div>
                    </div>
                    <h4 class="text-center"><?php echo __('Contact Information','openpos');?></h4>
                    <div class="form-group">
                        <label for="inputEmail3"  class="col-sm-4 control-label"><?php echo __('Email','openpos');?></label>
                        <div class="col-sm-8">
                            <input type="email" value="<?php echo $default['email'];?>"  class="form-control" id="inputEmail3" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_phone" class="col-sm-4 control-label"><?php echo __('Phone','openpos');?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $default['phone'];?>"  class="form-control" id="input_phone" name="phone" placeholder="Phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_fb" class="col-sm-4 control-label"><?php echo __('Facebook','openpos');?></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $default['facebook'];?>"  class="form-control" id="input_fb" name="facebook" placeholder="Facebook">
                        </div>
                    </div>
                    <?php do_action('op_warehouse_form_end',$default); ?>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default pull-right"><?php echo __('Save','openpos');?></button>
                        </div>
                    </div>
                </form>
                <?php do_action('op_warehouse_form_after',$default); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        "use strict";
        $(document).ready(function(){
            $('#warehouse-frm').on('submit',function(){
                var data = $(this).serialize();
                $.ajax({
                    url: openpos_admin.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend:function(){
                        $('body').addClass('op_loading');
                    },
                    success:function(data){
                        if(data.status == 1)
                        {
                            window.location.href = '<?php echo admin_url('admin.php?page=op-warehouses&op-action=edit&id='); ?>'+data.data['id'];

                        }else {
                            alert(data.message);
                            $('body').removeClass('op_loading');
                        }
                    },
                    error:function(){
                        $('body').removeClass('op_loading');
                    }
                });
                console.log(data);
                return false;
            });


        });



    })( jQuery );
</script>