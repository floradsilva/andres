//-------------------------------------------
// Options handlers
//-------------------------------------------
jQuery(document).ready(function() {
	"use strict";

	// Init jQuery Tabs
	jQuery('#trx_addons_options_tabs').tabs();

	// Init jQuery Accordion
	jQuery('.trx_addons_options_panels').accordion({
		'header': '.trx_addons_options_panel_title',
		'heightStyle': 'content'
	});

	// Init Sortable items
	jQuery('.trx_addons_options .trx_addons_options_item_choises.trx_addons_options_sortable').sortable({
		items: "label",
		update: function(event, ui) {
			var choises = '';
			ui.item.parent().find('input[type="checkbox"]').each(function() {
				choises += (choises ? '|' : '') + jQuery(this).data('name') + '=' + (jQuery(this).get(0).checked ? jQuery(this).val() : '0');
			});
			ui.item.siblings('input[type="hidden"]').eq(0).val(choises).trigger('change');
		}
	}).disableSelection();

	// Init checklist
	jQuery('.trx_addons_options .trx_addons_options_item_choises').on('change', 'input[type="checkbox"]', function() {
		var choises = '';
		var cont = jQuery(this).parents('.trx_addons_options_item_choises');
		cont.find('input[type="checkbox"]').each(function() {
			choises += (choises ? '|' : '') + jQuery(this).data('name') + '=' + (jQuery(this).get(0).checked ? jQuery(this).val() : '0');
		});
		cont.find('input[type="hidden"]').eq(0).val(choises).trigger('change');
	});
	
	// Check dependencies
	jQuery('.trx_addons_options .trx_addons_options_section').each(function () {
		trx_addons_options_check_dependencies(jQuery(this));
	});
	jQuery('.trx_addons_options .trx_addons_options_item_field [name^="trx_addons_options_field_"]').on('change', function () {
		trx_addons_options_check_dependencies(jQuery(this).parents('.trx_addons_options_section'));
	});
});

// Check for dependencies
function trx_addons_options_check_dependencies(cont) {
	"use strict";
	cont.find('.trx_addons_options_item_field').each(function() {
		"use strict";
		var id = jQuery(this).data('param');
		if (id == undefined) return;
		var depend = false;
		for (var fld in TRX_ADDONS_DEPENDENCIES) {
			if (fld == id) {
				depend = TRX_ADDONS_DEPENDENCIES[id];
				break;
			}
		}
		if (depend) {
			var dep_cnt = 0, dep_all = 0;
			var dep_cmp = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
			var dep_strict = typeof depend.strict != 'undefined';
			var fld=null, val='', name='', subname='';
			var parts = '', parts2 = '';
			for (var i in depend) {
				if (i == 'compare' || i == 'strict') continue;
				dep_all++;
				name = i;
				subname = '';
				if (name.indexOf('[') > 0) {
					parts = name.split('[');
					name = parts[0];
					subname = parts[1].replace(']', '');
				}
				if (name.charAt(0)=='#') {
					fld = jQuery(name);
					if (fld.length > 0 && !fld.hasClass('trx_addons_inited')) {
						fld.addClass('trx_addons_inited').on('change', function () {
							jQuery('.trx_addons_options .trx_addons_options_section').each(function () {
								trx_addons_options_check_dependencies(jQuery(this));
							});
						});
					}
				} else
					fld = cont.find('[name="trx_addons_options_field_'+name+'"]');
				if (fld.length > 0) {
					val = fld.attr('type')=='checkbox' || fld.attr('type')=='radio' 
								? (fld.parents('.trx_addons_options_item_field').find('[name^="trx_addons_options_field_"]:checked').length > 0 
									? fld.parents('.trx_addons_options_item_field').find('[name^="trx_addons_options_field_"]:checked').val()
									: 0
									)
								: fld.val();
					if (val===undefined) val = '';
					if (subname!='') {
						parts = val.split('|');
						for (var p=0; p<parts.length; p++) {
							parts2 = parts[p].split('=');
							if (parts2[0]==subname) {
								val = parts2[1];
							}
						}
					}
					for (var j in depend[i]) {
						if ( 
							   (depend[i][j]=='not_empty' && val!='') 										// Main field value is not empty - show current field
							|| (depend[i][j]=='is_empty' && val=='')										// Main field value is empty - show current field
							|| (val!=='' && (!isNaN(depend[i][j]) 											// Main field value equal to specified value - show current field
												? val==depend[i][j]
												: (dep_strict 
														? val==depend[i][j]
														: val.indexOf(depend[i][j])==0
													)
											)
								)
							|| (val!=='' && (""+depend[i][j]).charAt(0)=='^' && val.indexOf(depend[i][j].substr(1))==-1)	// Main field value not equal to specified value - show current field
						) {
							dep_cnt++;
							break;
						}
					}
				}
				if (dep_cnt > 0 && dep_cmp == 'or')
					break;
			}
			if ((dep_cnt > 0 && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
				jQuery(this).parents('.trx_addons_options_item').show().removeClass('trx_addons_options_no_use');
			} else {
				jQuery(this).parents('.trx_addons_options_item').hide().addClass('trx_addons_options_no_use');
			}
		}
	});
}
