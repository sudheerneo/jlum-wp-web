<?php
/**
 * The front page template file.
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Hestia
 * @since Hestia 1.0
 */

get_header(); ?>
	<?php
	/**
	 * Hestia Header hook.
	 *
	 * @hooked hestia_slider_section
	 */
	do_action( 'hestia_header' );
	?>
	</header>
	<div class="<?php echo esc_html( hestia_layout() ); ?>">
		<?php
		/**
		 * Hestia Sections hook.
		 *
		 * @hooked hestia_features_section - 1
		 * @hooked hestia_about_section - 2
		 * @hooked hestia_shop_section - 3
		 * @hooked hestia_portfolio_section - 4
		 * @hooked hestia_team_section - 5
		 * @hooked hestia_pricing_section - 6
		 * @hooked hestia_testimonials_section - 7
		 * @hooked hestia_subscribe_section - 8
		 * @hooked hestia_blog_section - 9
		 * @hooked hestia_contact_section - 10
		 */
		do_action( 'hestia_sections' );
	?>
<?php get_footer(); ?>
