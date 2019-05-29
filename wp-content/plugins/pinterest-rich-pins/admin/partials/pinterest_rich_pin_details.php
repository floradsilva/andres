<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$post_id = isset($post->ID) ? $post->ID : "";
$product = wc_get_product($post_id);
$image = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id,'thumbnail') : "";

// Thumbnail image
$imageId = get_post_thumbnail_id($post_id);

// Gallery images
$attachment_ids = $product->get_gallery_image_ids();

if(!empty($attachment_ids)){
	if(!empty($imageId) && !in_array($imageId,$attachment_ids)){
		$attachment_ids[] = $imageId;
	}
}
else{
	if(!empty($imageId)){
		$attachment_ids = array($imageId);
	}
}

$selected = array();

$where = " O.product_id = '".$post_id."' ";
$order = " DESC ";
$orderby = " id ";
$LIMIT = "";
$added_images = get_from_attachments($id='',$where,$order,$orderby,$LIMIT);
if(!empty($added_images)){
	foreach ($added_images as $pint_attach){
		$selected[] = $pint_attach->attachment_id;
	}
}

$is_pinterest = get_post_meta($post_id,"is_pinterest_product",true);



if(!empty($attachment_ids)){
	
	?><div class="pinterest_rich_pin_loader-image inner-position"></div>
	<p>
		<input type="checkbox" value="yes" name="is_pinterest_product" id="is_pinterest_product" <?php if($is_pinterest == "yes"){ echo 'checked'; } ?> />
		<label for="is_pinterest_product" ><?php _e('Want to perform action on Pinterest?',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label>
	</p>
	<p class="pinterest_action_outer">
		<label for="pinterest_action"><?php _e('Action to perform',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?> : </label>
		<select name="pinterest_action" id="pinterest_action">
			<option value="add"><?php _e('Add',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
			<option value="update"><?php _e('Update',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
			<option value="delete"><?php _e('Delete',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
		</select>
		<span class="description"><?php _e('Please select specific action to perform.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></span>
	</p>
	<div class="pinterest_action_outer">
		<p><?php 
			_e('Please select images which you want to display in Pinterest.',PINTEREST_RICH_PINS_TEXT_DOMAIN);
		?></p>
		<div class="pinterest-product-images-select-outer">
			<div class="prp-row"><?php
				foreach( $attachment_ids as $attachment_id ) {
					?><div class="prp-col">
						<div class="pinterest-product-images <?php if(in_array($attachment_id, $selected)){ echo 'active'; } ?>">
							<div class="check-mark"><i class="fa fa-check"></i></div>
							<input type="checkbox" id="pinterest_product_images_<?php echo $attachment_id; ?>" class="pinterest_product_images" name="pinterest_product_images[]" value="<?php echo $attachment_id; ?>" <?php if(in_array($attachment_id, $selected)){ echo 'checked'; } ?> />
							<label for="pinterest_product_images_<?php echo $attachment_id; ?>">
								<img height="200" width="200" src="<?php echo wp_get_attachment_url( $attachment_id, 'thumbnail' ); ?>" />
							</label>
						</div>
					</div><?php
				}
				
			?></div>
			<div class="clear"></div>
		</div>
		<script>
			jQuery(document).ready(function(){
				
				// On load
				if(!jQuery("#is_pinterest_product").prop("checked")){
					jQuery(".pinterest_action_outer").hide();
				}
				
				// On change
				jQuery("#is_pinterest_product").change(function(){
					if(jQuery(this).prop("checked")){
						jQuery(".pinterest_action_outer").show();
					}
					else{
						jQuery(".pinterest_action_outer").hide();
					}
				});
				
				jQuery(".pinterest-product-images").click(function(){
					var isChecked = jQuery(this).find(".pinterest_product_images").prop("checked");
					var thisObj = jQuery(this);
					
					if(isChecked){
						jQuery(this).find(".pinterest_product_images").prop("checked",false);
						jQuery(thisObj).removeClass('active');
					}
					else{
						jQuery(this).find(".pinterest_product_images").prop("checked",true)
						jQuery(thisObj).addClass('active');
					}
				});
			});
		</script>
	</div><?php
}
else{
	?><script>
		jQuery(document).ready(function(){
			jQuery("#pinterest_rich_pin_details").hide();
		});
	</script><?php
}