<?php
/**
 * Plugin Name: DV Team
 * Plugin URI: http://codecanyon.net/item/dv-team-responsive-team-showcase-wordpress-plugin/9962337
 * Description: Responsive Team Showcase Plugin
 * Version: 2.1
 * Author: Egemenerd
 * Author URI: http://codecanyon.net/user/egemenerd
 * License: http://codecanyon.net/licenses
 * Text Domain: dvteam
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

/*---------------------------------------------------
custom image sizes
----------------------------------------------------*/

add_image_size( 'dv-team-rectangle', 600, 400, true);
add_image_size( 'dv-team-square', 600, 600, true);
add_filter('image_size_names_choose', 'dvteam_image_sizes');

function dvteam_image_sizes($gridsizes) {
    $gridaddsizes = array(
        "dv-team-rectangle" => esc_html__( 'DV-Team Rectangle', 'dvteam'),
        "dv-team-square" => esc_html__( 'DV-Team Square', 'dvteam')
    );
    $gridnewsizes = array_merge($gridsizes, $gridaddsizes);
    return $gridnewsizes;
}

/* ---------------------------------------------------------
Admin scripts
----------------------------------------------------------- */

function dvteam_admin_script($hook){
    wp_enqueue_style('dvteam-admin-main', plugins_url('css/panel-main.css', __FILE__));
    if (( 'toplevel_page_dvteam_options' == $hook ) || ( 'dvteam-settings_page_dvteam_options_1' == $hook ) || ( 'dvteam-settings_page_dvteam_options_2' == $hook ) || ( 'dvteam-settings_page_dvteam_options_3' == $hook )) {
        wp_enqueue_style('dvteam-admin', plugin_dir_url( __FILE__ ) . 'css/admin.css', false, '1.0');
        wp_enqueue_script( 'dvteam-admin', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), '1.0', true ); 
    } else {
        return;
    }
}
add_action( 'admin_enqueue_scripts', 'dvteam_admin_script' );

/* ---------------------------------------------------------
Custom Metaboxes - https://github.com/WebDevStudios/CMB2
----------------------------------------------------------- */

// Check for  PHP version and use the correct dir
$dvteamdir = ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) ? __DIR__ : dirname( __FILE__ );

if ( file_exists(  $dvteamdir . '/cmb2/init.php' ) ) {
	require_once  $dvteamdir . '/cmb2/init.php';
} elseif ( file_exists(  $dvteamdir . '/CMB2/init.php' ) ) {
	require_once  $dvteamdir . '/CMB2/init.php';
}

/* ---------------------------------------------------------
Add Featured Image Support for DV Team Custom Post Type
----------------------------------------------------------- */

function DvteamAddFeatured() {

    global $_wp_theme_features;

    if( empty($_wp_theme_features['post-thumbnails']) ) {
        $_wp_theme_features['post-thumbnails'] = array( array('dvourteam') );
    }

    elseif( true === $_wp_theme_features['post-thumbnails']) {
        return;
    }

    elseif( is_array($_wp_theme_features['post-thumbnails'][0]) ) {
        $_wp_theme_features['post-thumbnails'][0][] = 'dvourteam';
    }
}
add_action( 'after_setup_theme', 'DvteamAddFeatured', 99 );

/* ---------------------------------------------------------
Add Required Featured Image message for DV Team Custom Post Type
----------------------------------------------------------- */

function dvteam_rfi_dont_publish_post( $new_status, $old_status, $post ) {
    if ( $new_status === 'publish' && $old_status !== 'publish'
        && !dvteam_rfi_should_let_post_publish( $post ) ) {
        wp_die( esc_attr__( 'You cannot publish without a featured image.', 'dvteam' ) );
    }
}
add_action( 'transition_post_status', 'dvteam_rfi_dont_publish_post', 10, 3 );

function dvteam_rfi_enqueue_edit_screen_js( $hook ) {

    global $post;
    $post_types = array(
        'dvourteam'
    );
    if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) {
        return;
    }

    if ( in_array( $post->post_type, $post_types ) ) {
        wp_register_script( 'dvteam-rfi-admin-js', plugins_url( 'js/require-featured-image-on-edit.js', __FILE__ ), array( 'jquery' ) );
        wp_enqueue_script( 'dvteam-rfi-admin-js' );
        wp_localize_script(
            'dvteam-rfi-admin-js',
            'objectL10n',
            array(
                'jsWarningHtml' => esc_attr__( 'This member has no featured image. Please set one. You need to set a featured image before publishing.', 'dvteam' ),
            )
        );
    }
}
add_action( 'admin_enqueue_scripts', 'dvteam_rfi_enqueue_edit_screen_js' );

function dvteam_rfi_should_let_post_publish( $post ) {
    global $post;
    $post_types = array(
        'dvourteam'
    );
    return !( in_array( $post->post_type, $post_types ) && !has_post_thumbnail( $post->ID ) );
}

/*---------------------------------------------------
Hide dv-team custom post type post view and post preview links/buttons
----------------------------------------------------*/
function dvteam_posttype_admin_css() {
    global $post_type;
    $post_types = array(
        'dvourteam'
    );
    if(in_array($post_type, $post_types)) { ?>
    <style type="text/css">#post-preview, #view-post-btn, .updated > p > a, #wp-admin-bar-view, #edit-slug-box{display: none !important;}</style>
    <?php }
}
add_action( 'admin_head-post-new.php', 'dvteam_posttype_admin_css' );
add_action( 'admin_head-post.php', 'dvteam_posttype_admin_css' );

function dvteam_row_actions( $actions ) {
    if(get_post_type() == 'dvourteam') {
        unset( $actions['view'] );
    }
    return $actions;
}
add_filter( 'post_row_actions', 'dvteam_row_actions', 10, 1 );

/*---------------------------------------------------
Tinymce custom button
----------------------------------------------------*/

function dvteam_shortcodes_add_button() {  
    if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') ) {  
        add_filter('mce_external_plugins', 'dvteam_add_plugin', 10);  
        add_filter('mce_buttons', 'dvteam_register_button', 10);  
    }  
} 
add_action('init', 'dvteam_shortcodes_add_button');  

function dvteam_register_button($buttons) {
    array_push($buttons, "dvteam_mce_button");
    return $buttons;  
} 

function dvteam_add_plugin($plugin_array) {
    $plugin_array['dvteam_mce_button'] = plugin_dir_url( __FILE__ ) . 'js/shortcodes/shortcodes.js';
    return $plugin_array;  
}

/*---------------------------------------------------
Tinymce Skills and CV Buttons
----------------------------------------------------*/
    
function dvskillshortcodes_add_button() {
    if ( current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_external_plugins', 'dvskill_add_plugin');  
        add_filter('mce_buttons_3', 'dvskill_register_button');  
    }  
} 

add_action('init', 'dvskillshortcodes_add_button');

function dvskill_register_button($buttons) {
    array_push($buttons, "dvskills");
    array_push($buttons, "dvcv");
    return $buttons;  
} 

function dvskill_add_plugin($plugin_array) {
    $plugin_array['dvskills'] = plugin_dir_url( __FILE__ ) . 'js/shortcodes/skills.js';
    $plugin_array['dvcv'] = plugin_dir_url( __FILE__ ) . 'js/shortcodes/skills.js';
    return $plugin_array;  
}

/* ---------------------------------------------------------
Include required files
----------------------------------------------------------- */

include_once('ourteam_cpt.php');
include_once('dvteam_shortcodes.php');
include_once('dvteam_widgets.php');
include_once('settings.php');