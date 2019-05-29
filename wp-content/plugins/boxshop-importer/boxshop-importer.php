<?php 
/**
 * Plugin Name: BoxShop Importer
 * Plugin URI: http://theme-sky.com
 * Description: Import the demo content of BoxShop theme
 * Version: 1.0.6
 * Author: ThemeSky Team
 * Author URI: http://theme-sky.com
 */
 
if( !class_exists('TS_Boxshop_Importer') ){
	class TS_Boxshop_Importer{

		function __construct(){
			/* Register js, css */
			add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
			
			/* Register Menu Page */
			add_action('admin_menu', array($this, 'register_menu_page'));
			
			/* Register ajax action */
			add_action( 'wp_ajax_ts_import_content', array($this, 'import_content') );
			add_action( 'wp_ajax_ts_import_revslider', array($this, 'import_revslider') );
			add_action( 'wp_ajax_ts_import_theme_options', array($this, 'import_theme_options') );
			add_action( 'wp_ajax_ts_import_widget', array($this, 'import_widget') );
			
			add_action( 'wp_ajax_ts_import_config', array($this, 'import_config') );
		}
		
		function register_scripts(){
			wp_enqueue_style( 'ts-import-style', plugins_url( '/assets/style.css', __FILE__ ) );
			wp_register_script( 'ts-import-script', plugins_url( '/assets/script.js', __FILE__ ), array( 'jquery' ), false, true );
		}
		
		function register_menu_page(){
			add_menu_page( 'Import Demo', 'BoxShop Importer', 'manage_options', 'ts_importer', array($this, 'importer_page_content'), '', 78 );
		}
		
		function importer_page_content(){
			wp_enqueue_script( 'ts-import-script' );
		?>
		
		<div class="ts-importer-wrapper">
			<div class="heading">
				<h2>BoxShop - Import Demo Content</h2>
			</div>
			<div class="note">
				<h3>Please read before importing:</h3>
				<p>This importer will help you build your site look like our demo. Importing data is recommended on fresh install.</p>
				<p>Please ensure you have already installed and activated ThemeSky, WooCommerce, Visual Composer and Revolution Slider plugins.</p>
				<p>Please note that importing data only builds a frame for your website. <strong>It will not import all demo contents and images.</strong></p>
				<p>It can take a few minutes to complete. <strong>Please don't close your browser while importing.</strong></p>
				<h3>Select the options below which you want to import:</h3>
			</div>
			<div class="options">
				<div class="option">
					<label for="ts_import_demo_content">
						<input type="checkbox" name="ts_import_demo_content" id="ts_import_demo_content" value="1" />
						Demo Content
					</label>
				</div>
				<div class="option">
					<label for="ts_import_theme_options">
						<input type="checkbox" name="ts_import_theme_options" id="ts_import_theme_options" value="1" />
						Theme Options
					</label>
				</div>
				<div class="option">
					<label for="ts_import_widget">
						<input type="checkbox" name="ts_import_widget" id="ts_import_widget" value="1" />
						Widgets
					</label>
				</div>
				<div class="option">
					<label for="ts_import_revslider">
						<input type="checkbox" name="ts_import_revslider" id="ts_import_revslider" value="1" />
						Revolution Slider
					</label>
				</div>
			</div>
			<div class="button-wrapper">
				<button id="ts-import-button" disabled>Import</button>
				<i class="fa fa-spinner fa-spin importing-button hidden"></i>
			</div>
			<div class="import-result hidden">
				<div class="progress">
				    <div class="progress-bar progress-bar-striped active" role="progressbar"
				    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					0% Complete
				    </div>
				</div>
				<div class="messages">
					
				</div>
			</div>
		</div>
		<?php
		}
		
		/* Include Importer Classes */
		function include_importer_classes(){
			if ( ! class_exists( 'WP_Importer' ) ) {
				include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			}

			if ( ! class_exists('WP_Import') ) {
				include_once dirname(__FILE__) . '/includes/wordpress-importer.php';
			}
		}
		
		/* Dont Resize image while importing */
		function no_resize_image( $sizes ){
			return array();
		}
		
		/* Import XML */
		function import_content(){
			set_time_limit(0);
			if ( !defined('WP_LOAD_IMPORTERS') ){ 
				define('WP_LOAD_IMPORTERS', true); 
			}
			
			add_filter('intermediate_image_sizes_advanced', array($this, 'no_resize_image'));
			
			$file_name = isset($_POST['file_name'])?$_POST['file_name']:'';
			$file_path = dirname(__FILE__) . '/data/content/'.$file_name.'.xml';
			
			if( file_exists($file_path) ){
				$this->include_importer_classes();
				
				$importer = new WP_Import();
				$importer->fetch_attachments = true;
				ob_start();
				$importer->import($file_path);
				ob_end_clean();
				
				echo 'Successful Import Demo Content';
			}
			
			wp_die();
		}
		
		function import_config(){
			$this->woocommerce_settings();
			$this->menu_locations();
			$this->update_options();
			$this->update_product_cats_in_product_tab_shortcode();
			$this->change_url();
			$this->delete_transients();
			echo 'Config successfully';
			wp_die();
		}
		
		/* Import Theme Options */
		function import_theme_options(){
			$theme_options_path = dirname(__FILE__) . '/data/theme_options.txt';
			if( !file_exists($theme_options_path) ){
				wp_die();
			}
			$theme_options_url = untrailingslashit( plugin_dir_url(__FILE__) ) . '/data/theme_options.txt';
			$theme_options_txt = wp_remote_get( $theme_options_url );
			$smof_data = json_decode( $theme_options_txt['body'], true );
			boxshop_of_save_options($smof_data);
			
			echo 'Successful Import Theme Options';
			wp_die();
		}
		
		function import_custom_sidebars(){
			$file_path = dirname(__FILE__) . '/data/custom_sidebars.txt';
			if( file_exists($file_path) ){
				$file_url = untrailingslashit( plugin_dir_url(__FILE__) ) . '/data/custom_sidebars.txt';
				$custom_sidebars = wp_remote_get( $file_url );
				$custom_sidebars = maybe_unserialize( trim( $custom_sidebars['body'] ) );
				update_option('ts_custom_sidebars', $custom_sidebars);
			}
		}
		
		/* Import Widgets */
		function import_widget(){
			$this->import_custom_sidebars();
			
			$file_path = dirname(__FILE__) . '/data/widget_data.wie';
			if( !file_exists($file_path) ){
				wp_die();
			}
			
			$data = implode( '', file( $file_path ) );
			$data = json_decode( $data );
			
			global $wp_registered_sidebars;

			/* Add custom sidebars to registered sidebars variable */
			$custom_sidebars = get_option('ts_custom_sidebars');
			if( is_array($custom_sidebars) && !empty($custom_sidebars) ){
				foreach( $custom_sidebars as $name ){
					$custom_sidebar = array(
										'name' 			=> ''.$name.''
										,'id' 			=> sanitize_title($name)
										,'description' 	=> ''
										,'class'		=> 'ts-custom-sidebar'
									);
					if( !isset($wp_registered_sidebars[$custom_sidebar['id']]) ){
						$wp_registered_sidebars[$custom_sidebar['id']] = $custom_sidebar;
					}
				}
			}
			
			// Have valid data?
			// If no data or could not decode.
			if ( empty( $data ) || ! is_object( $data ) ) {
				wp_die( 'Import data could not be read. Please try a different file.' );
			}

			// Get all available widgets site supports.
			$available_widgets = $this->get_available_widgets();

			// Get all existing widget instances.
			$widget_instances = array();
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
			}

			// Begin results.
			$results = array();

			// Loop import data's sidebars.
			foreach ( $data as $sidebar_id => $widgets ) {

				// Skip inactive widgets (should not be in export file).
				if ( 'wp_inactive_widgets' === $sidebar_id ) {
					continue;
				}

				// Check if sidebar is available on this site.
				// Otherwise add widgets to inactive, and say so.
				if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
					$sidebar_available    = true;
					$use_sidebar_id       = $sidebar_id;
					$sidebar_message_type = 'success';
					$sidebar_message      = '';
				} else {
					$sidebar_available    = false;
					$use_sidebar_id       = 'wp_inactive_widgets'; // Add to inactive if sidebar does not exist in theme.
					$sidebar_message_type = 'error';
					$sidebar_message      = 'Widget area does not exist in theme (using Inactive)';
				}

				// Result for sidebar
				// Sidebar name if theme supports it; otherwise ID.
				$results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id;
				$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
				$results[ $sidebar_id ]['message']      = $sidebar_message;
				$results[ $sidebar_id ]['widgets']      = array();

				// Loop widgets.
				foreach ( $widgets as $widget_instance_id => $widget ) {

					$fail = false;

					// Get id_base (remove -# from end) and instance ID number.
					$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
					$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

					// Does site support this widget?
					if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
						$fail                = true;
						$widget_message_type = 'error';
						$widget_message = 'Site does not support widget'; // Explain why widget not imported.
					}

					// Convert multidimensional objects to multidimensional arrays
					// Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
					// Without this, they are imported as objects and cause fatal error on Widgets page
					// If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach
					// It is probably much more likely that arrays are used than objects, however.
					$widget = json_decode( wp_json_encode( $widget ), true );

					// Does widget with identical settings already exist in same sidebar?
					if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

						// Get existing widgets in this sidebar.
						$sidebars_widgets = get_option( 'sidebars_widgets' );
						$sidebar_widgets = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); // Check Inactive if that's where will go.

						// Loop widgets with ID base.
						$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
						foreach ( $single_widget_instances as $check_id => $check_widget ) {

							// Is widget in same sidebar and has identical settings?
							if ( in_array( "$id_base-$check_id", $sidebar_widgets, true ) && (array) $widget === $check_widget ) {

								$fail = true;
								$widget_message_type = 'warning';

								// Explain why widget not imported.
								$widget_message = 'Widget already exists';

								break;

							}

						}

					}

					// No failure.
					if ( ! $fail ) {

						// Add widget instance
						$single_widget_instances = get_option( 'widget_' . $id_base ); // All instances for that widget ID base, get fresh every time.
						$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array(
							'_multiwidget' => 1, // Start fresh if have to.
						);
						$single_widget_instances[] = $widget; // Add it.

						// Get the key it was given.
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load,
						// and the widget doesn't stick (reload wipes it).
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number = 1;
							$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity.
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget.
						update_option( 'widget_' . $id_base, $single_widget_instances );

						// Assign widget instance to sidebar.
						// Which sidebars have which widgets, get fresh every time.
						$sidebars_widgets = get_option( 'sidebars_widgets' );

						// Avoid rarely fatal error when the option is an empty string
						if ( ! $sidebars_widgets ) {
							$sidebars_widgets = array();
						}

						// Use ID number from new widget instance.
						$new_instance_id = $id_base . '-' . $new_instance_id_number;

						// Add new instance to sidebar.
						$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id;

						// Save the amended data.
						update_option( 'sidebars_widgets', $sidebars_widgets );

						// After widget import action.
						$after_widget_import = array(
							'sidebar'           => $use_sidebar_id,
							'sidebar_old'       => $sidebar_id,
							'widget'            => $widget,
							'widget_type'       => $id_base,
							'widget_id'         => $new_instance_id,
							'widget_id_old'     => $widget_instance_id,
							'widget_id_num'     => $new_instance_id_number,
							'widget_id_num_old' => $instance_id_number,
						);

						// Success message.
						if ( $sidebar_available ) {
							$widget_message_type = 'success';
							$widget_message      = 'Imported';
						} else {
							$widget_message_type = 'warning';
							$widget_message      = 'Imported to Inactive';
						}

					}

					// Result for widget instance
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name'] = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; // Widget name or ID if name not available (not supported by site).
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = ! empty( $widget['title'] ) ? $widget['title'] : 'No Title'; // Show "No Title" if widget instance is untitled.
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;

				}

			}
			
			echo 'Successful Import Widgets';
			wp_die();
		}
		
		function get_available_widgets() {

			global $wp_registered_widget_controls;

			$widget_controls = $wp_registered_widget_controls;

			$available_widgets = array();

			foreach ( $widget_controls as $widget ) {

				// No duplicates.
				if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
					$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
					$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];
				}

			}

			return $available_widgets;
		}
		
		/* Import Revolution Slider */
		function import_revslider(){
			if( class_exists('RevSliderSlider') && class_exists('ZipArchive') ) {
				global $wpdb;
				$updateAnim = true;
				$updateStatic= true;
				
				$rev_directory = dirname(__FILE__) . '/data/revslider/';
				$rev_files = array();
				
				$rev_db = new RevSliderDB();
				
				foreach( glob( $rev_directory . '*.zip' ) as $filename ) {
					$filename = basename($filename);
					$allow_import = false;
					
					$arr_filename = explode('_', $filename);
					$slider_new_id = absint( $arr_filename[0] );
					if( $slider_new_id > 0 ){
						$response = $rev_db->fetch( RevSliderGlobals::$table_sliders, 'id=' + $slider_new_id );
						if( empty($response) ){ /* not exists */
							$rev_files_ids[] = $slider_new_id;
							$allow_import = true;
						}
						else{
							$rev_files_ids[] = 0;
							$allow_import = true;
						}
					}
					else{
						$rev_files_ids[] = 0;
						$allow_import = true;
					}
					
					if( $allow_import ){
						$rev_files[] = $rev_directory . $filename;
					}
				}

				foreach( $rev_files as $index => $rev_file ) {

						$filepath = $rev_file;

						$zip = new ZipArchive;
						$importZip = $zip->open($filepath, ZIPARCHIVE::CREATE);

						if( $importZip === true ){

							$slider_export = $zip->getStream('slider_export.txt');
							$custom_animations = $zip->getStream('custom_animations.txt');
							$dynamic_captions = $zip->getStream('dynamic-captions.css');
							$static_captions = $zip->getStream('static-captions.css');

							$content = '';
							$animations = '';
							$dynamic = '';
							$static = '';

							while ( !feof($slider_export) ) $content .= fread($slider_export, 1024);
							if($custom_animations){ while (!feof($custom_animations)) $animations .= fread($custom_animations, 1024); }
							if($dynamic_captions){ while (!feof($dynamic_captions)) $dynamic .= fread($dynamic_captions, 1024); }
							if($static_captions){ while (!feof($static_captions)) $static .= fread($static_captions, 1024); }

							fclose($slider_export);
							if($custom_animations){ fclose($custom_animations); }
							if($dynamic_captions){ fclose($dynamic_captions); }
							if($static_captions){ fclose($static_captions); }

						}else{
							$content = @file_get_contents($filepath);
						}

						if($importZip === true){
							$db = new RevSliderDB();

							$animations = @unserialize($animations);
							if( !empty($animations) ){
								foreach($animations as $key => $animation){
									$exist = $db->fetch(RevSliderGlobals::$table_layer_anims, "handle = '".$animation['handle']."'");
									if( !empty($exist) ){
										if( $updateAnim == 'true' ){
											$arrUpdate = array();
											$arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
											$db->update(RevSliderGlobals::$table_layer_anims, $arrUpdate, array('handle' => $animation['handle']));

											$id = $exist['0']['id'];
										}else{
											$arrInsert = array();
											$arrInsert["handle"] = 'copy_'.$animation['handle'];
											$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

											$id = $db->insert(RevSliderGlobals::$table_layer_anims, $arrInsert);
										}
									}else{
										$arrInsert = array();
										$arrInsert["handle"] = $animation['handle'];
										$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

										$id = $db->insert(RevSliderGlobals::$table_layer_anims, $arrInsert);
									}

									$content = str_replace(array('customin-'.$animation['id'], 'customout-'.$animation['id']), array('customin-'.$id, 'customout-'.$id), $content);
								}
							}else{

							}

							if( !empty($static) ){
								if( isset( $updateStatic ) && $updateStatic == 'true' ){
									RevSliderOperations::updateStaticCss($static);
								}else{
									$static_cur = RevSliderOperations::getStaticCss();
									$static = $static_cur."\n".$static;
									RevSliderOperations::updateStaticCss($static);
								}
							}
							
							$dynamicCss = RevSliderCssParser::parseCssToArray($dynamic);

							if(is_array($dynamicCss) && $dynamicCss !== false && count($dynamicCss) > 0){
								foreach($dynamicCss as $class => $styles){
									//check if static style or dynamic style
									$class = trim($class);
									
									if(strpos($class, ',') !== false && strpos($class, '.tp-caption') !== false){ //we have something like .tp-caption.redclass, .redclass
										$class_t = explode(',', $class);
										foreach($class_t as $k => $cl){
											if(strpos($cl, '.tp-caption') !== false) $class = $cl;
										}
									}
									
									if((strpos($class, ':hover') === false && strpos($class, ':') !== false) || //before, after
										strpos($class," ") !== false || // .tp-caption.imageclass img or .tp-caption .imageclass or .tp-caption.imageclass .img
										strpos($class,".tp-caption") === false || // everything that is not tp-caption
										(strpos($class,".") === false || strpos($class,"#") !== false) || // no class -> #ID or img
										strpos($class,">") !== false){ //.tp-caption>.imageclass or .tp-caption.imageclass>img or .tp-caption.imageclass .img
										continue;
									}
									
									//is a dynamic style
									if(strpos($class, ':hover') !== false){
										$class = trim(str_replace(':hover', '', $class));
										$arrInsert = array();
										$arrInsert["hover"] = json_encode($styles);
										$arrInsert["settings"] = json_encode(array('hover' => 'true'));
									}else{
										$arrInsert = array();
										$arrInsert["params"] = json_encode($styles);
										$arrInsert["settings"] = '';
									}
									//check if class exists
									$result = $db->fetch(RevSliderGlobals::$table_css, $db->prepare("handle = %s", array($class)));
									
									if(!empty($result)){ //update
										$db->update(RevSliderGlobals::$table_css, $arrInsert, array('handle' => $class));
									}else{ //insert
										$arrInsert["handle"] = $class;
										$db->insert(RevSliderGlobals::$table_css, $arrInsert);
									}
								}
								
							}
						}

						$content = preg_replace_callback('!s:(\d+):"(.*?)";!', array('RevSliderSlider', 'clear_error_in_string') , $content); //clear errors in string

						$arrSlider = @unserialize($content);
						$sliderParams = $arrSlider["params"];

						if(isset($sliderParams["background_image"]))
							$sliderParams["background_image"] = RevSliderFunctionsWP::getImageUrlFromPath($sliderParams["background_image"]);

						$json_params = json_encode($sliderParams);

						
						$arrInsert = array();
						$arrInsert["params"] = $json_params;
						$arrInsert["title"] = RevSliderFunctions::getVal($sliderParams, "title","Slider1");
						$arrInsert["alias"] = RevSliderFunctions::getVal($sliderParams, "alias","slider1");
						if( $rev_files_ids[$index] != 0 ){
							$arrInsert["id"] = $rev_files_ids[$index];
							$arrFormat = array('%s', '%s', '%s', '%d');
						}
						else{
							$arrFormat = array('%s', '%s', '%s');
						}
						$sliderID = $wpdb->insert(RevSliderGlobals::$table_sliders, $arrInsert, $arrFormat);
						$sliderID = $wpdb->insert_id;

						

						/* create all slides */
						$arrSlides = $arrSlider["slides"];

						$alreadyImported = array();

						foreach($arrSlides as $slide){

							$params = $slide["params"];
							$layers = $slide["layers"];

							if(isset($params["image"])){
								if(trim($params["image"]) !== ''){
									if($importZip === true){
										$image = $zip->getStream('images/'.$params["image"]);
										if(!$image){
											echo $params["image"].' not found!<br>';
										}else{
											if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]])){
												$importImage = RevSliderFunctionsWP::import_media('zip://'.$filepath."#".'images/'.$params["image"], $sliderParams["alias"].'/');

												if($importImage !== false){
													$alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]] = $importImage['path'];

													$params["image"] = $importImage['path'];
												}
											}else{
												$params["image"] = $alreadyImported['zip://'.$filepath."#".'images/'.$params["image"]];
											}
										}
									}
								}
								$params["image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["image"]);
							}

							foreach($layers as $key=>$layer){
								if(isset($layer["image_url"])){
									if(trim($layer["image_url"]) !== ''){
										if($importZip === true){
											$image_url = $zip->getStream('images/'.$layer["image_url"]);
											if(!$image_url){
												echo $layer["image_url"].' not found!<br>';
											}else{
												if(!isset($alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]])){
													$importImage = RevSliderFunctionsWP::import_media('zip://'.$filepath."#".'images/'.$layer["image_url"], $sliderParams["alias"].'/');

													if($importImage !== false){
														$alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]] = $importImage['path'];

														$layer["image_url"] = $importImage['path'];
													}
												}else{
													$layer["image_url"] = $alreadyImported['zip://'.$filepath."#".'images/'.$layer["image_url"]];
												}
											}
										}
									}
									$layer["image_url"] = RevSliderFunctionsWP::getImageUrlFromPath($layer["image_url"]);
									$layers[$key] = $layer;
								}
							}

							/* create new slide */
							$arrCreate = array();
							$arrCreate["slider_id"] = $sliderID;
							$arrCreate["slide_order"] = $slide["slide_order"];
							$arrCreate["layers"] = json_encode($layers);
							$arrCreate["params"] = json_encode($params);

							$wpdb->insert(RevSliderGlobals::$table_slides,$arrCreate);
					}
				}
			}
		}
		
		/* WooCommerce Settings */
		function woocommerce_settings(){
			$woopages = array(
				'woocommerce_shop_page_id' 			=> 'Shop'
				,'woocommerce_cart_page_id' 		=> 'Shopping cart'
				,'woocommerce_checkout_page_id' 	=> 'Checkout'
				,'woocommerce_myaccount_page_id' 	=> 'My Account'
				,'yith_wcwl_wishlist_page_id' 		=> 'Wishlist'
			);
			foreach( $woopages as $woo_page_name => $woo_page_title ) {
				$woopage = get_page_by_title( $woo_page_title );
				if( isset( $woopage->ID ) && $woopage->ID ) {
					update_option($woo_page_name, $woopage->ID);
				}
			}
			
			if( class_exists('YITH_Woocompare') ){
				update_option('yith_woocompare_compare_button_in_products_list', 'yes');
			}

			if( class_exists('WC_Admin_Notices') ){
				WC_Admin_Notices::remove_notice('install');
			}
			delete_transient( '_wc_activation_redirect' );
			
			flush_rewrite_rules();
		}
		
		/* Menu Locations */
		function menu_locations(){
			$locations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();

			if( $menus ) {
				foreach($menus as $menu) {
					if( $menu->name == 'Menu main' ) {
						$locations['primary'] = $menu->term_id;
					}
					if( $menu->name == 'Mobile menu' ) {
						$locations['mobile'] = $menu->term_id;
					}
					if( $menu->name == 'Shop by category' ) {
						$locations['vertical'] = $menu->term_id;
					}
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		
		/* Update Options */
		function update_options(){
			$homepage = get_page_by_title( 'Home' );
			if( is_object( $homepage ) ){
				update_option('show_on_front', 'page');
				update_option('page_on_front', $homepage->ID);
			}
		}
		
		/* Change url */
		function change_url(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			$import_url = 'http://demo.theme-sky.com/boxshop-import';
			$site_url = get_option( 'siteurl', '' );
			$wpdb->query("update `{$wp_prefix}posts` set `guid` = replace(`guid`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}posts` set `post_content` = replace(`post_content`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '{$import_url}', '{$site_url}');");
			
			$option_names = array('ts_logo', 'ts_logo_mobile', 'ts_logo_sticky', 'ts_favicon', 'ts_prod_placeholder_img');
			foreach( $option_names as $option_name ){
				$option_value = get_theme_mod($option_name, '');
				if( $option_value ){
					$option_value = str_replace($import_url, $site_url, $option_value);
					set_theme_mod($option_name, $option_value);
				}
			}
			
			/* Update Widgets */
			$widgets = array(
				'ts_single_image' => array('img_url')
				,'ts_mailchimp_subscription' => array('bg_image')
			);
			foreach( $widgets as $base => $fields ){
				$widget_instances = get_option( 'widget_' . $base, array() );
				if( is_array($widget_instances) ){
					foreach( $widget_instances as $number => $instance ){
						if( $number == '_multiwidget' ){
							continue;
						}
						foreach( $fields as $field ){
							if( isset($widget_instances[$number][$field]) ){
								$widget_instances[$number][$field] = str_replace($import_url, $site_url, $widget_instances[$number][$field]);
							}
						}
					}
					update_option( 'widget_' . $base, $widget_instances );
				}
			}
		}
		
		/* Delete transient */
		function delete_transients(){
			delete_transient('ts_mega_menu_custom_css');
			delete_transient('ts_product_deals_ids');
		}
		
		/* Update Product Cats in Product Tab Shortcode */
		function update_product_cats_in_product_tab_shortcode(){
			$product_cats = get_terms( array(
							'taxonomy'		=> 'product_cat'
							,'hide_empty'	=> true
							,'orderby'		=> 'count'
							,'order'		=> 'desc'
						)
					);
			if( is_array($product_cats) && count($product_cats) > 0 ){
				$product_cats = wp_list_pluck( $product_cats, 'term_id' );
				$product_cats = array_values($product_cats);
				
				$pages = array(
					'Home'	=> array(
							'62, 220, 221, 80, 245, 244, 246'
							,'243, 240, 241, 165, 242, 132, 163'
							,'234, 233, 239, 235, 237, 236, 238'
							,'75, 151, 225, 124, 247'
					)
					,'Supermarket 2'	=> array(
							'220, 246, 80, 245'
							,'75, 225, 151, 248'
							,'241, 240, 165, 242'
					)
					,'Home Electronic'	=> array(
							'246, 80, 62'
							,'246, 80, 62'
					)
					,'Home Houseware'	=> array(
							'234, 246, 62, 245, 80, 233, 237'
					)
					,'Home Organic'	=> array(
							'62, 80, 245'
					)
				);
				foreach( $pages as $page_title => $need_replaced_cats ){
					$page = get_page_by_title( $page_title );
					if( is_object( $page ) ){
						$index = 0;
						foreach( $need_replaced_cats as $need_replaced_cat ){
							$replaced_cats = array();
							for( $i = 0; $i < 4; $i++ ){
								if( !isset($product_cats[$index]) ){
									$index = 0;
								}
								$replaced_cats[] = $product_cats[$index];
								$index++;
							}
							$replaced_cats = array_unique($replaced_cats);
							$page->post_content = str_replace('product_cats="'.$need_replaced_cat.'"', 'product_cats="'.implode(',', $replaced_cats).'"', $page->post_content);
						}
						wp_update_post( $page );
					}
				}
			}
		}
		
	}
	new TS_Boxshop_Importer();
}
?>