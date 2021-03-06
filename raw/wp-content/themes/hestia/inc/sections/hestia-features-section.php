<?php
/**
 * Services section for the homepage.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_features' ) ) :
	/**
	 * Features section content.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_features() {

		$show_features_single_product = get_theme_mod( 'hestia_features_show_on_single_product', false );
		$hide_section                 = get_theme_mod( 'hestia_features_hide', false );

		if ( ( is_single() && ( (bool) $show_features_single_product === false ) ) || ( is_front_page() && ( (bool) $hide_section === true ) ) ) {
			return;
		}

		$hestia_features_title = get_theme_mod( 'hestia_features_title', esc_html__( 'Why our product is the best', 'hestia-pro' ) );
		$hestia_features_subtitle = get_theme_mod( 'hestia_features_subtitle', esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ) );
		?>
		<section class="features" id="features" data-sorder="hestia_features">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<?php if ( ! empty( $hestia_features_title ) || is_customize_preview() ) : ?>
							<h2 class="title"><?php echo esc_html( $hestia_features_title ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $hestia_features_subtitle ) || is_customize_preview() ) : ?>
							<h5 class="description"><?php echo esc_html( $hestia_features_subtitle ); ?></h5>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<?php
					$hestia_features_content = get_theme_mod( 'hestia_features_content', json_encode( array(
						array(
							'icon_value' => 'fa-wechat',
							'title' => esc_html__( 'Responsive', 'hestia-pro' ),
							'text' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
							'link' => '#',
							'id' => 'customizer_repeater_56d7ea7f40b56',
							'color' => '#e91e63',
						),
						array(
							'icon_value' => 'fa-check',
							'title' => esc_html__( 'Quality', 'hestia-pro' ),
							'text' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
							'link' => '#',
							'id' => 'customizer_repeater_56d7ea7f40b66',
							'color' => '#00bcd4',
						),
						array(
							'icon_value' => 'fa-support',
							'title' => esc_html__( 'Support', 'hestia-pro' ),
							'text' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
							'link' => '#',
							'id' => 'customizer_repeater_56d7ea7f40b86',
							'color' => '#4caf50',
						),
					)) );
					if ( ! empty( $hestia_features_content ) ) :
						$hestia_features_content = json_decode( $hestia_features_content );
						foreach ( $hestia_features_content as $features_item ) :
							?>
							<div class="col-md-4 <?php echo esc_attr( $features_item->id ); ?>">
								<div class="info">
									<?php if ( ! empty( $features_item->link ) ) : ?>
									<a href="<?php echo esc_html( $features_item->link ); ?>">
										<?php endif; ?>
										<?php if ( ! empty( $features_item->icon_value ) ) : ?>
											<div class="icon">
												<i class="fa <?php echo esc_html( $features_item->icon_value ); ?>"></i>
											</div>
										<?php endif; ?>
										<?php if ( ! empty( $features_item->title ) ) : ?>
											<h4 class="info-title"><?php echo esc_html( $features_item->title ); ?></h4>
										<?php endif; ?>
										<?php if ( ! empty( $features_item->link ) ) : ?>
									</a>
								<?php endif; ?>
									<?php if ( ! empty( $features_item->text ) ) : ?>
										<p><?php echo esc_html( $features_item->text ); ?></p>
									<?php endif; ?>
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

if ( function_exists( 'hestia_features' ) ) {
	$section_priority = apply_filters( 'hestia_section_priority', 10, 'hestia_features' );
	add_action( 'hestia_sections', 'hestia_features', absint( $section_priority ) );
}
