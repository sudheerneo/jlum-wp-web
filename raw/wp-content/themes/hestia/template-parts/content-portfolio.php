<?php
/**
 * The default template for displaying content
 *
 * Used for single portfolio posts.
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */
?>

					<article id="post-<?php the_ID(); ?>" class="section section-text">
						<div class="row">
							<div class="col-md-8 col-md-offset-2">
								<?php the_post_thumbnail(); ?>
								<?php the_content(); ?>
							</div>
						</div>
					</article>
					<div class="section section-blog-info">
						<div class="row">
							<div class="col-md-8 col-md-offset-2">
								<div class="row">
									<div class="col-md-12">
										<?php
											hestia_wp_link_pages( array(
												'before' => '<div class="text-center"> <ul class="nav pagination pagination-primary">',
												'after' => '</ul> </div>',
												'link_before' => '<li>',
												'link_after' => '</li>',
											) );
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
