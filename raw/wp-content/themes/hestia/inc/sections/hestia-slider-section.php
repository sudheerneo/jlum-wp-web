<?php
/**
 * Slider section for the homepage.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

if ( ! function_exists( 'hestia_slider' ) ) :
	/**
	 * Slider section content.
	 *
	 * @since Hestia 1.0
	 */
	function hestia_slider() {
		?>
		<div id="carousel-hestia-generic" class="carousel slide" data-ride="carousel">
		<div class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
				<?php
				$hestia_slider_content = get_theme_mod( 'hestia_slider_content', json_encode( array(
					array(
						'image_url' => get_template_directory_uri() . '/assets/img/slider1.jpg',
						'title'     => esc_html__( 'Lorem Ipsum', 'hestia-pro' ),
						'subtitle'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
						'text'      => esc_html__( 'Button', 'hestia-pro' ),
						'link'      => '#',
						'id'        => 'customizer_repeater_56d7ea7f40a56',
					),
					array(
						'image_url' => get_template_directory_uri() . '/assets/img/slider2.jpg',
						'title'     => esc_html__( 'Lorem Ipsum', 'hestia-pro' ),
						'subtitle'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
						'text'      => esc_html__( 'Button', 'hestia-pro' ),
						'link'      => '#',
						'id'        => 'customizer_repeater_56d7ea7f40a57',
					),
					array(
						'image_url' => get_template_directory_uri() . '/assets/img/slider3.jpg',
						'title'     => esc_html__( 'Lorem Ipsum', 'hestia-pro' ),
						'subtitle'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia-pro' ),
						'text'      => esc_html__( 'Button', 'hestia-pro' ),
						'link'      => '#',
						'id'        => 'customizer_repeater_56d7ea7f40a58',
					),
				) ) );
				$i                     = 0;
				if ( ! empty( $hestia_slider_content ) ) :
					$hestia_slider_content = json_decode( $hestia_slider_content );
					foreach ( $hestia_slider_content as $slider_item ) : ?>
						<div class="item <?php $i ++;
						if ( $i == 1 ) {
							echo 'active';
						} ?>">
							<?php if ( ! empty( $slider_item->image_url ) ) : ?>
							<div class="page-header header-filter"
								 style="background-image: url('<?php echo esc_url( $slider_item->image_url ); ?>');">
								<?php else : ?>
								<div class="page-header header-filter">
									<?php endif; ?>
									<div class="container">
										<div class="row">
											<div class="col-md-8 col-md-offset-2 text-center">
												<?php if ( ! empty( $slider_item->title ) ) : ?>
													<h2 class="title"><?php echo esc_html( $slider_item->title ); ?></h2>
												<?php endif; ?>
												<?php if ( ! empty( $slider_item->subtitle ) ) : ?>
													<h4><?php echo esc_html( $slider_item->subtitle ); ?></h4>
												<?php endif; ?>
												<?php if ( ! empty( $slider_item->link ) || ! empty( $slider_item->text ) ) : ?>
													<div class="buttons">
														<a href="<?php echo esc_url( $slider_item->link ); ?>"
														   class="btn btn-primary btn-lg"><?php echo esc_html( $slider_item->text ); ?></a>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
					endforeach;
				endif; ?>
				</div>
				<?php if ( $i >= 2 ) : ?>
					<a class="left carousel-control" href="#carousel-hestia-generic" data-slide="prev"> <i
							class="fa fa-angle-left"></i> </a>
					<a class="right carousel-control" href="#carousel-hestia-generic" data-slide="next"> <i
							class="fa fa-angle-right"></i> </a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	add_action( 'hestia_header', 'hestia_slider' );
endif;
