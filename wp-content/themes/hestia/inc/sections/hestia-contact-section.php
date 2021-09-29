<?php
/**
 * Contact section for the homepage.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_contact' ) ) :
	/**
	 * Contact section content.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_contact() {
		$hide_section = get_theme_mod( 'hestia_contact_hide', false );
		if ( (bool) $hide_section === true ) {
			return;
		}
		$hestia_contact_title      = get_theme_mod( 'hestia_contact_title', esc_html__( 'Get in Touch', 'hestia' ) );
		$hestia_contact_subtitle   = get_theme_mod( 'hestia_contact_subtitle', esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ) );
		$hestia_contact_area_title = get_theme_mod( 'hestia_contact_area_title', esc_html__( 'Contact Us', 'hestia' ) );
		?>
		<section class="contactus section-image" id="contact" data-sorder="hestia_contact"
				 style="background-image: url('<?php echo get_theme_mod( 'hestia_contact_background', get_template_directory_uri() . '/assets/img/contact.jpg' ); ?>')">
			<div class="container">
				<div class="row">
					<div class="col-md-5">
						<?php if ( ! empty( $hestia_contact_title ) || is_customize_preview() ) : ?>
							<h2 class="title"><?php echo esc_html( $hestia_contact_title ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $hestia_contact_subtitle ) || is_customize_preview() ) : ?>
							<h5 class="description"><?php echo esc_html( $hestia_contact_subtitle ); ?></h5>
						<?php endif; ?>
						<?php

						$hestia_contact_content = get_theme_mod( 'hestia_contact_content_new', wp_kses_post( hestia_contact_get_old_content( 'hestia_contact_content' ) ) );
						if ( ! empty( $hestia_contact_content ) ) {
						    echo '<div class="description">';
						        echo wp_kses_post( $hestia_contact_content );
						    echo '</div>';
						}

						?>
					</div>
					<?php if ( defined( 'PIRATE_FORMS_VERSION' ) ) : ?>
						<div class="col-md-5 col-md-offset-2">
							<div class="card card-contact">
								<?php if ( ! empty( $hestia_contact_area_title ) || is_customize_preview() ) : ?>
									<div class="header header-raised header-primary text-center">
										<h4 class="card-title"><?php echo esc_html( $hestia_contact_area_title ); ?></h4>
									</div>
								<?php endif; ?>
								<div class="content">
									<?php echo do_shortcode( '[pirate_forms]' ) ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
endif;


if ( function_exists( 'hestia_contact' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 55, 'hestia_contact' );
	add_action( 'hestia_sections', 'hestia_contact', absint( $section_priority ) );
}
