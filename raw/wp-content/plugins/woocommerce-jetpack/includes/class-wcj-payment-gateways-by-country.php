<?php
/**
 * WooCommerce Jetpack Payment Gateways by Country
 *
 * The WooCommerce Jetpack Payment Gateways by Country class.
 *
 * @version 2.7.0
 * @since   2.4.1
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCJ_Payment_Gateways_By_Country' ) ) :

class WCJ_Payment_Gateways_By_Country extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.7.0
	 */
	function __construct() {

		$this->id         = 'payment_gateways_by_country';
		$this->short_desc = __( 'Gateways by Country or State', 'woocommerce-jetpack' );
		$this->desc       = __( 'Set countries or states to include/exclude for WooCommerce payment gateways to show up.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-payment-gateways-by-country-or-state/';
		parent::__construct();

		add_filter( 'init', array( $this, 'add_settings_hook' ) );

		if ( $this->is_enabled() ) {
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), PHP_INT_MAX, 1 );
		}
	}

	/**
	 * available_payment_gateways.
	 *
	 * @version 2.7.0
	 */
	function available_payment_gateways( $_available_gateways ) {
		if ( isset( WC()->customer ) ) {
			$customer_country = ( WCJ_IS_WC_VERSION_BELOW_3 ) ? WC()->customer->get_country() : WC()->customer->get_billing_country();
			foreach ( $_available_gateways as $key => $gateway ) {
				$include_countries = get_option( 'wcj_gateways_countries_include_' . $key, '' );
				if ( ! empty( $include_countries ) && ! in_array( $customer_country, $include_countries ) ) {
					unset( $_available_gateways[ $key ] );
					continue;
				}
				$exclude_countries = get_option( 'wcj_gateways_countries_exclude_' . $key, '' );
				if ( ! empty( $exclude_countries ) && in_array( $customer_country, $exclude_countries ) ) {
					unset( $_available_gateways[ $key ] );
					continue;
				}
				$customer_state = ( WCJ_IS_WC_VERSION_BELOW_3 ) ? WC()->customer->get_state() : WC()->customer->get_billing_state();
				$include_states = get_option( 'wcj_gateways_states_include_' . $key, '' );
				if ( ! empty( $include_states ) && ! in_array( $customer_state, $include_states ) ) {
					unset( $_available_gateways[ $key ] );
					continue;
				}
				$exclude_states = get_option( 'wcj_gateways_states_exclude_' . $key, '' );
				if ( ! empty( $exclude_states ) && in_array( $customer_state, $exclude_states ) ) {
					unset( $_available_gateways[ $key ] );
					continue;
				}
			}
		}
		return $_available_gateways;
	}

	/**
	 * add_settings.
	 *
	 * @version 2.7.0
	 */
	function add_settings() {
		$settings = array(
			array(
				'title' => __( 'Payment Gateways', 'woocommerce-jetpack' ),
				'type'  => 'title',
				'desc'  => __( 'Leave empty to disable.', 'woocommerce-jetpack' ),
				'id'    => 'wcj_payment_gateways_by_country_gateways_options',
			),
		);
		$countries = wcj_get_countries();
		$states    = wcj_get_states();
		$gateways  = WC()->payment_gateways->payment_gateways();
		foreach ( $gateways as $key => $gateway ) {
			$default_gateways = array( 'bacs' );
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
					'title'     => $gateway->title,
					'desc_tip'  => $desc_tip,
					'desc'      => __( 'Include Countries', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_countries_include_' . $key,
					'default'   => '',
					'type'      => 'multiselect',
					'class'     => 'chosen_select',
					'css'       => 'width: 450px;',
					'options'   => $countries,
					'custom_attributes' => $custom_attributes,
				),
				array(
					'title'     => '',
					'desc_tip'  => $desc_tip,
					'desc'      => __( 'Exclude Countries', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_countries_exclude_' . $key,
					'default'   => '',
					'type'      => 'multiselect',
					'class'     => 'chosen_select',
					'css'       => 'width: 450px;',
					'options'   => $countries,
					'custom_attributes' => $custom_attributes,
				),
				array(
					'title'     => '',
					'desc_tip'  => $desc_tip,
					'desc'      => __( 'Include States (Base Country)', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_states_include_' . $key,
					'default'   => '',
					'type'      => 'multiselect',
					'class'     => 'chosen_select',
					'css'       => 'width: 450px;',
					'options'   => $states,
					'custom_attributes' => $custom_attributes,
				),
				array(
					'title'     => '',
					'desc_tip'  => $desc_tip,
					'desc'      => __( 'Exclude States (Base Country)', 'woocommerce-jetpack' ),
					'id'        => 'wcj_gateways_states_exclude_' . $key,
					'default'   => '',
					'type'      => 'multiselect',
					'class'     => 'chosen_select',
					'css'       => 'width: 450px;',
					'options'   => $states,
					'custom_attributes' => $custom_attributes,
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'  => 'sectionend',
				'id'    => 'wcj_payment_gateways_by_country_gateways_options',
			),
		) );
		return $settings;
	}

}

endif;

return new WCJ_Payment_Gateways_By_Country();
