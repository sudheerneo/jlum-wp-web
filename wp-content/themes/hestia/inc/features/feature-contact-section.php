<?php
/**
 * Customizer functionality for the Contact section.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

// Load Authors multiple select control.
$contact_info_path = trailingslashit( get_template_directory() ) . 'inc/customizer-contact-info/class-hestia-contact-info.php';
if ( file_exists( $contact_info_path ) ) {
	require_once( $contact_info_path );
}

/**
 * Hook controls for Contact section to Customizer.
 *
 * @since Hestia 1.0
 */
function hestia_contact_customize_register( $wp_customize ) {

	$wp_customize->add_section( 'hestia_contact', array(
		'title' => esc_html__( 'Contact', 'hestia' ),
		'panel' => 'hestia_frontpage_sections',
		'priority' => apply_filters( 'hestia_section_priority', 55, 'hestia_contact' ),
	));

	$wp_customize->add_setting( 'hestia_contact_hide', array(
		'sanitize_callback' => 'hestia_sanitize_checkbox',
		'default' => false,
	) );

	$wp_customize->add_control( 'hestia_contact_hide', array(
		'type' => 'checkbox',
		'label' => esc_html__( 'Disable section','hestia' ),
		'section' => 'hestia_contact',
		'priority' => 1,
	) );

	$wp_customize->add_setting( 'hestia_contact_background', array(
		'default' => get_template_directory_uri() . '/assets/img/contact.jpg',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'postMessage',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hestia_contact_background', array(
		'label' => esc_html__( 'Background Image', 'hestia' ),
		'section' => 'hestia_contact',
		'priority' => 5,
	)));

	$wp_customize->add_setting( 'hestia_contact_title', array(
		'default' => esc_html__( 'Get in Touch', 'hestia' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'postMessage',
	));

	$wp_customize->add_control( 'hestia_contact_title', array(
		'label' => esc_html__( 'Section Title', 'hestia' ),
		'section' => 'hestia_contact',
		'priority' => 10,
	));

	$wp_customize->add_setting( 'hestia_contact_subtitle', array(
		'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'postMessage',
	));

	$wp_customize->add_control( 'hestia_contact_subtitle', array(
		'label' => esc_html__( 'Section Subtitle', 'hestia' ),
		'section' => 'hestia_contact',
		'priority' => 15,
	));

	$wp_customize->add_setting( 'hestia_contact_area_title', array(
		'default' => esc_html__( 'Contact Us', 'hestia' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'postMessage',
	));

	$wp_customize->add_control( 'hestia_contact_area_title', array(
		'label' => esc_html__( 'Form Title', 'hestia' ),
		'section' => 'hestia_contact',
		'priority' => 20,
	));

	$wp_customize->add_setting( 'hestia_contact_info', array(
		'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control( new Hestia_Contact_Info( $wp_customize, 'hestia_contact_info', array(
		'label' => esc_html__( 'Instructions', 'hestia' ),
		'section' => 'hestia_contact',
		'capability' => 'install_plugins',
		'priority' => 25,
	)));

	$wp_customize->add_setting( 'hestia_contact_content_new', array(
		'sanitize_callback' => 'wp_kses_post',
		'default' => wp_kses_post( hestia_contact_get_old_content( 'hestia_contact_content' ) ),
	));

	$wp_customize->add_control( 'hestia_contact_content_new', array(
		'label'   => esc_html__( 'Contact Content','hestia' ),
		'type'    => 'textarea',
		'section' => 'hestia_contact',
		'priority' => 30,
	));

}

add_action( 'customize_register', 'hestia_contact_customize_register' );
