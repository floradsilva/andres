<!-- Loop items are displayed here -->
<tr>
    <td>
        <input type="checkbox" name="queue_check" id="queue_check<?php echo $pinterest_rich_pin_queue->id; ?>" value="<?php echo $pinterest_rich_pin_queue->id; ?>" class="queue_checkbox" >
    </td>
    <td>
		<strong><?php echo $queue_id; ?></strong>
    </td>
	<td><img src="<?php echo wp_get_attachment_url($image_id); ?>" height="40" width="40" /></td>
	<td><?php
		echo '<a href="'.$product_link.'"><strong>'.ucfirst($name).'</strong></a>';
	?></td>
	 <td class="<?php echo $queue_status; ?>"><?php
		echo ucfirst($queue_status);
	?></td>
	<td><?php
		// Change the date format as required
		echo  pinterest_rich_pin_change_date_format_admin($queue_datetime,'Y-m-d H:i:s','j F, Y, g:i a');
   ?></td>
	<td><?php echo ucfirst($action); ?></td>
    <td align="center" ><?php
		if($queue_status == "processing"){
			?><a class="remove_queue_action pinterest_rich_pin_action-btn" data-id="<?php echo $queue_id; ?>" href="javascript:void(0);" title="<?php _e('Remove from queue',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>"><span class="fa fa-trash"></span></a><?php
		}
		else{
			?><a class="retry_queue_action pinterest_rich_pin_action-btn" data-id="<?php echo $queue_id; ?>" href="javascript:void(0);" title="<?php _e('Retry from queue',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>"><span class="fa fa-repeat"></span></a><?php
		}
    ?></td>
</tr>
<!-- Loop items are displayed here ends-->