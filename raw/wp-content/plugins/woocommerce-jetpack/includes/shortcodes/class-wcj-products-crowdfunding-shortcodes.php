<?php
/**
 * WooCommerce Jetpack Products Crowdfunding Shortcodes
 *
 * The WooCommerce Jetpack Products Crowdfunding Shortcodes class.
 *
 * @version 2.7.0
 * @since   2.5.4
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_Products_Crowdfunding_Shortcodes' ) ) :

class WCJ_Products_Crowdfunding_Shortcodes extends WCJ_Shortcodes {

	/**
	 * Constructor.
	 *
	 * @version 2.5.4
	 * @since   2.5.4
	 */
	public function __construct() {

		$this->the_shortcodes = array(
			'wcj_product_total_orders',
			'wcj_product_total_orders_items',
			'wcj_product_total_orders_sum',
			'wcj_product_crowdfunding_goal',
			'wcj_product_crowdfunding_goal_remaining',
			'wcj_product_crowdfunding_goal_remaining_progress_bar',
			'wcj_product_crowdfunding_startdate',
			'wcj_product_crowdfunding_deadline',
			'wcj_product_crowdfunding_time_remaining',
			'wcj_product_crowdfunding_time_remaining_progress_bar',
		);

		$this->the_atts = array(
			'product_id'       => 0,
			'hide_currency'    => 'no',
			'offset'           => '',
		);

		parent::__construct();
	}

	/**
	 * Inits shortcode atts and properties.
	 *
	 * @version 2.5.4
	 * @since   2.5.4
	 * @param   array $atts Shortcode atts.
	 * @return  array The (modified) shortcode atts.
	 */
	function init_atts( $atts ) {

		// Atts
		if ( 0 == $atts['product_id'] ) {
			global $wcj_product_id_for_shortcode;
			if ( 0 != $wcj_product_id_for_shortcode ) {
				$atts['product_id'] = $wcj_product_id_for_shortcode;
			} else {
				$atts['product_id'] = get_the_ID();
			}
			if ( 0 == $atts['product_id'] ) return false;
		}
		$the_post_type = get_post_type( $atts['product_id'] );
		if ( 'product' !== $the_post_type && 'product_variation' !== $the_post_type ) return false;

		// Class properties
		$this->the_product = wc_get_product( $atts['product_id'] );
		if ( ! $this->the_product ) return false;

		return $atts;
	}

	/**
	 * get_product_orders_data.
	 *
	 * @version 2.7.0
	 * @since   2.2.6
	 */
	function get_product_orders_data( $return_value = 'total_orders', $atts ) {
		$product_ids = array();
		if ( $this->the_product->is_type( 'grouped' ) ) {
			$product_ids = $this->the_product->get_children();
		} else {
			$product_ids = array( wcj_get_product_id_or_variation_parent_id( $this->the_product ) );
		}
		global $woocommerce_loop, $post;
		$saved_wc_loop = $woocommerce_loop;
		$saved_post    = $post;
		$total_orders  = 0;
		$total_qty     = 0;
		$total_sum     = 0;
		$offset        = 0;
		$block_size    = 256;
		$date_query_after = get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_startdate', true );
		while( true ) {
			$args = array(
				'post_type'      => 'shop_order',
				'post_status'    => 'wc-completed',
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'date',
				'order'          => 'ASC',
				'date_query'     => array(
					array(
						'after'     => $date_query_after,
						'inclusive' => true,
					),
				),
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $order_id ) {
				$the_order = wc_get_order( $order_id );
				$the_items = $the_order->get_items();
				$item_found = false;
				foreach( $the_items as $item ) {
					if ( in_array( $item['product_id'], $product_ids ) ) {
						$total_sum += $item['line_total'] + $item['line_tax'];
						$total_qty += $item['qty'];
						$item_found = true;
					}
				}
				if ( $item_found ) {
					$total_orders++;
				}
			}
			$offset += $block_size;
		}
//		wp_reset_postdata();
		$woocommerce_loop = $saved_wc_loop;
		$post             = $saved_post;
		setup_postdata( $post );
		switch ( $return_value ) {
			case 'orders_sum':
				$return = $total_sum;
				break;
			case 'total_items':
				$return = $total_qty;
				break;
			default: // 'total_orders'
				$return = $total_orders;
				break;
		}
		if ( 0 != $atts['offset'] ) {
			$return += $atts['offset'];
		}
		return $return;
	}

	/**
	 * wcj_product_total_orders_items.
	 *
	 * @version 2.5.0
	 * @since   2.5.0
	 */
	function wcj_product_total_orders_items( $atts ) {
		return $this->get_product_orders_data( 'total_items', $atts );
	}

	/**
	 * wcj_product_total_orders.
	 *
	 * @version 2.5.0
	 * @since   2.2.6
	 */
	function wcj_product_total_orders( $atts ) {
		return $this->get_product_orders_data( 'total_orders', $atts );
	}

	/**
	 * wcj_product_total_orders_sum.
	 *
	 * @version 2.5.4
	 * @since   2.2.6
	 */
	function wcj_product_total_orders_sum( $atts ) {
		$total_orders_sum = $this->get_product_orders_data( 'orders_sum', $atts );
		return ( 'yes' === $atts['hide_currency'] ) ? $total_orders_sum : wc_price( $total_orders_sum );
	}

	/**
	 * wcj_product_crowdfunding_startdate.
	 *
	 * @version 2.7.0
	 * @since   2.2.6
	 */
	function wcj_product_crowdfunding_startdate( $atts ) {
		return date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_startdate', true ) ) );
	}

	/**
	 * wcj_product_crowdfunding_deadline.
	 *
	 * @version 2.7.0
	 * @since   2.2.6
	 */
	function wcj_product_crowdfunding_deadline( $atts ) {
		return date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_deadline', true ) ) );
	}

	/**
	 * wcj_product_crowdfunding_time_remaining.
	 *
	 * @version 2.3.8
	 * @since   2.2.6
	 */
	function wcj_product_crowdfunding_time_remaining( $atts ) {
		$seconds_remaining = strtotime( $this->wcj_product_crowdfunding_deadline( $atts ) ) - current_time( 'timestamp' );
		$days_remaining    = floor( $seconds_remaining / ( 24 * 60 * 60 ) );
		$hours_remaining   = floor( $seconds_remaining / (      60 * 60 ) );
		$minutes_remaining = floor( $seconds_remaining /             60   );
		if ( $seconds_remaining <= 0 ) return '';
		if ( $days_remaining    >  0 ) return ( 1 == $days_remaining    ) ? $days_remaining    . ' day left'    : $days_remaining    . ' days left';
		if ( $hours_remaining   >  0 ) return ( 1 == $hours_remaining   ) ? $hours_remaining   . ' hour left'   : $hours_remaining   . ' hours left';
		if ( $minutes_remaining >  0 ) return ( 1 == $minutes_remaining ) ? $minutes_remaining . ' minute left' : $minutes_remaining . ' minutes left';
		return                                ( 1 == $seconds_remaining ) ? $seconds_remaining . ' second left' : $seconds_remaining . ' seconds left';
		/* if ( ( $seconds_remaining = strtotime( $this->wcj_product_crowdfunding_deadline( $atts ) ) - time() ) <= 0 ) return '';
		if ( ( $days_remaining = floor( $seconds_remaining / ( 24 * 60 * 60 ) ) ) > 0 ) {
			return ( 1 === $days_remaining ) ? $days_remaining . ' day left' : $days_remaining . ' days left';
		}
		if ( ( $hours_remaining = floor( $seconds_remaining / ( 60 * 60 ) ) ) > 0 ) {
			return ( 1 === $hours_remaining ) ? $hours_remaining . ' hour left' : $hours_remaining . ' hours left';
		}
		if ( ( $minutes_remaining = floor( $seconds_remaining / 60 ) ) > 0 ) {
			return ( 1 === $minutes_remaining ) ? $minutes_remaining . ' minute left' : $minutes_remaining . ' minutes left';
		}
		return ( 1 === $seconds_remaining ) ? $seconds_remaining . ' second left' : $seconds_remaining . ' seconds left'; */
	}

	/**
	 * wcj_product_crowdfunding_time_remaining_progress_bar.
	 *
	 * @version 2.7.0
	 * @since   2.5.0
	 */
	function wcj_product_crowdfunding_time_remaining_progress_bar( $atts ) {
		$deadline_seconds  = strtotime( get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_deadline', true ) );
		$startdate_seconds = strtotime( get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_startdate', true ) );

		$seconds_remaining = $deadline_seconds - current_time( 'timestamp' );
		$seconds_total     = $deadline_seconds - $startdate_seconds;

		$current_value = $seconds_remaining;
		$max_value     = $seconds_total;
		return '<progress value="' . $current_value . '" max="' . $max_value . '"></progress>';
	}

	/**
	 * wcj_product_crowdfunding_goal.
	 *
	 * @version 2.7.0
	 * @since   2.2.6
	 */
	function wcj_product_crowdfunding_goal( $atts ) {
		$goal = get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_goal_sum', true );
		return ( 'yes' === $atts['hide_currency'] ) ? $goal : wc_price( $goal );
	}

	/**
	 * wcj_product_crowdfunding_goal_remaining.
	 *
	 * @version 2.7.0
	 * @since   2.2.6
	 */
	function wcj_product_crowdfunding_goal_remaining( $atts ) {
		$goal             = get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_goal_sum', true );
		$total_orders_sum = $this->get_product_orders_data( 'orders_sum', $atts );
		$goal_remaining   = $goal - $total_orders_sum;
		return ( 'yes' === $atts['hide_currency'] ) ? $goal_remaining : wc_price( $goal_remaining );
	}

	/**
	 * wcj_product_crowdfunding_goal_remaining_progress_bar.
	 *
	 * @version 2.7.0
	 * @since   2.5.0
	 */
	function wcj_product_crowdfunding_goal_remaining_progress_bar( $atts ) {
		$current_value = $this->get_product_orders_data( 'orders_sum', $atts );
		$max_value     = get_post_meta( wcj_get_product_id_or_variation_parent_id( $this->the_product ), '_' . 'wcj_crowdfunding_goal_sum', true );
		return '<progress value="' . $current_value . '" max="' . $max_value . '"></progress>';
	}

}

endif;

return new WCJ_Products_Crowdfunding_Shortcodes();
