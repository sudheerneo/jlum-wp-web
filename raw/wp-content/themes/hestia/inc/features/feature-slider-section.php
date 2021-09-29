<?php
/**
 * Customizer functionality for the Slider section.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

// Load Customizer repeater control.
require_once( trailingslashit( get_template_directory() ) . '/inc/customizer-repeater/functions.php' );

/**
 * Hook controls for Slider section to Customizer.
 *
 * @since Hestia 1.0
 */
function hestia_slider_customize_register( $wp_customize ) {

	$wp_customize->add_section( 'hestia_slider', array(
		'title' => esc_html__( 'Slider', 'hestia-pro' ),
		'panel' => 'hestia_frontpage_sections',
		'priority' => 5,
	));

	$wp_customize->add_setting( 'hestia_slider_content', array(
		'sanitize_callback' => 'hestia_repeater_sanitize',
		'default' => json_encode( array(
			array(
				'image_url' => get_template_directory_uri() . '/assets/img/slider1.jpg',
				'title' => esc_html__( 'Lorem Ipsum', 'hestia-pro' ),
				'subtitle' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
				'text' => esc_html__( 'Button', 'hestia-pro' ),
				'link' => '#',
				'id' => 'customizer_repeater_56d7ea7f40a56',
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/img/slider2.jpg',
				'title' => esc_html__( 'Lorem Ipsum', 'hestia-pro' ),
				'subtitle' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
				'text' => esc_html__( 'Button', 'hestia-pro' ),
				'link' => '#',
				'id' => 'customizer_repeater_56d7ea7f40a57',
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/img/slider3.jpg',
				'title' => esc_html__( 'Lorem Ipsum', 'hestia-pro' ),
				'subtitle' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
				'text' => esc_html__( 'Button', 'hestia-pro' ),
				'link' => '#',
				'id' => 'customizer_repeater_56d7ea7f40a58',
			),
		)),
	));

	$wp_customize->add_control( new Hestia_Repeater( $wp_customize, 'hestia_slider_content', array(
		'label'   => esc_html__( 'Slider Content','hestia-pro' ),
		'section' => 'hestia_slider',
		'priority' => 1,
		'customizer_repeater_image_control' => true,
		'customizer_repeater_title_control' => true,
		'customizer_repeater_subtitle_control' => true,
		'customizer_repeater_text_control' => true,
		'customizer_repeater_link_control' => true,
	)));

}

add_action( 'customize_register', 'hestia_slider_customize_register' );
