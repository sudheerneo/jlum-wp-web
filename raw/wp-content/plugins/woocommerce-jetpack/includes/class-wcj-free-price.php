<?php
/**
 * WooCommerce Jetpack Free Price
 *
 * The WooCommerce Jetpack Free Price class.
 *
 * @version 2.7.0
 * @since   2.5.9
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCJ_Free_Price' ) ) :

class WCJ_Free_Price extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.7.0
	 * @since   2.5.9
	 * @todo    single in grouped is treated as "related"
	 */
	function __construct() {

		$this->id         = 'free_price';
		$this->short_desc = __( 'Free Price Labels', 'woocommerce-jetpack' );
		$this->desc       = __( 'WooCommerce free price labels.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-free-price-labels/';
		parent::__construct();

		add_action( 'init', array( $this, 'add_settings_hook' ) );

		if ( $this->is_enabled() ) {
			if ( WCJ_IS_WC_VERSION_BELOW_3 ) {
				add_filter( 'woocommerce_free_price_html',           array( $this, 'modify_free_price_simple_external_custom' ), PHP_INT_MAX, 2 );
				add_filter( 'woocommerce_grouped_free_price_html',   array( $this, 'modify_free_price_grouped' ),                PHP_INT_MAX, 2 );
				add_filter( 'woocommerce_variable_free_price_html',  array( $this, 'modify_free_price_variable' ),               PHP_INT_MAX, 2 );
				add_filter( 'woocommerce_variation_free_price_html', array( $this, 'modify_free_price_variation' ),              PHP_INT_MAX, 2 );
			} else {
				add_filter( 'woocommerce_get_price_html',            array( $this, 'maybe_modify_price' ),                       PHP_INT_MAX, 2 );
			}
		}
	}

	/**
	 * are_all_prices_free
	 *
	 * @version 2.7.0
	 * @since   2.7.0
	 */
	function are_all_prices_free( $_product, $type ) {
		if ( 'variable' === $type ) {
			$prices    = $_product->get_variation_prices( true );
			$min_price = current( $prices['price'] );
			$max_price = end( $prices['price'] );
			if ( '' !== $min_price && '' !== $max_price ) {
				return ( 0 == $min_price && 0 == $max_price );
			}
		} elseif ( 'variable' === $type ) {
			$child_prices     = array();
			foreach ( $_product->get_children() as $child_id ) {
				$child = wc_get_product( $child_id );
				if ( '' !== $child->get_price() ) {
					$child_prices[] = wcj_get_product_display_price( $child );
				}
			}
			if ( ! empty( $child_prices ) ) {
				$min_price = min( $child_prices );
				$max_price = max( $child_prices );
			} else {
				$min_price = '';
				$max_price = '';
			}
			if ( '' !== $min_price && '' !== $max_price ) {
				return ( 0 == $min_price && 0 == $max_price );
			}
		}
		return false;
	}

	/**
	 * maybe_modify_price
	 *
	 * @version 2.7.0
	 * @since   2.7.0
	 */
	function maybe_modify_price( $price, $_product ) {
		if ( '' !== $price ) {
			if ( 0 == $_product->get_price() ) {
				if ( $_product->is_type( 'grouped' ) ) {
					return ( $this->are_all_prices_free( $_product, 'grouped' ) )  ? $this->modify_free_price_grouped( $price, $_product )  : $price;
				} elseif ( $_product->is_type( 'variable' ) ) {
					return ( $this->are_all_prices_free( $_product, 'variable' ) ) ? $this->modify_free_price_variable( $price, $_product ) : $price;
				} elseif ( $_product->is_type( 'variation' ) ) {
					return $this->modify_free_price_variation( $price, $_product );
				} else {
					return $this->modify_free_price_simple_external_custom( $price, $_product );
				}
			}
		}
		return $price;
	}

	/**
	 * get_view_id
	 *
	 * @version 2.5.9
	 * @since   2.5.9
	 */
	function get_view_id( $product_id ) {
		$view = 'single'; // default
		if ( is_single( $product_id ) ) {
			$view = 'single';
		} elseif ( is_single() ) {
			$view = 'related';
		} elseif ( is_front_page() ) {
			$view = 'home';
		} elseif ( is_page() ) {
			$view = 'page';
		} elseif ( is_archive() ) {
			$view = 'archive';
		}
		return $view;
	}

	/**
	 * modify_free_price_simple_external_custom.
	 *
	 * @version 2.7.0
	 * @since   2.5.9
	 */
	function modify_free_price_simple_external_custom( $price, $_product ) {
		$default = '<span class="amount">' . __( 'Free!', 'woocommerce' ) . '</span>';
		return ( $_product->is_type( 'external' ) ) ?
			do_shortcode( get_option( 'wcj_free_price_external_' . $this->get_view_id( wcj_get_product_id_or_variation_parent_id( $_product ) ), $default ) ) :
			do_shortcode( get_option( 'wcj_free_price_simple_'   . $this->get_view_id( wcj_get_product_id_or_variation_parent_id( $_product ) ), $default ) );
	}

	/**
	 * modify_free_price_grouped.
	 *
	 * @version 2.7.0
	 * @since   2.5.9
	 */
	function modify_free_price_grouped( $price, $_product ) {
		return do_shortcode( get_option( 'wcj_free_price_grouped_' . $this->get_view_id( wcj_get_product_id_or_variation_parent_id( $_product ) ), __( 'Free!', 'woocommerce' ) ) );
	}

	/**
	 * modify_free_price_variable.
	 *
	 * @version 2.7.0
	 * @since   2.5.9
	 */
	function modify_free_price_variable( $price, $_product ) {
		return do_shortcode( apply_filters( 'booster_get_option', __( 'Free!', 'woocommerce' ), get_option( 'wcj_free_price_variable_' . $this->get_view_id( wcj_get_product_id_or_variation_parent_id( $_product ) ), __( 'Free!', 'woocommerce' ) ) ) );
	}

	/**
	 * modify_free_price_variation.
	 *
	 * @version 2.5.9
	 * @since   2.5.9
	 */
	function modify_free_price_variation( $price, $_product ) {
		return do_shortcode( apply_filters( 'booster_get_option', __( 'Free!', 'woocommerce' ), get_option( 'wcj_free_price_variable_variation', __( 'Free!', 'woocommerce' ) ) ) );
	}

	/**
	 * add_settings.
	 *
	 * @version 2.7.0
	 * @since   2.5.9
	 */
	function add_settings( $settings ) {
		$product_types = array(
			'simple'   => __( 'Simple and Custom Products', 'woocommerce-jetpack' ),
			'variable' => __( 'Variable Products', 'woocommerce-jetpack' ),
			'grouped'  => __( 'Grouped Products', 'woocommerce-jetpack' ),
			'external' => __( 'External Products', 'woocommerce-jetpack' ),
		);
		$views = array(
			'single'   => __( 'Single Product Page', 'woocommerce-jetpack' ),
			'related'  => __( 'Related Products', 'woocommerce-jetpack' ),
			'home'     => __( 'Homepage', 'woocommerce-jetpack' ),
			'page'     => __( 'Pages (e.g. Shortcodes)', 'woocommerce-jetpack' ),
			'archive'  => __( 'Archives (Product Categories)', 'woocommerce-jetpack' ),
		);
		$settings = array();
		foreach ( $product_types as $product_type => $product_type_desc ) {
			$default_value = ( 'simple' === $product_type || 'external' === $product_type ) ? '<span class="amount">' . __( 'Free!', 'woocommerce' ) . '</span>' : __( 'Free!', 'woocommerce' );
			$settings = array_merge( $settings, array(
				array(
					'title'    => $product_type_desc,
					'desc'     => __( 'Labels can contain shortcodes.', 'woocommerce-jetpack' ),
					'type'     => 'title',
					'id'       => 'wcj_free_price_' . $product_type . 'options',
				),
			) );
			$current_views = $views;
			if ( 'variable' === $product_type ) {
				$current_views['variation'] = __( 'Variations', 'woocommerce-jetpack' );
			}
			foreach ( $current_views as $view => $view_desc ) {
				$settings = array_merge( $settings, array(
					array(
						'title'    => $view_desc,
						'id'       => 'wcj_free_price_' . $product_type . '_' . $view,
						'default'  => $default_value,
						'type'     => 'textarea',
						'css'      => 'width:30%;min-width:300px;min-height:50px;',
						'desc'     => ( 'variable' === $product_type ) ? apply_filters( 'booster_get_message', '', 'desc' ) : '',
						'custom_attributes' => ( 'variable' === $product_type ) ? apply_filters( 'booster_get_message', '', 'readonly' ) : '',
					),
				) );
			}
			$settings = array_merge( $settings, array(
				array(
					'type'     => 'sectionend',
					'id'       => 'wcj_free_price_' . $product_type . 'options',
				),
			) );
		}
		return $settings;
	}
}

endif;

return new WCJ_Free_Price();
