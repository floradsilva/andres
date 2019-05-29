<?php
/**
 * Default Theme Options and Internal Theme Settings
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

// Theme init priorities:
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)

if ( !function_exists('gravity_options_theme_setup1') ) {
    add_action('after_setup_theme', 'gravity_options_theme_setup1', 1);
    function gravity_options_theme_setup1() {

        // -----------------------------------------------------------------
        // -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
        // -- Internal theme settings
        // -----------------------------------------------------------------
        gravity_storage_set('settings', array(

            'ajax_views_counter' => true,                        // Use AJAX for increment posts counter (if cache plugins used)
            // or increment posts counter then loading page (without cache plugin)
            'disable_jquery_ui' => false,                        // Prevent loading custom jQuery UI libraries in the third-party plugins

            'max_load_fonts' => 3,                            // Max fonts number to load from Google fonts or from uploaded fonts

            'use_mediaelements' => true,                        // Load script "Media Elements" to play video and audio

            'max_excerpt_length' => 30,                            // Max words number for the excerpt in the blog style 'Excerpt'.
            // For style 'Classic' - get half from this value
            'message_maxlength' => 1000                            // Max length of the message from contact form

        ));


// -----------------------------------------------------------------
// -- Theme fonts (Google and/or custom fonts)
// -----------------------------------------------------------------

// Fonts to load when theme start
// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
// For example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
        gravity_storage_set('load_fonts', array(
            // Google font
            array(
                'name' => 'Playfair Display',
                'family' => 'serif',
                'styles' => '400,400italic,700,700italic'
            ),
            // Font-face packed with theme
            array(
                'name' => 'Montserrat',
                'family' => 'sans-serif',
                'styles' => '400,700'
            )
        ));

// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
        gravity_storage_set('load_fonts_subset', 'latin,latin-ext');

// Settings of the main tags
        gravity_storage_set('theme_fonts', array(
            'p' => array(
                'title' => esc_html__('Main text', 'gravity'),
                'description' => esc_html__('Font settings of the main text of the site', 'gravity'),
                'font-family' => 'Montserrat, sans-serif',
                'font-size' => '1rem',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1.786em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '',
                'margin-top' => '0em',
                'margin-bottom' => '1.8em'
            ),
            'h1' => array(
                'title' => esc_html__('Heading 1', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '5rem',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px',
                'margin-top' => '0.875em',
                'margin-bottom' => '0.875em'
            ),
            'h2' => array(
                'title' => esc_html__('Heading 2', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '3.571rem',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px',
                'margin-top' => '1.25em',
                'margin-bottom' => '1em'
            ),
            'h3' => array(
                'title' => esc_html__('Heading 3', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '2.8571em',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px',
                'margin-top' => '1.3em',
                'margin-bottom' => '1.475em'
            ),
            'h4' => array(
                'title' => esc_html__('Heading 4', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '2.5em',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1.2em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px',
                'margin-top' => '1.685em',
                'margin-bottom' => '1.1em'
            ),
            'h5' => array(
                'title' => esc_html__('Heading 5', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '1.786em',
                'font-weight' => '700',
                'font-style' => 'normal',
                'line-height' => '1.2em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px',
                'margin-top' => '2.25em',
                'margin-bottom' => '1.5em'
            ),
            'h6' => array(
                'title' => esc_html__('Heading 6', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '1.286em',
                'font-weight' => '700',
                'font-style' => 'normal',
                'line-height' => '1.2em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px',
                'margin-top' => '3.35em',
                'margin-bottom' => '0.8em'
            ),
            'logo' => array(
                'title' => esc_html__('Logo text', 'gravity'),
                'description' => esc_html__('Font settings of the text case of the logo', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '1.8em',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1.25em',
                'text-decoration' => 'none',
                'text-transform' => 'uppercase',
                'letter-spacing' => '1px'
            ),
            'button' => array(
                'title' => esc_html__('Buttons', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '18px',
                'font-weight' => '700',
                'font-style' => 'normal',
                'line-height' => '1.5em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px'
            ),
            'input' => array(
                'title' => esc_html__('Input fields', 'gravity'),
                'description' => esc_html__('Font settings of the input fields, dropdowns and textareas', 'gravity'),
                'font-family' => 'Montserrat, sans-serif',
                'font-size' => '1em',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1.2em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px'
            ),
            'info' => array(
                'title' => esc_html__('Post meta', 'gravity'),
                'description' => esc_html__('Font settings of the post meta: date, counters, share, etc.', 'gravity'),
                'font-family' => 'Montserrat, sans-serif',
                'font-size' => '0.929rem',
                'font-weight' => '400',
                'font-style' => 'normal',
                'line-height' => '1.5em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px',
                'margin-top' => '0.4em',
                'margin-bottom' => ''
            ),
            'menu' => array(
                'title' => esc_html__('Main menu', 'gravity'),
                'description' => esc_html__('Font settings of the main menu items', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '1.214rem',
                'font-weight' => '700',
                'font-style' => 'normal',
                'line-height' => '1.5em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0px'
            ),
            'submenu' => array(
                'title' => esc_html__('Dropdown menu', 'gravity'),
                'description' => esc_html__('Font settings of the dropdown menu items', 'gravity'),
                'font-family' => 'Playfair Display, serif',
                'font-size' => '0.857rem',
                'font-weight' => '700',
                'font-style' => 'normal',
                'line-height' => '1.5em',
                'text-decoration' => 'none',
                'text-transform' => 'none',
                'letter-spacing' => '0.5px'
            )
        ));


// -----------------------------------------------------------------
// -- Theme colors for customizer
// -- Attention! Inner scheme must be last in the array below
// -----------------------------------------------------------------
        gravity_storage_set('schemes', array(

            // Color scheme: 'default'
            'default' => array(
                'title' => esc_html__('Default', 'gravity'),
                'colors' => array(

                    // Whole block border and background
                    'bg_color' => '#ffffff',
                    'bd_color' => '#e3e3e3', //+

                    // Text and links colors
                    'text' => '#9a9a9a', //+
                    'text_light' => '#c7c5c5', //+
                    'text_dark' => '#232323', //+
                    'text_link' => '#232323', //+
                    'text_hover' => '#f0ed98', //+

                    // Alternative blocks (submenu, buttons, tabs, etc.)
                    'alter_bg_color' => '#f5f5f5', //+
                    'alter_bg_hover' => '#353535', //+
                    'alter_bd_color' => '#f6f6f6', //+
                    'alter_bd_hover' => '#dadada',
                    'alter_text' => '#333333',
                    'alter_light' => '#b7b7b7',
                    'alter_dark' => '#36383d', //+
                    'alter_link' => '#fe7259',
                    'alter_hover' => '#72cfd5',

                    // Input fields (form's fields and textarea)
                    'input_bg_color' => 'rgba(221,225,229,0.3)',
                    'input_bg_hover' => 'rgba(221,225,229,0.3)',
                    'input_bd_color' => 'rgba(221,225,229,0.3)',
                    'input_bd_hover' => '#e5e5e5',
                    'input_text' => '#b7b7b7',
                    'input_light' => '#e5e5e5',
                    'input_dark' => '#1d1d1d',

                    // Inverse blocks (text and links on accented bg)
                    'inverse_text' => '#ffffff', //+
                    'inverse_light' => '#333333',
                    'inverse_dark' => '#000000',
                    'inverse_link' => '#ffffff',
                    'inverse_hover' => '#1d1d1d',
                )
            ),

            // Color scheme: 'dark'
            'dark' => array(
                'title' => esc_html__('Dark', 'gravity'),
                'colors' => array(

                    // Whole block border and background
                    'bg_color' => '#0e0d12',
                    'bd_color' => '#1c1b1f',

                    // Text and links colors
                    'text' => '#b7b7b7',
                    'text_light' => '#5f5f5f',
                    'text_dark' => '#ffffff',
                    'text_link' => '#fe7259',
                    'text_hover' => '#ffaa5f',

                    // Alternative blocks (submenu, buttons, tabs, etc.)
                    'alter_bg_color' => '#1e1d22',
                    'alter_bg_hover' => '#28272e',
                    'alter_bd_color' => '#313131',
                    'alter_bd_hover' => '#3d3d3d',
                    'alter_text' => '#a6a6a6',
                    'alter_light' => '#5f5f5f',
                    'alter_dark' => '#ffffff',
                    'alter_link' => '#ffaa5f',
                    'alter_hover' => '#fe7259',

                    // Input fields (form's fields and textarea)
                    'input_bg_color' => 'rgba(62,61,66,0.5)',
                    'input_bg_hover' => 'rgba(62,61,66,0.5)',
                    'input_bd_color' => 'rgba(62,61,66,0.5)',
                    'input_bd_hover' => '#353535', //+
                    'input_text' => '#b7b7b7',
                    'input_light' => '#5f5f5f',
                    'input_dark' => '#ffffff',

                    // Inverse blocks (text and links on accented bg)
                    'inverse_text' => '#1d1d1d',
                    'inverse_light' => '#5f5f5f',
                    'inverse_dark' => '#000000',
                    'inverse_link' => '#ffffff',
                    'inverse_hover' => '#1d1d1d',
                )
            )

        ));
    }
}


// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('gravity_options_create')) {

	function gravity_options_create() {

		gravity_storage_set('options', array(
		
			// Section 'Title & Tagline' - add theme options in the standard WP section
			'title_tagline' => array(
				"title" => esc_html__('Title, Tagline & Site icon', 'gravity'),
				"desc" => wp_kses_data( __('Specify site title and tagline (if need) and upload the site icon', 'gravity') ),
				"type" => "section"
				),
		
		
			// Section 'Header' - add theme options in the standard WP section
			'header_image' => array(
				"title" => esc_html__('Header', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload logo images, select header type and widgets set for the header', 'gravity') ),
				"type" => "section"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'gravity'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'gravity'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwide', 'gravity'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'header_style' => array(
				"title" => esc_html__('Header style', 'gravity'),
				"desc" => wp_kses_data( __('Select style to display the site header', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 'header-default',
				"options" => gravity_get_list_header_styles(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'gravity'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 'default',
				"options" => gravity_get_list_header_positions(),
				"type" => "select"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'gravity'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'gravity') ),
				),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(false, true),
				"type" => "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'gravity'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"dependency" => array(
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => gravity_get_list_range(0,6),
				"type" => "select"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'gravity'),
				"desc" => wp_kses_data( __('Select color scheme to decorate header area', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 'inherit',
				"options" => gravity_get_list_schemes(true),
				"refresh" => false,
				"type" => "select"
				),
            'top_panel_title_hide' => array(
                "title" => esc_html__('Hide title and breadcrumbs in header', 'gravity'),
                "desc" => wp_kses_data( __("Hide title and breadcrumbs in header", 'gravity') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'gravity')
                ),
                "std" => 0,
                "type" => "checkbox"
            ),
			'menu_info' => array(
				"title" => esc_html__('Menu settings', 'gravity'),
				"desc" => wp_kses_data( __('Select main menu style, position, color scheme and other parameters', 'gravity') ),
				"type" => "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'gravity'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'gravity'),
					'left'	=> esc_html__('Left',	'gravity'),
					'right'	=> esc_html__('Right',	'gravity')
				),
				"type" => "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Menu Color Scheme', 'gravity'),
				"desc" => wp_kses_data( __('Select color scheme to decorate main menu area', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => 'inherit',
				"options" => gravity_get_list_schemes(true),
				"refresh" => false,
				"type" => "select"
				),
            'menu_appointment_button' => array(
                "title" => esc_html__('Add styles for appointment button in main menu', 'gravity'),
                "desc" => wp_kses_data( __('Add styles for appointment button in main menu', 'gravity') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'gravity')
                ),
                "std" => '0',
                "type" => "checkbox"
            ),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'gravity'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'gravity') ),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'gravity'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'gravity') ),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'gravity'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'gravity') ),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo settings', 'gravity'),
				"desc" => wp_kses_data( __('Select logo images for the normal and Retina displays', 'gravity') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'gravity')
                ),
				"type" => "info"
				),
			'logo' => array(
				"title" => esc_html__('Logo', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload site logo', 'gravity') ),
                "override" => array(
                    'mode' => 'page',
                    'section' => esc_html__('Header', 'gravity')
                ),
				"std" => '',
				"type" => "image"
				),
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'gravity') ),
				"std" => '',
				"type" => "image"
				),
			'logo_inverse' => array(
				"title" => esc_html__('Logo inverse', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it on the dark background', 'gravity') ),
				"std" => '',
				"type" => "image"
				),
			'logo_inverse_retina' => array(
				"title" => esc_html__('Logo inverse for Retina', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'gravity') ),
				"std" => '',
				"type" => "image"
				),
			'logo_side' => array(
				"title" => esc_html__('Logo side', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu', 'gravity') ),
				"std" => '',
				"type" => "image"
				),
			'logo_side_retina' => array(
				"title" => esc_html__('Logo side for Retina', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'gravity') ),
				"std" => '',
				"type" => "image"
				),
			'logo_text' => array(
				"title" => esc_html__('Logo from Site name', 'gravity'),
				"desc" => wp_kses_data( __('Do you want use Site name and description as Logo if images above are not selected?', 'gravity') ),
				"std" => 1,
				"type" => "checkbox"
				),
			
		
		
			// Section 'Content'
			'content' => array(
				"title" => esc_html__('Content', 'gravity'),
				"desc" => wp_kses_data( __('Options for the content area', 'gravity') ),
				"type" => "section",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'gravity'),
				"desc" => wp_kses_data( __('Select width of the body content', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"refresh" => false,
				"std" => 'wide',
				"options" => array(
					'boxed'		=> esc_html__('Boxed',		'gravity'),
					'wide'		=> esc_html__('Wide',		'gravity'),
					'fullwide'	=> esc_html__('Fullwide',	'gravity'),
					'fullscreen'=> esc_html__('Fullscreen',	'gravity')
				),
				"type" => "select"
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'gravity'),
				"desc" => wp_kses_data( __('Select color scheme to decorate whole site. Attention! Case "Inherit" can be used only for custom pages, not for root site content in the Appearance - Customize', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"std" => 'default',
				"options" => gravity_get_list_schemes(true),
				"refresh" => false,
				"type" => "select"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'gravity'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'gravity')
				),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'gravity'),
				"desc" => wp_kses_data( __('Remove margins above and below the content area', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'gravity')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),
			'seo_snippets' => array(
				"title" => esc_html__('SEO snippets', 'gravity'),
				"desc" => wp_kses_data( __('Add structured data markup to the single posts and pages', 'gravity') ),
				"std" => 0,
				"type" => "checkbox"
				),
			'privacy_text' => array(
				"title" => esc_html__("Text with Privacy Policy link", 'gravity'),
				"desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'gravity') ),
				"std"   => wp_kses_post( __( 'I agree that my submitted data is being collected and stored.', 'gravity') ),
				"type"  => "text"
			),
			'border_radius' => array(
				"title" => esc_html__('Border radius', 'gravity'),
				"desc" => wp_kses_data( __('Specify the border radius of the form fields and buttons in pixels or other valid CSS units', 'gravity') ),
				"std" => 0,
				"type" => "text"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'gravity') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"std" => '',
				"type" => "image"
				),
			'no_image' => array(
				"title" => esc_html__('No image placeholder', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload image, used as placeholder for the posts without featured image', 'gravity') ),
				"std" => '',
				"type" => "image"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'gravity'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gravity')
				),
				"std" => 'sidebar_widgets',
				"options" => gravity_get_list_sidebars(false, true),
				"type" => "select"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'gravity'),
				"desc" => wp_kses_data( __('Select color scheme to decorate sidebar', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gravity')
				),
				"std" => 'inherit',
				"options" => gravity_get_list_schemes(true),
				"refresh" => false,
				"type" => "select"
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'gravity'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'gravity')
				),
				"refresh" => false,
				"std" => 'right',
				"options" => gravity_get_list_sidebars_positions(),
				"type" => "select"
				),
			'hide_sidebar_on_single' => array(
				"title" => esc_html__('Hide sidebar on the single post', 'gravity'),
				"desc" => wp_kses_data( __("Hide sidebar on the single post's pages", 'gravity') ),
				"std" => 0,
				"type" => "checkbox"
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets above the page', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'gravity')
				),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(false, true),
				"type" => "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'gravity')
				),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(false, true),
				"type" => "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'gravity')
				),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(false, true),
				"type" => "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets below the page', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Widgets', 'gravity')
				),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(false, true),
				"type" => "select"
				),
		
		
		
			// Section 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'gravity'),
				"desc" => wp_kses_data( __('Select set of widgets and columns number for the site footer', 'gravity') ),
				"type" => "section"
				),
			'footer_style' => array(
				"title" => esc_html__('Footer style', 'gravity'),
				"desc" => wp_kses_data( __('Select style to display the site footer', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Footer', 'gravity')
				),
				"std" => 'footer-default',
				"options" => apply_filters('gravity_filter_list_footer_styles', array(
					'footer-default' => esc_html__('Default Footer',	'gravity')
				)),
				"type" => "select"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'gravity'),
				"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gravity')
				),
				"std" => 'inherit',
				"options" => gravity_get_list_schemes(true),
				"refresh" => false,
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'gravity'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gravity')
				),
				"std" => 'footer_widgets',
				"options" => gravity_get_list_sidebars(false, true),
				"type" => "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'gravity'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gravity')
				),
				"dependency" => array(
					'footer_widgets' => array('^hide')
				),
				"std" => 4,
				"options" => gravity_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwide', 'gravity'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'gravity') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'gravity')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'gravity'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'gravity') ),
				'refresh' => false,
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'gravity') ),
				"dependency" => array(
					'logo_in_footer' => array('1')
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'gravity'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'gravity') ),
				"dependency" => array(
					'logo_in_footer' => array('1')
				),
				"std" => '',
				"type" => "image"
				),
			'socials_in_footer' => array(
				"title" => esc_html__('Show social icons', 'gravity'),
				"desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'gravity') ),
				"std" => 0,
				"type" => "checkbox"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'gravity'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'gravity') ),
				"std" => esc_html__('ThemeREX &copy; {Y}. All rights reserved. Terms of use and Privacy Policy', 'gravity'),
				"refresh" => false,
				"type" => "textarea"
				),
		
		
		
			// Section 'Homepage' - settings for home page
			'homepage' => array(
				"title" => esc_html__('Homepage', 'gravity'),
				"desc" => wp_kses_data( __('Select blog style and widgets to display on the homepage', 'gravity') ),
				"type" => "section"
				),
			'expand_content_home' => array(
				"title" => esc_html__('Expand content', 'gravity'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden on the Homepage', 'gravity') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),
			'blog_style_home' => array(
				"title" => esc_html__('Blog style', 'gravity'),
				"desc" => wp_kses_data( __('Select posts style for the homepage', 'gravity') ),
				"std" => 'excerpt',
				"options" => gravity_get_list_blog_styles(),
				"type" => "select"
				),
			'first_post_large_home' => array(
				"title" => esc_html__('First post large', 'gravity'),
				"desc" => wp_kses_data( __('Make first post large (with Excerpt layout) on the Classic layout of the Homepage', 'gravity') ),
				"dependency" => array(
					'blog_style_home' => array('classic')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'header_style_home' => array(
				"title" => esc_html__('Header style', 'gravity'),
				"desc" => wp_kses_data( __('Select style to display the site header on the homepage', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_header_styles(true),
				"type" => "select"
				),
			'header_position_home' => array(
				"title" => esc_html__('Header position', 'gravity'),
				"desc" => wp_kses_data( __('Select position to display the site header on the homepage', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_header_positions(true),
				"type" => "select"
				),
			'header_widgets_home' => array(
				"title" => esc_html__('Header widgets', 'gravity'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on the homepage', 'gravity') ),
				"std" => 'header_widgets',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'sidebar_widgets_home' => array(
				"title" => esc_html__('Sidebar widgets', 'gravity'),
				"desc" => wp_kses_data( __('Select sidebar to show on the homepage', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'sidebar_position_home' => array(
				"title" => esc_html__('Sidebar position', 'gravity'),
				"desc" => wp_kses_data( __('Select position to show sidebar on the homepage', 'gravity') ),
				"refresh" => false,
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars_positions(true),
				"type" => "select"
				),
			'widgets_above_page_home' => array(
				"title" => esc_html__('Widgets above the page', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'gravity') ),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'widgets_above_content_home' => array(
				"title" => esc_html__('Widgets above the content', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'gravity') ),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'widgets_below_content_home' => array(
				"title" => esc_html__('Widgets below the content', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'gravity') ),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'widgets_below_page_home' => array(
				"title" => esc_html__('Widgets below the page', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'gravity') ),
				"std" => 'hide',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			
		
		
			// Section 'Blog archive'
			'blog' => array(
				"title" => esc_html__('Blog archive', 'gravity'),
				"desc" => wp_kses_data( __('Options for the blog archive', 'gravity') ),
				"type" => "section",
				),
			'expand_content_blog' => array(
				"title" => esc_html__('Expand content', 'gravity'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden on the blog archive', 'gravity') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),
			'blog_style' => array(
				"title" => esc_html__('Blog style', 'gravity'),
				"desc" => wp_kses_data( __('Select posts style for the blog archive', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"dependency" => array(
					'#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
				),
				"std" => 'excerpt',
				"options" => gravity_get_list_blog_styles(),
				"type" => "select"
				),
			'blog_columns' => array(
				"title" => esc_html__('Blog columns', 'gravity'),
				"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'gravity') ),
				"std" => 2,
				"options" => gravity_get_list_range(2,4),
				"type" => "hidden"
				),
			'post_type' => array(
				"title" => esc_html__('Post type', 'gravity'),
				"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"dependency" => array(
					'#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
				),
				"linked" => 'parent_cat',
				"refresh" => false,
				"hidden" => true,
				"std" => 'post',
				"options" => gravity_get_list_posts_types(),
				"type" => "select"
				),
			'parent_cat' => array(
				"title" => esc_html__('Category to show', 'gravity'),
				"desc" => wp_kses_data( __('Select category to show in the blog archive', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"dependency" => array(
					'#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
				),
				"refresh" => false,
				"hidden" => true,
				"std" => '0',
				"options" => gravity_array_merge(array(0 => esc_html__('- Select category -', 'gravity')), gravity_get_list_categories()),
				"type" => "select"
				),
			'posts_per_page' => array(
				"title" => esc_html__('Posts per page', 'gravity'),
				"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"dependency" => array(
					'#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
				),
				"hidden" => true,
				"std" => '10',
				"type" => "text"
				),
			"blog_pagination" => array( 
				"title" => esc_html__('Pagination style', 'gravity'),
				"desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"std" => "pages",
				"options" => array(
					'pages'	=> esc_html__("Page numbers", 'gravity'),
					'links'	=> esc_html__("Older/Newest", 'gravity'),
					'more'	=> esc_html__("Load more", 'gravity'),
					'infinite' => esc_html__("Infinite scroll", 'gravity')
				),
				"type" => "select"
				),
			'show_filters' => array(
				"title" => esc_html__('Show filters', 'gravity'),
				"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"dependency" => array(
					'#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
					'blog_style' => array('portfolio', 'gallery')
				),
				"hidden" => true,
				"std" => 0,
				"type" => "checkbox"
				),
			'first_post_large' => array(
				"title" => esc_html__('First post large', 'gravity'),
				"desc" => wp_kses_data( __('Make first post large (with Excerpt layout) on the Classic layout of blog archive', 'gravity') ),
				"dependency" => array(
					'blog_style' => array('classic')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			"blog_content" => array( 
				"title" => esc_html__('Posts content', 'gravity'),
				"desc" => wp_kses_data( __("Show full post's content in the blog or only post's excerpt", 'gravity') ),
				"std" => "excerpt",
				"options" => array(
					'excerpt'	=> esc_html__('Excerpt',	'gravity'),
					'fullpost'	=> esc_html__('Full post',	'gravity')
				),
				"type" => "select"
				),
			'time_diff_before' => array(
				"title" => esc_html__('Time difference', 'gravity'),
				"desc" => wp_kses_data( __("How many days show time difference instead post's date", 'gravity') ),
				"std" => 5,
				"type" => "text"
				),
			'related_posts' => array(
				"title" => esc_html__('Related posts', 'gravity'),
				"desc" => wp_kses_data( __('How many related posts should be displayed in the single post?', 'gravity') ),
				"std" => 2,
				"options" => gravity_get_list_range(2,4),
				"type" => "hidden"
				),
			'related_style' => array(
				"title" => esc_html__('Related posts style', 'gravity'),
				"desc" => wp_kses_data( __('Select style of the related posts output', 'gravity') ),
				"std" => 2,
				"options" => gravity_get_list_styles(1,2),
				"type" => "hidden"
				),
			"blog_animation" => array( 
				"title" => esc_html__('Animation for the posts', 'gravity'),
				"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Content', 'gravity')
				),
				"dependency" => array(
					'#page_template' => array('blog.php'),
                    '.editor-page-attributes__template select' => array( 'blog.php' ),
				),
				"std" => "none",
				"options" => gravity_get_list_animations_in(),
				"type" => "select"
				),
			'header_style_blog' => array(
				"title" => esc_html__('Header style', 'gravity'),
				"desc" => wp_kses_data( __('Select style to display the site header on the blog archive', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_header_styles(true),
				"type" => "select"
				),
			'header_position_blog' => array(
				"title" => esc_html__('Header position', 'gravity'),
				"desc" => wp_kses_data( __('Select position to display the site header on the blog archive', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_header_positions(true),
				"type" => "select"
				),
			'header_widgets_blog' => array(
				"title" => esc_html__('Header widgets', 'gravity'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on the blog archive', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'sidebar_widgets_blog' => array(
				"title" => esc_html__('Sidebar widgets', 'gravity'),
				"desc" => wp_kses_data( __('Select sidebar to show on the blog archive', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'sidebar_position_blog' => array(
				"title" => esc_html__('Sidebar position', 'gravity'),
				"desc" => wp_kses_data( __('Select position to show sidebar on the blog archive', 'gravity') ),
				"refresh" => false,
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars_positions(true),
				"type" => "select"
				),
			'hide_sidebar_on_single_blog' => array(
				"title" => esc_html__('Hide sidebar on the single post', 'gravity'),
				"desc" => wp_kses_data( __("Hide sidebar on the single post", 'gravity') ),
				"std" => 0,
				"type" => "checkbox"
				),
			'widgets_above_page_blog' => array(
				"title" => esc_html__('Widgets above the page', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'widgets_above_content_blog' => array(
				"title" => esc_html__('Widgets above the content', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'widgets_below_content_blog' => array(
				"title" => esc_html__('Widgets below the content', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			'widgets_below_page_blog' => array(
				"title" => esc_html__('Widgets below the page', 'gravity'),
				"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'gravity') ),
				"std" => 'inherit',
				"options" => gravity_get_list_sidebars(true, true),
				"type" => "select"
				),
			
		
		
		
			// Section 'Colors' - choose color scheme and customize separate colors from it
			'scheme' => array(
				"title" => esc_html__('* Color scheme editor', 'gravity'),
				"desc" => wp_kses_data( __("<b>Simple settings</b> - you can change only accented color, used for links, buttons and some accented areas.", 'gravity') )
						. '<br>'
						. wp_kses_data( __("<b>Advanced settings</b> - change all scheme's colors and get full control over the appearance of your site!", 'gravity') ),
				"priority" => 1000,
				"type" => "section"
				),
		
			'color_settings' => array(
				"title" => esc_html__('Color settings', 'gravity'),
				"desc" => '',
				"std" => 'simple',
				"options" => array(
					"simple"  => esc_html__("Simple", 'gravity'),
					"advanced" => esc_html__("Advanced", 'gravity')
				),
				"refresh" => false,
				"type" => "switch"
				),
		
			'color_scheme_editor' => array(
				"title" => esc_html__('Color Scheme', 'gravity'),
				"desc" => wp_kses_data( __('Select color scheme to edit colors', 'gravity') ),
				"std" => 'default',
				"options" => gravity_get_list_schemes(),
				"refresh" => false,
				"type" => "select"
				),
		
			'scheme_storage' => array(
				"title" => esc_html__('Colors storage', 'gravity'),
				"desc" => esc_html__('Hidden storage of the all color from the all color shemes (only for internal usage)', 'gravity'),
				"std" => '',
				"refresh" => false,
				"type" => "hidden"
				),
		
			'scheme_info_single' => array(
				"title" => esc_html__('Colors for single post/page', 'gravity'),
				"desc" => wp_kses_data( __('Specify colors for single post/page (not for alter blocks)', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
				
			'bg_color' => array(
				"title" => esc_html__('Background color', 'gravity'),
				"desc" => wp_kses_data( __('Background color of the whole page', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'bd_color' => array(
				"title" => esc_html__('Border color', 'gravity'),
				"desc" => wp_kses_data( __('Color of the bordered elements, separators, etc.', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'text' => array(
				"title" => esc_html__('Text', 'gravity'),
				"desc" => wp_kses_data( __('Plain text color on single page/post', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_light' => array(
				"title" => esc_html__('Light text', 'gravity'),
				"desc" => wp_kses_data( __('Color of the post meta: post date and author, comments number, etc.', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_dark' => array(
				"title" => esc_html__('Dark text', 'gravity'),
				"desc" => wp_kses_data( __('Color of the headers, strong text, etc.', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_link' => array(
				"title" => esc_html__('Links', 'gravity'),
				"desc" => wp_kses_data( __('Color of links and accented areas', 'gravity') ),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'text_hover' => array(
				"title" => esc_html__('Links hover', 'gravity'),
				"desc" => wp_kses_data( __('Hover color for links and accented areas', 'gravity') ),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'scheme_info_alter' => array(
				"title" => esc_html__('Colors for alternative blocks', 'gravity'),
				"desc" => wp_kses_data( __('Specify colors for alternative blocks - rectangular blocks with its own background color (posts in homepage, blog archive, search results, widgets on sidebar, footer, etc.)', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
		
			'alter_bg_color' => array(
				"title" => esc_html__('Alter background color', 'gravity'),
				"desc" => wp_kses_data( __('Background color of the alternative blocks', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_bg_hover' => array(
				"title" => esc_html__('Alter hovered background color', 'gravity'),
				"desc" => wp_kses_data( __('Background color for the hovered state of the alternative blocks', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_bd_color' => array(
				"title" => esc_html__('Alternative border color', 'gravity'),
				"desc" => wp_kses_data( __('Border color of the alternative blocks', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_bd_hover' => array(
				"title" => esc_html__('Alternative hovered border color', 'gravity'),
				"desc" => wp_kses_data( __('Border color for the hovered state of the alter blocks', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_text' => array(
				"title" => esc_html__('Alter text', 'gravity'),
				"desc" => wp_kses_data( __('Text color of the alternative blocks', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_light' => array(
				"title" => esc_html__('Alter light', 'gravity'),
				"desc" => wp_kses_data( __('Color of the info blocks inside block with alternative background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_dark' => array(
				"title" => esc_html__('Alter dark', 'gravity'),
				"desc" => wp_kses_data( __('Color of the headers inside block with alternative background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_link' => array(
				"title" => esc_html__('Alter link', 'gravity'),
				"desc" => wp_kses_data( __('Color of the links inside block with alternative background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'alter_hover' => array(
				"title" => esc_html__('Alter hover', 'gravity'),
				"desc" => wp_kses_data( __('Color of the hovered links inside block with alternative background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'scheme_info_input' => array(
				"title" => esc_html__('Colors for the form fields', 'gravity'),
				"desc" => wp_kses_data( __('Specify colors for the form fields and textareas', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
		
			'input_bg_color' => array(
				"title" => esc_html__('Inactive background', 'gravity'),
				"desc" => wp_kses_data( __('Background color of the inactive form fields', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_bg_hover' => array(
				"title" => esc_html__('Active background', 'gravity'),
				"desc" => wp_kses_data( __('Background color of the focused form fields', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_bd_color' => array(
				"title" => esc_html__('Inactive border', 'gravity'),
				"desc" => wp_kses_data( __('Color of the border in the inactive form fields', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_bd_hover' => array(
				"title" => esc_html__('Active border', 'gravity'),
				"desc" => wp_kses_data( __('Color of the border in the focused form fields', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_text' => array(
				"title" => esc_html__('Inactive field', 'gravity'),
				"desc" => wp_kses_data( __('Color of the text in the inactive fields', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_light' => array(
				"title" => esc_html__('Disabled field', 'gravity'),
				"desc" => wp_kses_data( __('Color of the disabled field', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'input_dark' => array(
				"title" => esc_html__('Active field', 'gravity'),
				"desc" => wp_kses_data( __('Color of the active field', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
		
			'scheme_info_inverse' => array(
				"title" => esc_html__('Colors for inverse blocks', 'gravity'),
				"desc" => wp_kses_data( __('Specify colors for inverse blocks, rectangular blocks with background color equal to the links color or one of accented colors (if used in the current theme)', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"type" => "info"
				),
		
			'inverse_text' => array(
				"title" => esc_html__('Inverse text', 'gravity'),
				"desc" => wp_kses_data( __('Color of the text inside block with accented background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_light' => array(
				"title" => esc_html__('Inverse light', 'gravity'),
				"desc" => wp_kses_data( __('Color of the info blocks inside block with accented background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_dark' => array(
				"title" => esc_html__('Inverse dark', 'gravity'),
				"desc" => wp_kses_data( __('Color of the headers inside block with accented background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_link' => array(
				"title" => esc_html__('Inverse link', 'gravity'),
				"desc" => wp_kses_data( __('Color of the links inside block with accented background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),
			'inverse_hover' => array(
				"title" => esc_html__('Inverse hover', 'gravity'),
				"desc" => wp_kses_data( __('Color of the hovered links inside block with accented background', 'gravity') ),
				"dependency" => array(
					'color_settings' => array('^simple')
				),
				"std" => '$gravity_get_scheme_color',
				"refresh" => false,
				"type" => "color"
				),


			// Section 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'gravity'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'gravity') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Title', 'gravity')
				),
				"hidden" => true,
				"std" => '',
				"type" => "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'gravity'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'gravity') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Title', 'gravity')
				),
				"hidden" => true,
				"std" => '',
				"type" => "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		$fonts = array(
		
			// Panel 'Fonts' - manage fonts loading and set parameters of the base theme elements
			'fonts' => array(
				"title" => esc_html__('* Fonts settings', 'gravity'),
				"desc" => '',
				"priority" => 1500,
				"type" => "panel"
				),

			// Section 'Load_fonts'
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'gravity'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'gravity') )
						. '<br>'
						. wp_kses_data( __('<b>Attention!</b> Press "Refresh" button to reload preview area after the all fonts are changed', 'gravity') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'gravity'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'gravity') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'gravity') ),
				"refresh" => false,
				"std" => '$gravity_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=gravity_get_theme_setting('max_load_fonts'); $i++) {
			$fonts["load_fonts-{$i}-info"] = array(
				"title" => esc_html(sprintf(__('Font %s', 'gravity'), $i)),
				"desc" => '',
				"type" => "info",
				);
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'gravity'),
				"desc" => '',
				"refresh" => false,
				"std" => '$gravity_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'gravity'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'gravity') )
							: '',
				"refresh" => false,
				"std" => '$gravity_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'gravity'),
					'serif' => esc_html__('serif', 'gravity'),
					'sans-serif' => esc_html__('sans-serif', 'gravity'),
					'monospace' => esc_html__('monospace', 'gravity'),
					'cursive' => esc_html__('cursive', 'gravity'),
					'fantasy' => esc_html__('fantasy', 'gravity')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'gravity'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'gravity') )
											. '<br>'
								. wp_kses_data( __('<b>Attention!</b> Each weight and style increase download size! Specify only used weights and styles.', 'gravity') )
							: '',
				"refresh" => false,
				"std" => '$gravity_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Sections with font's attributes for each theme element
		$theme_fonts = gravity_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								: esc_html(sprintf(__('%s settings', 'gravity'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								: wp_kses_post( sprintf(__('Font settings of the "%s" tag.', 'gravity'), $tag) ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = gravity_get_list_load_fonts(true);
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gravity'),
						'100' => esc_html__('100 (Light)', 'gravity'), 
						'200' => esc_html__('200 (Light)', 'gravity'), 
						'300' => esc_html__('300 (Thin)',  'gravity'),
						'400' => esc_html__('400 (Normal)', 'gravity'),
						'500' => esc_html__('500 (Semibold)', 'gravity'),
						'600' => esc_html__('600 (Semibold)', 'gravity'),
						'700' => esc_html__('700 (Bold)', 'gravity'),
						'800' => esc_html__('800 (Black)', 'gravity'),
						'900' => esc_html__('900 (Black)', 'gravity')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gravity'),
						'normal' => esc_html__('Normal', 'gravity'), 
						'italic' => esc_html__('Italic', 'gravity')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gravity'),
						'none' => esc_html__('None', 'gravity'), 
						'underline' => esc_html__('Underline', 'gravity'),
						'overline' => esc_html__('Overline', 'gravity'),
						'line-through' => esc_html__('Line-through', 'gravity')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'gravity'),
						'none' => esc_html__('None', 'gravity'), 
						'uppercase' => esc_html__('Uppercase', 'gravity'),
						'lowercase' => esc_html__('Lowercase', 'gravity'),
						'capitalize' => esc_html__('Capitalize', 'gravity')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"refresh" => false,
					"std" => '$gravity_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters into Theme Options
		gravity_storage_merge_array('options', '', $fonts);

		// Add Header Video if WP version < 4.7
		if (!function_exists('get_header_video_url')) {
			gravity_storage_set_array_after('options', 'header_image_override', 'header_video', array(
				"title" => esc_html__('Header video', 'gravity'),
				"desc" => wp_kses_data( __("Select video to use it as background for the header", 'gravity') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'gravity')
				),
				"std" => '',
				"type" => "video"
				)
			);
		}
	}
}




// -----------------------------------------------------------------
// -- Create and manage Theme Options
// -----------------------------------------------------------------

// Theme init priorities:
// 2 - create Theme Options
if (!function_exists('gravity_options_theme_setup2')) {
	add_action( 'after_setup_theme', 'gravity_options_theme_setup2', 2 );
	function gravity_options_theme_setup2() {
		gravity_options_create();
	}
}

// Step 1: Load default settings and previously saved mods
if (!function_exists('gravity_options_theme_setup5')) {
	add_action( 'after_setup_theme', 'gravity_options_theme_setup5', 5 );
	function gravity_options_theme_setup5() {
		gravity_storage_set('options_reloaded', false);
		gravity_load_theme_options();
	}
}

// Step 2: Load current theme customization mods
if (is_customize_preview()) {
	if (!function_exists('gravity_load_custom_options')) {
		add_action( 'wp_loaded', 'gravity_load_custom_options' );
		function gravity_load_custom_options() {
			if (!gravity_storage_get('options_reloaded')) {
				gravity_storage_set('options_reloaded', true);
				gravity_load_theme_options();
			}
		}
	}
}

// Load current values for each customizable option
if ( !function_exists('gravity_load_theme_options') ) {
	function gravity_load_theme_options() {
		$options = gravity_storage_get('options');
		$reset = (int) get_theme_mod('reset_options', 0);
		foreach ($options as $k=>$v) {
			if (isset($v['std'])) {
				if (strpos($v['std'], '$gravity_')!==false) {
					$func = substr($v['std'], 1);
					if (function_exists($func)) {
						$v['std'] = $func($k);
					}
				}
				$value = $v['std'];
				if (!$reset) {
					if (isset($_GET[$k]))
						$value = $_GET[$k];
					else {
						$tmp = get_theme_mod($k, -987654321);
						if ($tmp != -987654321) $value = $tmp;
					}
				}
				gravity_storage_set_array2('options', $k, 'val', $value);
				if ($reset) remove_theme_mod($k);
			}
		}
		if ($reset) {
			// Unset reset flag
			set_theme_mod('reset_options', 0);
			// Regenerate CSS with default colors and fonts
			gravity_customizer_save_css();
		} else {
			do_action('gravity_action_load_options');
		}
	}
}

// Override options with stored page/post meta
if ( !function_exists('gravity_override_theme_options') ) {
	add_action( 'wp', 'gravity_override_theme_options', 1 );
	function gravity_override_theme_options($query=null) {
		if (is_page_template('blog.php')) {
			gravity_storage_set('blog_archive', true);
			gravity_storage_set('blog_template', get_the_ID());
		}
		gravity_storage_set('blog_mode', gravity_detect_blog_mode());
		if (is_singular()) {
			gravity_storage_set('options_meta', get_post_meta(get_the_ID(), 'gravity_options', true));
		}
	}
}


// Return customizable option value
if (!function_exists('gravity_get_theme_option')) {
	function gravity_get_theme_option($name, $defa='', $strict_mode=false, $post_id=0) {
		$rez = $defa;
		$from_post_meta = false;
		if ($post_id > 0) {
			if (!gravity_storage_isset('post_options_meta', $post_id))
				gravity_storage_set_array('post_options_meta', $post_id, get_post_meta($post_id, 'gravity_options', true));
			if (gravity_storage_isset('post_options_meta', $post_id, $name)) {
				$tmp = gravity_storage_get_array('post_options_meta', $post_id, $name);
				if (!gravity_is_inherit($tmp)) {
					$rez = $tmp;
					$from_post_meta = true;
				}
			}
		}
		if (!$from_post_meta && gravity_storage_isset('options')) {
			if ( !gravity_storage_isset('options', $name) ) {
				$rez = $tmp = '_not_exists_';
				if (function_exists('trx_addons_get_option'))
					$rez = trx_addons_get_option($name, $tmp, false);
				if ($rez === $tmp) {
					if ($strict_mode) {
						$s = debug_backtrace();
						$s = array_shift($s);
						echo '<pre>' . sprintf(esc_html__('Undefined option "%s" called from:', 'gravity'), $name);
						if (function_exists('dco')) dco($s);
						else print_r($s);
						echo '</pre>';
						die();
					} else
						$rez = $defa;
				}
			} else {
				$blog_mode = gravity_storage_get('blog_mode');
				// Override option from GET or POST for current blog mode
				if (!empty($blog_mode) && isset($_REQUEST[$name . '_' . $blog_mode])) {
					$rez = $_REQUEST[$name . '_' . $blog_mode];
				// Override option from GET
				} else if (isset($_REQUEST[$name])) {
					$rez = $_REQUEST[$name];
				// Override option from current page settings (if exists)
				} else if (gravity_storage_isset('options_meta', $name) && !gravity_is_inherit(gravity_storage_get_array('options_meta', $name))) {
					$rez = gravity_storage_get_array('options_meta', $name);
				// Override option from current blog mode settings: 'home', 'search', 'page', 'post', 'blog', etc. (if exists)
				} else if (!empty($blog_mode) && gravity_storage_isset('options', $name . '_' . $blog_mode, 'val') && !gravity_is_inherit(gravity_storage_get_array('options', $name . '_' . $blog_mode, 'val'))) {
					$rez = gravity_storage_get_array('options', $name . '_' . $blog_mode, 'val');
				// Get saved option value
				} else if (gravity_storage_isset('options', $name, 'val')) {
					$rez = gravity_storage_get_array('options', $name, 'val');
				// Get ThemeREX Addons option value
				} else if (function_exists('trx_addons_get_option')) {
					$rez = trx_addons_get_option($name, $defa, false);
				}
			}
		}
		return $rez;
	}
}


// Check if customizable option exists
if (!function_exists('gravity_check_theme_option')) {
	function gravity_check_theme_option($name) {
		return gravity_storage_isset('options', $name);
	}
}

// Get dependencies list from the Theme Options
if ( !function_exists('gravity_get_theme_dependencies') ) {
	function gravity_get_theme_dependencies() {
		$options = gravity_storage_get('options');
		$depends = array();
		foreach ($options as $k=>$v) {
			if (isset($v['dependency'])) 
				$depends[$k] = $v['dependency'];
		}
		return $depends;
	}
}

// Return internal theme setting value
if (!function_exists('gravity_get_theme_setting')) {
	function gravity_get_theme_setting($name) {
		return gravity_storage_isset('settings', $name) ? gravity_storage_get_array('settings', $name) : false;
	}
}


// Set theme setting
if ( !function_exists( 'gravity_set_theme_setting' ) ) {
	function gravity_set_theme_setting($option_name, $value) {
		if (gravity_storage_isset('settings', $option_name))
			gravity_storage_set_array('settings', $option_name, $value);
	}
}
?>