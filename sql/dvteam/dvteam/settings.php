<?php
defined( 'ABSPATH' ) || exit;

class DVteamSettings {
    /* The single instance of the class */
	protected static $_instance = null;

    /* Main Instance */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    /* Constructor */
    public function __construct() {
        add_action( 'cmb2_admin_init', array($this, 'register_metabox') );
        add_action( 'admin_enqueue_scripts',array($this, 'colorpicker_labels'), 99 );
    }

    /**
    * Hook in and register a metabox to handle a plugin options page and adds a menu item.
    */
    public function register_metabox() {
        // GENERAL SETTINGS
        $args = array(
            'id'           => 'dvteam_options',
            'title'        => esc_html__('DVteam Settings', 'dvteam'),
            'object_types' => array( 'options-page' ),
            'option_key'   => 'dvteam_options',
            'tab_group'    => 'dvteam_options',
            'tab_title'    => esc_html__('General', 'dvteam')
        );
        
        // 'tab_group' property is supported in > 2.4.0.
        if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
            $args['display_cb'] = array($this, 'display_with_tabs');
        }
        
        $main_options = new_cmb2_box( $args );

        $main_options->add_field( array(
            'name' => esc_html__( 'Custom CSS', 'dvteam' ),
            'id' => 'custom_css',
            'type' => 'textarea_code',
            'attributes' => array(
                'data-codeeditor' => json_encode( array(
                    'codemirror' => array(
                        'mode' => 'css'
                    ),
                ) ),
            ),
        ) );

        $main_options->add_field(
            array(
                'name' => esc_html__( 'Bold Titles', 'dvteam'),  
                'id' => 'boldtitles',
                'type' => 'radio_inline',
                'options' => array(
                    'enable' => esc_html__( 'Enable', 'dvteam' ),
                    'disable'   => esc_html__( 'Disable', 'dvteam' )
                ),
                'default' => 'disable',
            )
        );

