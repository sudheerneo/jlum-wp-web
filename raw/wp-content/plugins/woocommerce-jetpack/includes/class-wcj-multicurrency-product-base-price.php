<?php
/**
 * WooCommerce Jetpack Multicurrency Product Base Price
 *
 * The WooCommerce Jetpack Multicurrency Product Base Price class.
 *
 * @version 2.7.0
 * @since   2.4.8
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_Multicurrency_Base_Price' ) ) :

class WCJ_Multicurrency_Base_Price extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.7.0
	 * @since   2.4.8
	 */
	function __construct() {

		$this->id         = 'multicurrency_base_price';
		$this->short_desc = __( 'Multicurrency Product Base Price', 'woocommerce-jetpack' );
		$this->desc       = __( 'Enter prices for WooCommerce products in different currencies.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-multicurrency-product-base-price/';
		parent::__construct();

		add_action( 'init', array( $this, 'add_settings_hook' ) );

		if ( $this->is_enabled() ) {

			add_action( 'add_meta_boxes',    array( $this, 'add_meta_box' ) );
			add_action( 'save_post_product', array( $this, 'save_meta_box' ), PHP_INT_MAX, 2 );

			add_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol_on_product_edit' ), PHP_INT_MAX, 2 );

			if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				wcj_add_change_price_hooks( $this, PHP_INT_MAX - 10, false );
			}

			/* if ( is_admin() ) {
				include_once( 'reports/class-wcj-currency-reports.php' );
			} */
		}
	}

	/**
	 * get_currency_exchange_rate.
	 *
	 * @version 2.5.6
	 */
	function get_currency_exchange_rate( $currency_code ) {
		/*
		$currency_exchange_rate = 1;
		$total_number = apply_filters( 'booster_get_option', 1, get_option( 'wcj_multicurrency_base_price_total_number', 1 ) );
		for ( $i = 1; $i <= $total_number; $i++ ) {
			if ( $currency_code === get_option( 'wcj_multicurrency_base_price_currency_' . $i ) ) {
				$currency_exchange_rate = get_option( 'wcj_multicurrency_base_price_exchange_rate_' . $i );
				break;
			}
		}
		return $currency_exchange_rate;
		*/
		return wcj_get_currency_exchange_rate_product_base_currency( $currency_code );
	}

	/**
	 * change_price_grouped.
	 *
	 * @version 2.7.0
	 * @since   2.5.0
	 */
	function change_price_grouped( $price, $qty, $_product ) {
		if ( $_product->is_type( 'grouped' ) ) {
			foreach ( $_product->get_children() as $child_id ) {
				$the_price = get_post_meta( $child_id, '_price', true );
				$the_product = wc_get_product( $child_id );
				$the_price = wcj_get_product_display_price( $the_product, $the_price, 1 );
				if ( $the_price == $price ) {
					return $this->change_price( $price, $the_product );
				}
			}
		}
		return $price;
	}

	/**
	 * change_price.
	 *
	 * @version 2.7.0
	 */
	function change_price( $price, $_product ) {
		return wcj_price_by_product_base_currency( $price, wcj_get_product_id_or_variation_parent_id( $_product ) );
	}

	/**
	 * get_variation_prices_hash.
	 *
	 * @version 2.7.0
	 */
	function get_variation_prices_hash( $price_hash, $_product, $display ) {
		$multicurrency_base_price_currency = get_post_meta( wcj_get_product_id_or_variation_parent_id( $_product, true ), '_' . 'wcj_multicurrency_base_price_currency', true );
		$currency_exchange_rate = $this->get_currency_exchange_rate( $multicurrency_base_price_currency );
		$price_hash['wcj_base_currency'] = array(
			$multicurrency_base_price_currency,
			$currency_exchange_rate,
		);
		return $price_hash;
	}

	/**
	 * get_meta_box_options.
	 */
	function get_meta_box_options() {

		/* $main_product_id = get_the_ID();
		$_product = wc_get_product( $main_product_id );
		$products = array();
		if ( $_product->is_type( 'variable' ) ) {
			$available_variations = $_product->get_available_variations();
			foreach ( $available_variations as $variation ) {
				$variation_product = wc_get_product( $variation['variation_id'] );
				$products[ $variation['variation_id'] ] = ' (' . wcj_get_product_formatted_variation( $variation_product, true ) . ')';
			}
		} else {
			$products[ $main_product_id ] = '';
		}
		$options = array();
		$total_number = apply_filters( 'booster_get_option', 1, get_option( 'wcj_multicurrency_base_price_total_number', 1 ) );
		foreach ( $products as $product_id => $desc ) {
			$currency_codes = array();
			$currency_codes[ get_woocommerce_currency() ] = get_woocommerce_currency();
			for ( $i = 1; $i <= $total_number; $i++ ) {
				$currency_codes[ get_option( 'wcj_multicurrency_base_price_currency_' . $i ) ] = get_option( 'wcj_multicurrency_base_price_currency_' . $i );
			}
			$options[] = array(
				'name'       => 'wcj_multicurrency_base_price_currency_' . $product_id,
				'default'    => '',
				'type'       => 'select',
				'title'      => __( 'Product Currency', 'woocommerce-jetpack' ),
				'desc'       => $desc,
				'product_id' => $product_id,
				'meta_name'  => '_' . 'wcj_multicurrency_base_price_currency',
				'options'    => $currency_codes,
			);
		}
		return $options; */

		$currency_codes = array();
		$currency_codes[ get_woocommerce_currency() ] = get_woocommerce_currency();
		$total_number = apply_filters( 'booster_get_option', 1, get_option( 'wcj_multicurrency_base_price_total_number', 1 ) );
		for ( $i = 1; $i <= $total_number; $i++ ) {
			$currency_codes[ get_option( 'wcj_multicurrency_base_price_currency_' . $i ) ] = get_option( 'wcj_multicurrency_base_price_currency_' . $i );
		}
		$options = array(
			array(
				'name'       => 'wcj_multicurrency_base_price_currency',
				'default'    => get_woocommerce_currency(),
				'type'       => 'select',
				'title'      => __( 'Product Currency', 'woocommerce-jetpack' ),
				'options'    => $currency_codes,
			),
		);
		return $options;
	}

	/**
	 * change_currency_symbol_on_product_edit.
	 */
	function change_currency_symbol_on_product_edit( $currency_symbol, $currency ) {
		if ( is_admin() ) {
			global $pagenow;
			if ( 'post.php' === $pagenow && isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
				$multicurrency_base_price_currency = get_post_meta( get_the_ID(), '_' . 'wcj_multicurrency_base_price_currency', true );
				if ( '' != $multicurrency_base_price_currency ) {
					return wcj_get_currency_symbol( $multicurrency_base_price_currency );
				}
			}
		}
		return $currency_symbol;
	}

	/**
	 * add_settings.
	 *
	 * @version 2.6.0
	 */
	function add_settings() {
		$currency_from = get_woocommerce_currency();
		$all_currencies = wcj_get_currencies_names_and_symbols();
		foreach ( $all_currencies as $currency_key => $currency_name ) {
			if ( $currency_from == $currency_key ) {
				unset( $all_currencies[ $currency_key ] );
			}
		}
		$settings = array(
			array(
				'title'    => __( 'Options', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_multicurrency_base_price_options',
			),
			array(
				'title'    => __( 'Exchange Rates Updates', 'woocommerce-jetpack' ),
				'id'       => 'wcj_multicurrency_base_price_exchange_rate_update',
				'default'  => 'manual',
				'type'     => 'select',
				'options'  => array(
					'manual' => __( 'Enter Rates Manually', 'woocommerce-jetpack' ),
					'auto'   => __( 'Automatically via Currency Exchange Rates module', 'woocommerce-jetpack' ),
				),
				'desc'     => ( '' == apply_filters( 'booster_get_message', '', 'desc' ) ) ?
					__( 'Visit', 'woocommerce-jetpack' ) . ' <a href="' . admin_url( 'admin.php?page=wc-settings&tab=jetpack&wcj-cat=prices_and_currencies&section=currency_exchange_rates' ) . '">' . __( 'Currency Exchange Rates module', 'woocommerce-jetpack' ) . '</a>'
					:
					apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => apply_filters( 'booster_get_message', '', 'disabled' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_multicurrency_base_price_options',
			),
			array(
				'title'    => __( 'Currencies Options', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_multicurrency_base_price_currencies_options',
			),
			array(
				'title'    => __( 'Total Currencies', 'woocommerce-jetpack' ),
				'id'       => 'wcj_multicurrency_base_price_total_number',
				'default'  => 1,
				'type'     => 'custom_number',
				'desc'     => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => array_merge(
					is_array( apply_filters( 'booster_get_message', '', 'readonly' ) ) ? apply_filters( 'booster_get_message', '', 'readonly' ) : array(),
					array( 'step' => '1', 'min'  => '1', )
				),
			),
		);
		$total_number = apply_filters( 'booster_get_option', 1, get_option( 'wcj_multicurrency_base_price_total_number', 1 ) );
		for ( $i = 1; $i <= $total_number; $i++ ) {
			$currency_to = get_option( 'wcj_multicurrency_base_price_currency_' . $i, $currency_from );
			$custom_attributes = array(
				'currency_from'        => $currency_from,
				'currency_to'          => $currency_to,
				'multiply_by_field_id' => 'wcj_multicurrency_base_price_exchange_rate_' . $i,
			);
			if ( $currency_from == $currency_to ) {
				$custom_attributes['disabled'] = 'disabled';
			}
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'Currency', 'woocommerce-jetpack' ) . ' #' . $i,
					'id'       => 'wcj_multicurrency_base_price_currency_' . $i,
					'default'  => $currency_from,
					'type'     => 'select',
					'options'  => $all_currencies,
					'css'      => 'width:250px;',
				),
				array(
					'title'                    => '',
					'id'                       => 'wcj_multicurrency_base_price_exchange_rate_' . $i,
					'default'                  => 1,
					'type'                     => 'exchange_rate',
					'custom_attributes'        => array( 'step' => '0.000001', 'min'  => '0', ),
					'custom_attributes_button' => $custom_attributes,
					'css'                      => 'width:100px;',
					'value'                    => $currency_from . '/' . $currency_to,
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_multicurrency_base_price_currencies_options',
			),
		) );
		return $settings;
	}
}

endif;

return new WCJ_Multicurrency_Base_Price();
