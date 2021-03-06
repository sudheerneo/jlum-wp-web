<?php
/**
 * Customizer Extension for Hestia Companion
 *
 * @package Hestia Companion
 * @since 1.0.0
 */

if ( ! function_exists( 'hestia_companion_customize_register' ) ) :
	/**
	 * Hestia Companion Customize Register
	 */
	function hestia_companion_customize_register( $wp_customize ) {

		// Change defaults for customizer controls for features section.
		$hestia_features_title_control = $wp_customize->get_setting( 'hestia_features_title' );
		if ( ! empty( $hestia_features_title_control ) ) {
			$hestia_features_title_control->default = esc_html__( 'Why our product is the best', 'hestia' );
		}

		$hestia_features_subtitle_control = $wp_customize->get_setting( 'hestia_features_subtitle' );
		if ( ! empty( $hestia_features_subtitle_control ) ) {
			$hestia_features_subtitle_control->default = esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' );
		}

		$hestia_features_content_control = $wp_customize->get_setting( 'hestia_features_content' );
		if ( ! empty( $hestia_features_content_control ) ) {
			$hestia_features_content_control->default = json_encode( array(
				array(
				'icon_value' => 'fa-wechat',
				'title'      => esc_html__( 'Responsive', 'hestia' ),
				'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ),
				'link'       => '#',
				'id'         => 'customizer_repeater_56d7ea7f40b56',
				'color'      => '#e91e63',
				),
				array(
				'icon_value' => 'fa-check',
				'title'      => esc_html__( 'Quality', 'hestia' ),
				'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ),
				'link'       => '#',
				'id'         => 'customizer_repeater_56d7ea7f40b66',
				'color'      => '#00bcd4',
				),
				array(
				'icon_value' => 'fa-support',
				'title'      => esc_html__( 'Support', 'hestia' ),
				'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ),
				'link'       => '#',
				'id'         => 'customizer_repeater_56d7ea7f40b86',
				'color'      => '#4caf50',
				),
			) );

		}

		// Change defaults for customizer controls for team section.
		$hestia_team_title_control = $wp_customize->get_setting( 'hestia_team_title' );
		if ( ! empty( $hestia_team_title_control ) ) {
			$hestia_team_title_control->default = esc_html__( 'Meet our team', 'hestia' );
		}
		$hestia_team_subtitle_control = $wp_customize->get_setting( 'hestia_team_subtitle' );
		if ( ! empty( $hestia_team_subtitle_control ) ) {
			$hestia_team_subtitle_control->default = esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' );
		}
		$hestia_team_content_control = $wp_customize->get_setting( 'hestia_team_content' );
		if ( ! empty( $hestia_team_content_control ) ) {
			$hestia_team_content_control->default = json_encode( array(
				array(
				'image_url'       => get_template_directory_uri() . '/assets/img/1.jpg',
				'title'           => esc_html__( 'Desmond Purpleson', 'hestia' ),
				'subtitle'        => esc_html__( 'CEO', 'hestia' ),
				'text'            => esc_html__( 'Locavore pinterest chambray affogato art party, forage coloring book typewriter. Bitters cold selfies, retro celiac sartorial mustache.', 'hestia' ),
				'id'              => 'customizer_repeater_56d7ea7f40c56',
				'social_repeater' => json_encode( array(
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb908674e06',
						'link' => 'facebook.com',
						'icon' => 'fa-facebook',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb9148530ft',
						'link' => 'plus.google.com',
						'icon' => 'fa-google-plus',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb9148530fc',
						'link' => 'twitter.com',
						'icon' => 'fa-twitter',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb9150e1e89',
						'link' => 'linkedin.com',
						'icon' => 'fa-linkedin',
					),
				) ),
				),
				array(
				'image_url'       => get_template_directory_uri() . '/assets/img/2.jpg',
				'title'           => esc_html__( 'Parsley Pepperspray', 'hestia' ),
				'subtitle'        => esc_html__( 'Marketing Specialist', 'hestia' ),
				'text'            => esc_html__( 'Craft beer salvia celiac mlkshk. Pinterest celiac tumblr, portland salvia skateboard cliche thundercats. Tattooed chia austin hell.', 'hestia' ),
				'id'              => 'customizer_repeater_56d7ea7f40c66',
				'social_repeater' => json_encode( array(
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb9155a1072',
						'link' => 'facebook.com',
						'icon' => 'fa-facebook',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb9160ab683',
						'link' => 'twitter.com',
						'icon' => 'fa-twitter',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb9160ab484',
						'link' => 'pinterest.com',
						'icon' => 'fa-pinterest',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb916ddffc9',
						'link' => 'linkedin.com',
						'icon' => 'fa-linkedin',
					),
				) ),
				),
				array(
				'image_url'       => get_template_directory_uri() . '/assets/img/3.jpg',
				'title'           => esc_html__( 'Desmond Eagle', 'hestia' ),
				'subtitle'        => esc_html__( 'Graphic Designer', 'hestia' ),
				'text'            => esc_html__( 'Pok pok direct trade godard street art, poutine fam typewriter food truck narwhal kombucha wolf cardigan butcher whatever pickled you.', 'hestia' ),
				'id'              => 'customizer_repeater_56d7ea7f40c76',
				'social_repeater' => json_encode( array(
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb917e4c69e',
						'link' => 'facebook.com',
						'icon' => 'fa-facebook',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb91830825c',
						'link' => 'twitter.com',
						'icon' => 'fa-twitter',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb918d65f2e',
						'link' => 'linkedin.com',
						'icon' => 'fa-linkedin',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb918d65f2x',
						'link' => 'dribbble.com',
						'icon' => 'fa-dribbble',
					),
				) ),
				),
				array(
				'image_url'       => get_template_directory_uri() . '/assets/img/4.jpg',
				'title'           => esc_html__( 'Ruby Von Rails', 'hestia' ),
				'subtitle'        => esc_html__( 'Lead Developer', 'hestia' ),
				'text'            => esc_html__( 'Small batch vexillologist 90\'s blue bottle stumptown bespoke. Pok pok tilde fixie chartreuse, VHS gluten-free selfies wolf hot.', 'hestia' ),
				'id'              => 'customizer_repeater_56d7ea7f40c86',
				'social_repeater' => json_encode( array(
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb925cedcg5',
						'link' => 'github.com',
						'icon' => 'fa-github-square',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb925cedcb2',
						'link' => 'facebook.com',
						'icon' => 'fa-facebook',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb92615f030',
						'link' => 'twitter.com',
						'icon' => 'fa-twitter',
					),
					array(
						'id'   => 'customizer-repeater-social-repeater-57fb9266c223a',
						'link' => 'linkedin.com',
						'icon' => 'fa-linkedin',
					),
				) ),
				),
			) );
		}// End if().

		$hestia_testimonials_title_setting = $wp_customize->get_setting( 'hestia_testimonials_title' );
		if ( ! empty( $hestia_testimonials_title_setting ) ) {
			$hestia_testimonials_title_setting->default = esc_html__( 'What clients say', 'hestia' );
		}
		$hestia_testimonials_subtitle_setting = $wp_customize->get_setting( 'hestia_testimonials_subtitle' );
		if ( ! empty( $hestia_testimonials_subtitle_setting ) ) {
			$hestia_testimonials_subtitle_setting->default = esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' );
		}
		$hestia_testimonials_content_setting = $wp_customize->get_setting( 'hestia_testimonials_content' );
		if ( ! empty( $hestia_testimonials_content_setting ) ) {
			$hestia_testimonials_content_setting->default = json_encode( array(
				array(
				'image_url' => get_template_directory_uri() . '/assets/img/5.jpg',
				'title'     => esc_html__( 'Inverness McKenzie', 'hestia' ),
				'subtitle'  => esc_html__( 'Business Owner', 'hestia' ),
				'text'      => esc_html__( '"We have no regrets! After using your product my business skyrocketed! I made back the purchase price in just 48 hours! I couldn\'t have asked for more than this."', 'hestia' ),
				'id'        => 'customizer_repeater_56d7ea7f40d56',
				),
				array(
				'image_url' => get_template_directory_uri() . '/assets/img/6.jpg',
				'title'     => esc_html__( 'Hanson Deck', 'hestia' ),
				'subtitle'  => esc_html__( 'Independent Artist', 'hestia' ),
				'text'      => esc_html__( '"Your company is truly upstanding and is behind its product 100 percent. Hestia is worth much more than I paid. I like Hestia more each day because it makes easier."', 'hestia' ),
				'id'        => 'customizer_repeater_56d7ea7f40d66',
				),
				array(
				'image_url' => get_template_directory_uri() . '/assets/img/7.jpg',
				'title'     => esc_html__( 'Natalya Undergrowth', 'hestia' ),
				'subtitle'  => esc_html__( 'Freelancer', 'hestia' ),
				'text'      => esc_html__( '"Thank you for making it painless, pleasant and most of all hassle free! I am so pleased with this product. Dude, your stuff is great! I will refer everyone I know."', 'hestia' ),
				'id'        => 'customizer_repeater_56d7ea7f40d76',
				),
			) );
		}

	}

	add_action( 'customize_register', 'hestia_companion_customize_register' );
endif;