        $main_options->add_field( array(
            'name' => esc_html__( 'Title bottom margin (px)', 'dvteam' ),
            'id'   => 'titlemargin',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 20,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Divider Height (px)', 'dvteam' ),
            'id'   => 'pdividersize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 1,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Colors', 'dvteam' ),
            'type' => 'title',
            'id'   => 'general_colors_subtitle'
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Title', 'dvteam' ),
            'id'   => 'phtitlescolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Text', 'dvteam' ),
            'id'   => 'ptextcolor',
            'type' => 'colorpicker',
            'default' => '#c7c7c7',
            'options' => array(
                'alpha' => false
            )
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Divider', 'dvteam' ),
            'id'   => 'pdividercolor',
            'type' => 'colorpicker',
            'default' => '#414141',
            'options' => array(
                'alpha' => false
            )
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Quote', 'dvteam' ),
            'id'   => 'quotescolor',
            'type' => 'colorpicker',
            'default' => '#414141',
            'options' => array(
                'alpha' => false
            )
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Carousel Arrow Background', 'dvteam' ),
            'id'   => 'cararrowbg',
            'type' => 'colorpicker',
            'default' => '#313131',
            'options' => array(
                'alpha' => false
            )
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Font Sizes', 'dvteam' ),
            'type' => 'title',
            'id'   => 'general_fonts_subtitle'
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'H1 (px)', 'dvteam' ),
            'id'   => 'h1',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 34,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'H2 (px)', 'dvteam' ),
            'id'   => 'h2',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 28,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'H3 (px)', 'dvteam' ),
            'id'   => 'h3',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 24,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'H4 (px)', 'dvteam' ),
            'id'   => 'h4',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 20,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'H5 (px)', 'dvteam' ),
            'id'   => 'h5',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 18,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'H6 (px)', 'dvteam' ),
            'id'   => 'h6',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 16,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Regular Text (px)', 'dvteam' ),
            'id'   => 'p',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 14,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Quote Text (px)', 'dvteam' ),
            'id'   => 'quote',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 28,
        ) );

        $main_options->add_field( array(
            'name' => esc_html__( 'Quotes (em)', 'dvteam' ),
            'id'   => 'quotemarks',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 6,
        ) );

        
        // PANEL SETTINGS
        $args = array(
            'id'           => 'dvteam_options_1',
            'title'        => esc_html__('DVteam Settings', 'dvteam'),
            'menu_title'   => esc_html__('Panel', 'dvteam'),
            'object_types' => array( 'options-page' ),
            'option_key'   => 'dvteam_options_1',
            'parent_slug'  => 'dvteam_options',
            'tab_group'    => 'dvteam_options',
            'tab_title'    => esc_html__('Panel', 'dvteam'),
        );
        
        // 'tab_group' property is supported in > 2.4.0.
        if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
            $args['display_cb'] = array($this, 'display_with_tabs');
        }
        
        $options_1 = new_cmb2_box( $args );

        $options_1->add_field(
            array(
                'name' => esc_html__( 'Activate Body Scroll Effect', 'dvteam'),  
                'description' => esc_html__( 'It may not work properly on some themes.', 'dvteam'),  
                'id' => 'activatescroll',
                'type' => 'radio_inline',
                'options' => array(
                    'enable' => esc_html__( 'Enable', 'dvteam' ),
                    'disable'   => esc_html__( 'Disable', 'dvteam' )
                ),
                'default' => 'enable',
            )
        );

        $options_1->add_field( array(
            'name' => esc_html__( 'Panel Width (px)', 'dvteam' ),
            'id'   => 'panelwidth',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 640,
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Inner Spacing (px)', 'dvteam' ),
            'id'   => 'spaceinner',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 30,
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Main Title Font Size (px)', 'dvteam' ),
            'id'   => 'ptitlesize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 28,
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Sub Title Font Size (px)', 'dvteam' ),
            'id'   => 'psubtitlesize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 18,
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Overlay Opacity', 'dvteam' ),
            'id'   => 'overlayopacity',
            'type' => 'select',
            'default' => '0.7',
            'options' => array(
                '0' => '0',
                '0.1' => '0.1',
                '0.2' => '0.2',
                '0.3' => '0.3',
                '0.4' => '0.4',
                '0.5' => '0.5',
                '0.6' => '0.6',
                '0.7' => '0.7',
                '0.8' => '0.8',
                '0.9' => '0.9',
                '1' => '1'
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Colors', 'dvteam' ),
            'type' => 'title',
            'id'   => 'panel_colors_subtitle'
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Custom Scrollbar', 'dvteam' ),
            'id'   => 'scrollbarcolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Overlay', 'dvteam' ),
            'id'   => 'overlay_color',
            'type' => 'colorpicker',
            'default' => '#212121',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Panel Background', 'dvteam' ),
            'id'   => 'panelbgcolor',
            'type' => 'colorpicker',
            'default' => '#313131',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Social Bar Background', 'dvteam' ),
            'id'   => 'socialbgcolor',
            'type' => 'colorpicker',
            'default' => '#212121',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Main Title', 'dvteam' ),
            'id'   => 'ptitlecolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Main Title Background', 'dvteam' ),
            'id'   => 'ptitlebgcolor',
            'type' => 'colorpicker',
            'default' => '#eb5656',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Sub Title', 'dvteam' ),
            'id'   => 'psubtitlecolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Sub Title Background', 'dvteam' ),
            'id'   => 'psubtitlebgcolor',
            'type' => 'colorpicker',
            'default' => '#414141',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Contact Form 7 Color 1', 'dvteam' ),
            'id'   => 'cfcolorone',
            'type' => 'colorpicker',
            'default' => '#414141',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Contact Form 7 Color 2', 'dvteam' ),
            'id'   => 'cfcolortwo',
            'type' => 'colorpicker',
            'default' => '#eb5656',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_1->add_field( array(
            'name' => esc_html__( 'Contact Form 7 Color 3', 'dvteam' ),
            'id'   => 'cfcolorthree',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );
        
        // GRID SETTINGS
        $args = array(
            'id'           => 'dvteam_options_2',
            'title'        => esc_html__('DVteam Settings', 'dvteam'),
            'menu_title'   => esc_html__('Grid', 'dvteam'),
            'object_types' => array( 'options-page' ),
            'option_key'   => 'dvteam_options_2',
            'parent_slug'  => 'dvteam_options',
            'tab_group'    => 'dvteam_options',
            'tab_title'    => esc_html__('Grid', 'dvteam'),
        );
        
        // 'tab_group' property is supported in > 2.4.0.
        if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
            $args['display_cb'] = array($this, 'display_with_tabs');
        }
        
        $options_2 = new_cmb2_box( $args );

        $options_2->add_field(
            array(
                'name' => esc_html__( 'Align', 'dvteam'),  
                'description' => esc_html__( 'Thumbnail Horizontal Alignment.', 'dvteam'),  
                'id' => 'thumbnailalign',
                'type' => 'radio_inline',
                'options' => array(
                    'left' => esc_html__( 'Left', 'dvteam' ),
                    'center'   => esc_html__( 'Center', 'dvteam' ),
                    'right'   => esc_html__( 'Right', 'dvteam' )
                ),
                'default' => 'left',
            )
        );

        $options_2->add_field( array(
            'name' => esc_html__( 'Thumbnail Hover Opacity', 'dvteam' ),
            'id'   => 'thumbnailopacity',
            'type' => 'select',
            'default' => '0.1',
            'options' => array(
                '0' => '0',
                '0.1' => '0.1',
                '0.2' => '0.2',
                '0.3' => '0.3',
                '0.4' => '0.4',
                '0.5' => '0.5',
                '0.6' => '0.6',
                '0.7' => '0.7',
                '0.8' => '0.8',
                '0.9' => '0.9',
                '1' => '1'
            )
        ) );

        $options_2->add_field(
            array(
                'name' => esc_html__( 'Thumbnail Zoom Effect', 'dvteam'),
                'id' => 'removezoom',
                'type' => 'radio_inline',
                'options' => array(
                    'enable' => esc_html__( 'Enable', 'dvteam' ),
                    'disable'   => esc_html__( 'Disable', 'dvteam' )
                ),
                'default' => 'enable',
            )
        );

        $options_2->add_field(
            array(
                'name' => esc_html__( 'Info Icon', 'dvteam'),
                'id' => 'removeinfo',
                'type' => 'radio_inline',
                'options' => array(
                    'enable' => esc_html__( 'Enable', 'dvteam' ),
                    'disable'   => esc_html__( 'Disable', 'dvteam' )
                ),
                'default' => 'enable',
            )
        );

        $options_2->add_field(
            array(
                'name' => esc_html__( 'Thumbnail Text Effect', 'dvteam'),
                'id' => 'removetextanim',
                'type' => 'radio_inline',
                'options' => array(
                    'enable' => esc_html__( 'Enable', 'dvteam' ),
                    'disable'   => esc_html__( 'Disable', 'dvteam' )
                ),
                'default' => 'enable',
            )
        );

        $options_2->add_field( array(
            'name' => esc_html__( 'Colors', 'dvteam' ),
            'type' => 'title',
            'id'   => 'grid_colors_subtitle'
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Thumbnail Overlay', 'dvteam' ),
            'id'   => 'thumbnailoverlay',
            'type' => 'colorpicker',
            'default' => '#212121',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Info Icon Background', 'dvteam' ),
            'id'   => 'infobgcolor',
            'type' => 'colorpicker',
            'default' => '#eb5656',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Title', 'dvteam' ),
            'id'   => 'imgtitlecolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Title', 'dvteam' ),
            'id'   => 'imgtitlebgcolor',
            'type' => 'colorpicker',
            'default' => '#eb5656',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Sub Title', 'dvteam' ),
            'id'   => 'imgsubtitlecolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Sub Title Background', 'dvteam' ),
            'id'   => 'imgsubtitlebgcolor',
            'type' => 'colorpicker',
            'default' => '#313131',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Filter', 'dvteam' ),
            'id'   => 'filtercolor',
            'type' => 'colorpicker',
            'default' => '#414141',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Filter Hover', 'dvteam' ),
            'id'   => 'filterhovercolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Filter Active', 'dvteam' ),
            'id'   => 'filteractivecolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Filter Background', 'dvteam' ),
            'id'   => 'filterbgcolor',
            'type' => 'colorpicker',
            'default' => '#f5f1f0',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Filter Hover Background', 'dvteam' ),
            'id'   => 'filterbghovercolor',
            'type' => 'colorpicker',
            'default' => '#414141',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Active Filter Background', 'dvteam' ),
            'id'   => 'filterbgactivecolor',
            'type' => 'colorpicker',
            'default' => '#eb5656',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Font Sizes', 'dvteam' ),
            'type' => 'title',
            'id'   => 'grid_fonts_subtitle'
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Title (px)', 'dvteam' ),
            'id'   => 'imgtitlesize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 18,
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Sub Title (px)', 'dvteam' ),
            'id'   => 'imgsubtitlesize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 14,
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Filter (px)', 'dvteam' ),
            'id'   => 'filterfont',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 18,
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Grid Filter Menu', 'dvteam' ),
            'type' => 'title',
            'id'   => 'grid_filter_subtitle'
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Outer Spacing', 'dvteam' ),
            'description' => esc_html__( 'The distance between filter menu and members (px)', 'dvteam'),
            'id'   => 'filterbottom',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 20,
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Menu Item Horizontal Spacing', 'dvteam' ),
            'description' => esc_html__( 'The distance between filter menu and members (px)', 'dvteam'),
            'id'   => 'filterhorizontal',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 15,
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Menu Item Vertical Spacing', 'dvteam' ),
            'description' => esc_html__( 'Menu item top-bottom spacing (px)', 'dvteam'),
            'id'   => 'filtervertical',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 5,
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Pagination', 'dvteam' ),
            'type' => 'title',
            'id'   => 'grid_pagination_subtitle'
        ) );

        $options_2->add_field(
            array(
                'name' => esc_html__( 'Pagination', 'dvteam'),
                'description' => esc_html__( 'If you have multiple shortcodes on the same page, pagination may not work properly.', 'dvteam'),
                'id' => 'pagination',
                'type' => 'radio_inline',
                'options' => array(
                    'enable' => esc_html__( 'Enable', 'dvteam' ),
                    'disable'   => esc_html__( 'Disable', 'dvteam' )
                ),
                'default' => 'disable',
            )
        );

        $options_2->add_field( array(
            'name' => esc_html__( 'Color', 'dvteam' ),
            'id'   => 'paginationfont',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Background', 'dvteam' ),
            'id'   => 'paginationbg',
            'type' => 'colorpicker',
            'default' => '#212121',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_2->add_field( array(
            'name' => esc_html__( 'Hover Background', 'dvteam' ),
            'id'   => 'paginationbg',
            'type' => 'colorpicker',
            'default' => '#eb5656',
            'options' => array(
                'alpha' => false
            )
        ) );

        // SKILLS SETTINGS
        $args = array(
            'id'           => 'dvteam_options_3',
            'title'        => esc_html__('DVteam Settings', 'dvteam'),
            'menu_title'   => esc_html__('Skills', 'dvteam'),
            'object_types' => array( 'options-page' ),
            'option_key'   => 'dvteam_options_3',
            'parent_slug'  => 'dvteam_options',
            'tab_group'    => 'dvteam_options',
            'tab_title'    => esc_html__('Skills', 'dvteam'),
        );
        
        // 'tab_group' property is supported in > 2.4.0.
        if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
            $args['display_cb'] = array($this, 'display_with_tabs');
        }
        
        $options_3 = new_cmb2_box( $args );

        $options_3->add_field( array(
            'name' => esc_html__( 'Skill Font Size (px)', 'dvteam' ),
            'id'   => 'skillsize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 14,
        ) );

        $options_3->add_field( array(
            'name' => esc_html__( 'Percent Font Size (px)', 'dvteam' ),
            'id'   => 'percentsize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 14,
        ) );

        $options_3->add_field( array(
            'name' => esc_html__( 'Skill Bar Border Size (px)', 'dvteam' ),
            'id'   => 'bordersize',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'default' => 1,
        ) );

        $options_3->add_field( array(
            'name' => esc_html__( 'Skill Color', 'dvteam' ),
            'id'   => 'skillcolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_3->add_field( array(
            'name' => esc_html__( 'Percent Color', 'dvteam' ),
            'id'   => 'percentcolor',
            'type' => 'colorpicker',
            'default' => '#ffffff',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_3->add_field( array(
            'name' => esc_html__( 'Skill Background', 'dvteam' ),
            'id'   => 'skillbg',
            'type' => 'colorpicker',
            'default' => '#212121',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_3->add_field( array(
            'name' => esc_html__( 'Skill Bar Background', 'dvteam' ),
            'id'   => 'skillbarbg',
            'type' => 'colorpicker',
            'default' => '#212121',
            'options' => array(
                'alpha' => false
            )
        ) );

        $options_3->add_field( array(
            'name' => esc_html__( 'Skill Bar Border Color', 'dvteam' ),
            'id'   => 'bordercolor',
            'type' => 'colorpicker',
            'default' => '#414141',
            'options' => array(
                'alpha' => false
            )
        ) );
        
    }

    /**
    * Gets navigation tabs array for CMB2 options pages which share the given
    * display_cb param.
    *
    * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
    *
    * @return array Array of tab information.
    */
    public function page_tabs( $cmb_options ) {
        $tab_group = $cmb_options->cmb->prop( 'tab_group' );
        $tabs      = array();
        
        foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
            if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
                $tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
                    ? $cmb->prop( 'tab_title' )
                    : $cmb->prop( 'title' );
            }
        }
        
        return $tabs;
    }

    /**
    * A CMB2 options-page display callback override which adds tab navigation among
    * CMB2 options pages which share this same display callback.
    *
    * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
    */
    public function display_with_tabs( $cmb_options ) {
        $tabs = $this->page_tabs( $cmb_options );
        ?>
        <div class="wrap cmb2-options-page option-<?php echo esc_attr($cmb_options->option_key); ?>">
            <?php if ( get_admin_page_title() ) : ?>
                <h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
            <?php endif; ?>
            <h2 class="nav-tab-wrapper">
                <?php foreach ( $tabs as $option_key => $tab_title ) : ?>
                    <a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
                <?php endforeach; ?>
            </h2>
            <form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo esc_attr($cmb_options->cmb->cmb_id); ?>" enctype="multipart/form-data" encoding="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
                <?php $cmb_options->options_page_metabox(); ?>
                <?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
            </form>
        </div>
    <?php
    }

    /**
    * Get Forms
    */
    public function get_forms() {
        $wp_query = new WP_Query(
            array('post_type' => 'dvteam', 'posts_per_page' => 99, 'post_status' => 'publish') 
        );
        $array = array();
        while($wp_query->have_posts()) : $wp_query->the_post();
        $array[get_the_id()] = get_the_title();
        endwhile;

        wp_reset_postdata();

        return $array;
    }

    /**
    * Get Role Names Except Administrator & Editor
    */
    public function get_role_names() {
        global $wp_roles; 
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles(); 
        }
        $roles = $wp_roles->get_names();
        unset($roles['administrator']);
        unset($roles['editor']);
        return $roles;
    }

    /**
    * Colorpicker Labels
    */
    function colorpicker_labels( $hook ) {
        global $wp_version;
        if( version_compare( $wp_version, '5.4.2' , '>=' ) ) {
            wp_localize_script(
            'wp-color-picker',
            'wpColorPickerL10n',
            array(
                'clear'            => esc_html__( 'Clear', 'dvteam' ),
                'clearAriaLabel'   => esc_html__( 'Clear color', 'dvteam' ),
                'defaultString'    => esc_html__( 'Default', 'dvteam' ),
                'defaultAriaLabel' => esc_html__( 'Select default color', 'dvteam' ),
                'pick'             => esc_html__( 'Select Color', 'dvteam' ),
                'defaultLabel'     => esc_html__( 'Color value', 'dvteam' )
            )
            );
        }
    }

}

/**
 * Returns the main instance of TMuserBlog.
 */
function DVteamSettings() {  
	return DVteamSettings::instance();
}
// Global for backwards compatibility.
$GLOBALS['DVteamSettings'] = DVteamSettings();


/**
* Custom Get option
*/

function dvteam_get_option( $tab = 'dvteam_options', $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		return cmb2_get_option( $tab, $key, $default );
	}
	$opts = get_option( $tab, $default );
	$val = $default;
	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}
	return $val;
}