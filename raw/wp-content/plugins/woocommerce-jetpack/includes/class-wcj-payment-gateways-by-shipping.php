<?php
/**
 * WooCommerce Jetpack Payment Gateways by Shipping
 *
 * The WooCommerce Jetpack Payment Gateways by Shipping class.
 *
 * @version 2.7.0
 * @since   2.7.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCJ_Payment_Gateways_By_Shipping' ) ) :

class WCJ_Payment_Gateways_By_Shipping extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.7.0
	 * @since   2.7.0
	 */
	function __construct() {

		$this->id         = 'payment_gateways_by_shipping';
		$this->short_desc = __( 'Gateways by Shipping', 'woocommerce-jetpack' );
		$this->desc       = __( 'Set "enable for shipping methods" for WooCommerce payment gateways.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-payment-gateways-by-shipping/';
		parent::__construct();

		add_filter( 'init', array( $this, 'add_settings_hook' ) );

		if ( $this->is_enabled() ) {
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), PHP_INT_MAX, 1 );
		}
	}

	/**
	 * check_if_enabled_for_methods.
	 *
	 * @version 2.7.0
	 * @since   2.7.0
	 * @see     `is_available()` function in WooCommerce `WC_Gateway_COD` class
	 * @todo    virtual orders (`enable_for_virtual`)
	 */
	function check_if_enabled_for_methods( $gateway_key, $enable_for_methods ) {

		$order          = null;
		$needs_shipping = false;

		// Test if shipping is needed first
		if ( WC()->cart && WC()->cart->needs_shipping() ) {
			$needs_shipping = true;
		} elseif ( is_page( wc_get_page_id( 'checkout' ) ) && 0 < get_query_var( 'order-pay' ) ) {
			$order_id = absint( get_query_var( 'order-pay' ) );
			$order    = wc_get_order( $order_id );

			// Test if order needs shipping.
			if ( 0 < sizeof( $order->get_items() ) ) {
				foreach ( $order->get_items() as $item ) {
					$_product = $order->get_product_from_item( $item );
					if ( $_product && $_product->needs_shipping() ) {
						$needs_shipping = true;
						break;
					}
				}
			}
		}

		$needs_shipping = apply_filters( 'woocommerce_cart_needs_shipping', $needs_shipping );

		// Virtual order, with virtual disabled
		/*
		if ( ! $this->enable_for_virtual && ! $needs_shipping ) {
			return false;
		}
		*/

		// Check methods
		if ( ! empty( $enable_for_methods ) && $needs_shipping ) {

			// Only apply if all packages are being shipped via chosen methods, or order is virtual
			$chosen_shipping_methods_session = WC()->session->get( 'chosen_shipping_methods' );

			if ( isset( $chosen_shipping_methods_session ) ) {
				$chosen_shipping_methods = array_unique( $chosen_shipping_methods_session );
			} else {
				$chosen_shipping_methods = array();
			}

			$check_method = false;

			if ( is_object( $order ) ) {
				if ( $order->shipping_method ) {
					$check_method = $order->shipping_method;
				}

			} elseif ( empty( $chosen_shipping_methods ) || sizeof( $chosen_shipping_methods ) > 1 ) {
				$check_method = false;
			} elseif ( sizeof( $chosen_shipping_methods ) == 1 ) {
				$check_method = $chosen_shipping_methods[0];
			}

			if ( ! $check_method ) {
				return false;
			}

			$found = false;

			foreach ( $enable_for_methods as $method_id ) {
				if ( strpos( $check_method, $method_id ) === 0 ) {
					$found = true;
					break;
				}
			}

			if ( ! $found ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * available_payment_gateways.
	 *
	 * @version 2.7.0
	 * @since   2.7.0
	 */
	function available_payment_gateways( $_available_gateways ) {
		foreach ( $_available_gateways as $key => $gateway ) {
			$enable_for_methods = get_option( 'wcj_gateways_by_shipping_enable_' . $key, '' );
			if ( ! empty( $enable_for_methods ) && ! $this->check_if_enabled_for_methods( $key, $enable_for_methods ) ) {
				unset( $_available_gateways[ $key ] );
				continue;
			}
		}
		return $_available_gateways;
	}

	/**
	 * add_settings.
	 *
	 * @version 2.7.0
	 * @version 2.7.0
	 * @todo    (maybe) remove COD, Custom Booster Payment Gateways (and maybe other payment gateways) that already have `enable_for_methods` option
	 */
	function add_settings( $settings ) {
		$shipping_methods = array();
		if ( is_admin() ) {
			foreach ( WC()->shipping()->load_shipping_methods() as $method ) {
				$shipping_methods[ $method->id ] = $method->get_method_title();
			}
		}
		$settings = array(
			array(
				'title' => __( 'Payment Gateways', 'woocommerce-jetpack' ),
				'type'  => 'title',
				'desc'  => __( 'If payment gateway is only available for certain methods, set it up here. Leave blank to enable for all methods.', 'woocommerce-jetpack' ),
				'id'    => 'wcj_payment_gateways_by_shipping_options',
			),
		);
		$gateways = WC()->payment_gateways->payment_gateways();
		foreach ( $gateways as $key => $gateway ) {
			$default_gateways = array( 'bacs', 'cod' );
			if ( ! empty( $default_gateways ) && ! in_array( $key, $default_gateways ) ) {
				$custom_attributes = apply_filters( 'booster_get_message', '', 'disabled' );
				if ( '' == $custom_attributes ) {
					$custom_attributes = array();
				}
				$desc_tip = apply_filters( 'booster_get_message', '', 'desc_no_link' );
			} else {
				$custom_attributes = array();
				$desc_tip = '';
			}
			$settings = array_merge( $settings, array(
				array(
					'title'             => $gateway->title,
					'desc_tip'          => $desc_tip,
					'desc'              => __( 'Enable for shipping methods', 'woocommerce' ),
					'id'                => 'wcj_gateways_by_shipping_enable_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'css'               => 'width: 450px;',
					'options'           => $shipping_methods,
					'custom_attributes' => array_merge( array( 'data-placeholder' => __( 'Select shipping methods', 'woocommerce' ) ), $custom_attributes ),
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'  => 'sectionend',
				'id'    => 'wcj_payment_gateways_by_shipping_options',
			),
		) );
		return $settings;
	}

}

endif;

return new WCJ_Payment_Gateways_By_Shipping();
