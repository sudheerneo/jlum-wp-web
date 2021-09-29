<?php
/**
 * WooCommerce Jetpack Payment Gateways Fees
 *
 * The WooCommerce Jetpack Payment Gateways Fees class.
 *
 * @version 2.7.0
 * @since   2.2.2
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_Payment_Gateways_Fees' ) ) :

class WCJ_Payment_Gateways_Fees extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.7.0
	 */
	function __construct() {

		$this->id         = 'payment_gateways_fees';
		$this->short_desc = __( 'Gateways Fees and Discounts', 'woocommerce-jetpack' );
		$this->desc       = __( 'Enable extra fees or discounts for WooCommerce payment gateways.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-payment-gateways-fees-and-discounts/';
		parent::__construct();

		add_filter( 'init',  array( $this, 'add_settings_hook' ) );

		if ( $this->is_enabled() ) {
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'gateways_fees' ) );
			add_action( 'wp_enqueue_scripts',              array( $this, 'enqueue_checkout_script' ) );
		}
	}

	/**
	 * enqueue_checkout_script.
	 *
	 * @version 2.7.0
	 */
	function enqueue_checkout_script() {
		if( ! is_checkout() ) {
			return;
		}
		wp_enqueue_script( 'wcj-payment-gateways-checkout', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/checkout.js', array( 'jquery' ), WCJ()->version, true );
	}

	/**
	 * gateways_fees.
	 *
	 * @version 2.7.0
	 */
	function gateways_fees() {
		global $woocommerce;
		$is_paypal_express = ( isset( $_GET['wc-api'] ) && 'WC_Gateway_PayPal_Express_AngellEYE' === $_GET['wc-api'] ) ? true : false;
		if ( ! $is_paypal_express ) {
			$current_gateway = $woocommerce->session->chosen_payment_method;
			$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
			if ( ! array_key_exists( $current_gateway, $available_gateways ) ) {
				$current_gateway = get_option( 'woocommerce_default_gateway', '' );
				if ( '' == $current_gateway ) {
					$current_gateway = current( $available_gateways );
					$current_gateway = isset( $current_gateway->id ) ? $current_gateway->id : '';
				}
			}
		} else {
			$current_gateway = 'paypal_express';
		}
		if ( '' != $current_gateway ) {
			$fee_text  = get_option( 'wcj_gateways_fees_text_' . $current_gateway );
			$min_cart_amount = get_option( 'wcj_gateways_fees_min_cart_amount_' . $current_gateway );
			$max_cart_amount = get_option( 'wcj_gateways_fees_max_cart_amount_' . $current_gateway );
			// Multicurrency (Currency Switcher) module
			if ( WCJ()->modules['multicurrency']->is_enabled() ) {
				$min_cart_amount = WCJ()->modules['multicurrency']->change_price( $min_cart_amount, null );
				$max_cart_amount = WCJ()->modules['multicurrency']->change_price( $max_cart_amount, null );
			}
			$total_in_cart = $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total;
			if ( '' != $fee_text && $total_in_cart >= $min_cart_amount  && ( 0 == $max_cart_amount || $total_in_cart <= $max_cart_amount ) ) {
				$fee_value = get_option( 'wcj_gateways_fees_value_' . $current_gateway );
				$fee_type  = get_option( 'wcj_gateways_fees_type_'  . $current_gateway );
				$final_fee_to_add = 0;
				switch ( $fee_type ) {
					case 'fixed':
						// Multicurrency (Currency Switcher) module
						if ( WCJ()->modules['multicurrency']->is_enabled() ) {
							$fee_value = WCJ()->modules['multicurrency']->change_price( $fee_value, null );
						}
						$final_fee_to_add = $fee_value;
						break;
					case 'percent':
						$final_fee_to_add = ( $fee_value / 100 ) * $total_in_cart;
						if ( 'yes' === get_option( 'wcj_gateways_fees_round_' . $current_gateway ) ) {
							$final_fee_to_add = round( $final_fee_to_add, get_option( 'wcj_gateways_fees_round_precision_' . $current_gateway ) );
						}
						break;
				}
				if ( 0 != $final_fee_to_add ) {
					$taxable = ( 'yes' === get_option( 'wcj_gateways_fees_is_taxable_' . $current_gateway ) ) ? true : false;
					$tax_class_name = '';
					if ( $taxable ) {
						$tax_class_id = get_option( 'wcj_gateways_fees_tax_class_id_' . $current_gateway, 0 );
						$tax_class_names = array_merge( array( '', ), WC_Tax::get_tax_classes() );
						$tax_class_name = $tax_class_names[ $tax_class_id ];
					}
					$woocommerce->cart->add_fee( $fee_text, $final_fee_to_add, $taxable, $tax_class_name );
				}
			}
		}
	}

	/**
	 * add_settings.
	 *
	 * @version 2.7.0
	 */
	function add_settings( $settings ) {
		$available_gateways = WC()->payment_gateways->payment_gateways();
		foreach ( $available_gateways as $key => $gateway ) {
			$settings = array_merge( $settings, array(
				array(
					'title'     => $gateway->title,
					'type'      => 'title',
//					'desc'      => ( $gateway->is_available() ? __( 'Available', 'woocommerce-jetpack' ) : __( 'Not available', 'woocommerce-jetpack' ) ),
					'id'        => 'wcj_gateways_fees_options_' . $key,
				),
				array(
					'title'     => __( 'Fee (or Discount) Title', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'Fee (or discount) title to show to customer.', 'woocommerce-jetpack' ),
					'desc'      => __( 'Leave blank to disable', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_text_' . $key,
					'default'   => '',
					'type'      => 'text',
				),
				array(
					'title'     => __( 'Fee (or Discount) Type', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'Percent or fixed value.', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_type_' . $key,
					'default'   => 'fixed',
					'type'      => 'select',
					'options'   => array(
						'fixed'   => __( 'Fixed', 'woocommerce-jetpack' ),
						'percent' => __( 'Percent', 'woocommerce-jetpack' ),
					),
				),
				array(
					'title'     => __( 'Fee (or Discount) Value', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'The value. For discount enter a negative number.', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_value_' . $key,
					'default'   => 0,
					'type'      => 'number',
					'custom_attributes' => array(
						'step' => '0.01',
					),
				),
				array(
					'title'     => __( 'Minimum Cart Amount', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'Minimum cart amount for adding the fee (or discount).', 'woocommerce-jetpack' ),
					'desc'      => __( 'Set 0 to disable', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_min_cart_amount_' . $key,
					'default'   => 0,
					'type'      => 'number',
					'custom_attributes' => array(
						'step' => '0.01',
						'min'  => '0',
					),
				),
				array(
					'title'     => __( 'Maximum Cart Amount', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'Maximum cart amount for adding the fee (or discount).', 'woocommerce-jetpack' ),
					'desc'      => __( 'Set 0 to disable', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_max_cart_amount_' . $key,
					'default'   => 0,
					'type'      => 'number',
					'custom_attributes' => array(
						'step' => '0.01',
						'min'  => '0',
					),
				),
				array(
					'title'     => __( 'Rounding', 'woocommerce-jetpack' ),
					'desc'      => __( 'Round the fee (or discount) value before adding to the cart', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_round_' . $key,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Rounding Precision', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'If Rounding is enabled, set precision here.', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_round_precision_' . $key,
					'default'   => 0,
					'type'      => 'number',
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '0',
					),
				),
				array(
					'title'     => __( 'Taxing', 'woocommerce-jetpack' ),
					'desc'      => __( 'Taxable', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_is_taxable_' . $key,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Tax Class', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'If Taxing is enabled, set tax class here.', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_fees_tax_class_id_' . $key,
					'default'   => '',
					'type'      => 'select',
					'options'   => array_merge( array( __( 'Standard Rate', 'woocommerce-jetpack' ) ), WC_Tax::get_tax_classes() ),
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'wcj_gateways_fees_options_' . $key,
				),
			) );
		}
		return $settings;
	}
}

endif;

return new WCJ_Payment_Gateways_Fees();
