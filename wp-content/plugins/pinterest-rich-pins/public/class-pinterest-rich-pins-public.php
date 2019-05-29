<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.vsourz.com
 * @since      1.0.0
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pinterest_Rich_Pins
 * @subpackage Pinterest_Rich_Pins/public
 * @author     Vsourz Development Team <support@vsourz.com>
 */
class Pinterest_Rich_Pins_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pinterest_Rich_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pinterest_Rich_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pinterest-rich-pins-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pinterest_Rich_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pinterest_Rich_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pinterest-rich-pins-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/*
	 * This function is to update product meta tags added by yoast seo plugin
	 */
	function pinterest_rich_pins_meta_tags_callback($key){
		if(is_product()){
			global $post;
			
			$prod_id = $post->ID;
			if(get_option("pinterest_wcrp_manage_metatags") == "custom_tags"){
				$key = get_the_excerpt($prod_id);
			}
		}
		return $key;
	}
	
	/*
	 * This function is to update product meta tags added by yoast seo plugin
	 */
	function pinterest_rich_pins_title_callback($key){
		if(is_product() && get_option("pinterest_wcrp_manage_metatags") == "custom_tags"){
			global $post;
			
			$prod_id = $post->ID;
			$key = get_the_title($prod_id)." - ".get_bloginfo( 'name' );
		}
		return $key;
	}
	
	/*
	 * This function is to update product meta tags added by yoast seo plugin
	 */
	function pinterest_rich_pins_wpseo_opengraph_type_callback($key){
		if(is_product() && get_option("pinterest_wcrp_manage_metatags") == "custom_tags"){
			$key = "product";
		}
		return $key;
	}
	
	/*
	 * This function is to update product meta tags added by yoast seo plugin
	 */
	function pinterest_rich_pins_wpseo_opengraph_callback(){
		if(is_product() && get_option("pinterest_wcrp_manage_metatags") == "custom_tags"){
			global $post;
			
			$post_id = $post->ID;
			$_product = wc_get_product($post_id);
			
			if(!empty($_product)){
				$display_meta_tags = true;
				$price = $_product->get_price();
				$currency = get_woocommerce_currency();
				?><meta property="product:price:amount" content="<?php echo number_format($price,2); ?>" />
				<meta property="product:price:currency" content="<?php echo $currency; ?>" />
				<meta property="og:availability" content="yes" /><?php
			}
		}
	}
	
	/*
	 * This function is to update product meta tags added by yoast seo plugin
	 */
	function pinterest_rich_pins_wpseo_opengraph_image_callback($key){
		if(is_product() && get_option("pinterest_wcrp_manage_metatags") == "custom_tags"){
			global $post;
			
			$post_id = $post->ID;
			$key = get_the_post_thumbnail_url( $post_id );
		}
		return $key;
	}
	
	/*
	 * This function is to update product meta tags added by yoast seo plugin
	 */
	function pinterest_rich_pins_extra_meta_callback(){
		
		$is_yeast_active = false;
		
		if ( is_active( 'wordpress-seo/wp-seo.php' ) ){
			$is_yeast_active = true;
		} 
		elseif( defined( 'WPSEO_VERSION' )) {
			$is_yeast_active = true;
		} 
		else {
			$is_yeast_active = false;
		}
		
		if(is_product() && get_option("pinterest_wcrp_manage_metatags") == "custom_tags"){
			global $post;
			
			$post_id = $post->ID;
			$_product = wc_get_product($post_id);
			
			if(!empty($_product)){
				$price = $_product->get_price();
				$currency = get_woocommerce_currency();
				$image = get_the_post_thumbnail_url( $post_id );
				$desc = $_product->get_short_description();
				$title = get_the_title($post_id);
				
				if(!$is_yeast_active){
					?><meta property="og:type" content="product" />
					<meta property="og:title" content="<?php echo $title." - ".get_bloginfo( 'name' ); ?>" />
					<meta property="og:description" content="<?php echo $desc; ?>" />
					<meta property="og:image" content="<?php echo $image; ?>" />
					<meta property="product:price:amount" content="<?php echo number_format($price,2); ?>" />
					<meta property="product:price:currency" content="<?php echo $currency; ?>" /><?php
				}
			}
		}
	}
}
