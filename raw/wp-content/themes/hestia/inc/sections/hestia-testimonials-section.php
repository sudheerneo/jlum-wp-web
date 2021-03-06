<?php
/**
 * Testimonials section for the homepage.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_testimonials' ) ) :
	/**
	 * Testimonials section content.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_testimonials() {
		$hide_section = get_theme_mod( 'hestia_testimonials_hide', false );
		if ( (bool) $hide_section === true ) {
			return;
		}
		$hestia_testimonials_title    = get_theme_mod( 'hestia_testimonials_title', esc_html__( 'What clients say', 'hestia-pro' ) );
		$hestia_testimonials_subtitle = get_theme_mod( 'hestia_testimonials_subtitle', esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ) );
		?>
		<section class="testimonials" id="testimonials" data-sorder="hestia_testimonials">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 text-center">
						<?php if ( ! empty( $hestia_testimonials_title ) || is_customize_preview() ) : ?>
							<h2 class="title"><?php echo esc_html( $hestia_testimonials_title ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $hestia_testimonials_subtitle ) || is_customize_preview() ) : ?>
							<h5 class="description"><?php echo esc_html( $hestia_testimonials_subtitle ); ?></h5>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<?php
					$hestia_testimonials_content = get_theme_mod( 'hestia_testimonials_content', json_encode( array(
						array(
							'image_url' => get_template_directory_uri() . '/assets/img/5.jpg',
							'title'     => esc_html__( 'Inverness McKenzie', 'hestia-pro' ),
							'subtitle'  => esc_html__( 'Business Owner', 'hestia-pro' ),
							'text'      => esc_html__( '"We have no regrets! After using your product my business skyrocketed! I made back the purchase price in just 48 hours! I couldn\'t have asked for more than this."', 'hestia-pro' ),
							'id'        => 'customizer_repeater_56d7ea7f40d56',
						),
						array(
							'image_url' => get_template_directory_uri() . '/assets/img/6.jpg',
							'title'     => esc_html__( 'Hanson Deck', 'hestia-pro' ),
							'subtitle'  => esc_html__( 'Independent Artist', 'hestia-pro' ),
							'text'      => esc_html__( '"Your company is truly upstanding and is behind its product 100 percent. Hestia is worth much more than I paid. I like Hestia more each day because it makes easier."', 'hestia-pro' ),
							'id'        => 'customizer_repeater_56d7ea7f40d66',
						),
						array(
							'image_url' => get_template_directory_uri() . '/assets/img/7.jpg',
							'title'     => esc_html__( 'Natalya Undergrowth', 'hestia-pro' ),
							'subtitle'  => esc_html__( 'Freelancer', 'hestia-pro' ),
							'text'      => esc_html__( '"Thank you for making it painless, pleasant and most of all hassle free! I am so pleased with this product. Dude, your stuff is great! I will refer everyone I know."', 'hestia-pro' ),
							'id'        => 'customizer_repeater_56d7ea7f40d76',
						),
					) ) );
					if ( ! empty( $hestia_testimonials_content ) ) :
						$hestia_testimonials_content = json_decode( $hestia_testimonials_content );
						foreach ( $hestia_testimonials_content as $testimonial_item ) :
							?>
							<div class="col-md-4">
								<div class="card card-testimonial card-plain">
									<?php if ( ! empty( $testimonial_item->image_url ) ) : ?>
										<div class="card-avatar">
											<?php if ( ! empty( $testimonial_item->link ) ) : ?>
											<a href="<?php echo esc_url( $testimonial_item->link ); ?>">
												<?php endif; ?>
												<img class="img"
													 src="<?php echo esc_url( $testimonial_item->image_url ); ?>" <?php if ( ! empty( $testimonial_item->title ) ) : ?> alt="<?php echo esc_attr( $testimonial_item->title ); ?>" title="<?php echo esc_attr( $testimonial_item->title ); ?>" <?php endif; ?> />
												<?php if ( ! empty( $testimonial_item->link ) ) : ?>
											</a>
										<?php endif; ?>
										</div>
									<?php endif; ?>
									<div class="content">
										<?php if ( ! empty( $testimonial_item->title ) ) : ?>
											<h4 class="card-title"><?php echo esc_html( $testimonial_item->title ); ?></h4>
										<?php endif; ?>
										<?php if ( ! empty( $testimonial_item->subtitle ) ) : ?>
											<h6 class="category text-muted"><?php echo esc_html( $testimonial_item->subtitle ); ?></h6>
										<?php endif; ?>
										<?php if ( ! empty( $testimonial_item->text ) ) : ?>
											<p class="card-description"><?php echo esc_html( $testimonial_item->text ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
			</div>
		</section>
		<?php
	}
endif;

if ( function_exists( 'hestia_testimonials' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 40, 'hestia_testimonials' );
	add_action( 'hestia_sections', 'hestia_testimonials', absint( $section_priority ) );
}
