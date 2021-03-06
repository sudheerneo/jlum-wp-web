<?php
/**
 * Plugin Name: Social Icons Widget by WPZOOM
 * Plugin URI: http://www.wpzoom.com/
 * Description: Social Icons Widget to displays links to social sharing websites. Supports more than 80 sites and includes 400 icons. Sort icons by Drag & Drop and change their color easily.
 * Author: WPZOOM
 * Author URI: http://www.wpzoom.com/
 * Version: 2.2.2
 * License: GPLv2 or later
 * Text Domain: zoom-social-icons-widget
 * Domain Path: /languages
 */

require_once plugin_dir_path( __FILE__ ) . 'class.zoom-social-icons-widget.php';

/**
* Load textdomain
 */
function zoom_social_icons_widget_load_textdomain() {
    load_plugin_textdomain( 'zoom-social-icons-widget', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'zoom_social_icons_widget_load_textdomain' );

/**
* Register the widget
 */
add_action( 'widgets_init', 'zoom_social_icons_widget_register' );
function zoom_social_icons_widget_register() {
	register_widget( 'Zoom_Social_Icons_Widget' );
}