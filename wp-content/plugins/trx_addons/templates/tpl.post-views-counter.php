<?php
/**
 * The template to AJAX increment post's views counter
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6
 */
?>
<!-- Post/page views count increment -->
<script type="text/javascript">
	jQuery(document).on('action.ready_trx_addons', function() {
		if (!TRX_ADDONS_STORAGE['post_views_counter_inited']) {
			TRX_ADDONS_STORAGE['post_views_counter_inited'] = true;
			setTimeout(function() {
				jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], {
					action: 'post_counter',
					nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
					post_id: <?php echo (int) get_the_ID(); ?>,
					views: 1
				}).done(function(response) {
					var rez = {};
					try {
						rez = JSON.parse(response);
					} catch (e) {
						rez = { error: TRX_ADDONS_STORAGE['ajax_error'] };
						console.log(response);
					}
					if (rez.error === '') {
						jQuery('.post_counters_single .post_counters_views .post_counters_number').html(rez.counter);
					}
				});
			}, 10);
		}
	});
</script>