<?php
/**
 * WooCommerce Jetpack Price Labels
 *
 * The WooCommerce Jetpack Price Labels class.
 *
 * @version 2.7.1
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCJ_Price_Labels' ) ) :

class WCJ_Price_Labels extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.7.0
	 */
	function __construct() {

		$this->id         = 'price_labels';
		$this->short_desc = __( 'Custom Price Labels', 'woocommerce-jetpack' );
		$this->desc       = __( 'Create any custom price label for any WooCommerce product.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-custom-price-labels/';
		parent::__construct();

		// Custom Price Labels - fields array
		$this->custom_tab_group_name = 'wcj_price_labels'; // for compatibility with Custom Price Label Pro plugin should use 'simple_is_custom_pricing_label'
		$this->custom_tab_sections = array( '_instead', '_before', '_between', '_after', );
		$this->custom_tab_sections_titles = array(
			'_instead' => __( 'Instead of the price', 'woocommerce-jetpack' ), // for compatibility with Custom Price Label Pro plugin should use ''
			'_before'  => __( 'Before the price', 'woocommerce-jetpack' ),
			'_between' => __( 'Between regular and sale prices', 'woocommerce-jetpack' ),
			'_after'   => __( 'After the price', 'woocommerce-jetpack' ),
		);
		$this->custom_tab_section_variations = array( '_text', '_enabled', '_home', '_products', '_single', '_page', '_cart', /*'_simple',*/ '_variable', '_variation', /*'_grouped',*/ );
		$this->custom_tab_section_variations_titles = array(
			'_text'      => '', // 'The label',
			'_enabled'   => __( 'Enable', 'woocommerce-jetpack' ), // for compatibility with Custom Price Label Pro plugin should use ''
			'_home'      => __( 'Hide on home page', 'woocommerce-jetpack' ),
			'_products'  => __( 'Hide on products page', 'woocommerce-jetpack' ),
			'_single'    => __( 'Hide on single', 'woocommerce-jetpack' ),
			'_page'      => __( 'Hide on all pages', 'woocommerce-jetpack' ),
			'_cart'      => __( 'Hide on cart page only', 'woocommerce-jetpack' ),
//			'_simple'    => __( 'Hide for simple product', 'woocommerce-jetpack' ),
			'_variable'  => __( 'Hide for main price', 'woocommerce-jetpack' ),
			'_variation' => __( 'Hide for all variations', 'woocommerce-jetpack' ),
//			'_grouped'   => __( 'Hide for grouped product', 'woocommerce-jetpack' ),
		);

		add_action( 'init', array( $this, 'add_settings_hook' ) );

		if ( $this->is_enabled() ) {

			if ( 'yes' === get_option( 'wcj_local_price_labels_enabled', 'yes' ) ) {
				// Meta box (admin)
				add_action( 'add_meta_boxes', array( $this, 'add_price_label_meta_box' ) );
				add_action( 'save_post_product', array( $this, 'save_custom_price_labels' ), 999, 2 );
			}

			// Prices Hooks
			$this->prices_filters = array(
				// Cart
				'woocommerce_cart_item_price',
				// Composite Products
				'woocommerce_composite_sale_price_html',
				'woocommerce_composite_price_html',
				'woocommerce_composite_empty_price_html',
				'woocommerce_composite_free_sale_price_html',
				'woocommerce_composite_free_price_html',
				// Booking Products
				'woocommerce_get_price_html',
				// Simple Products
				'woocommerce_empty_price_html',
				'woocommerce_free_price_html',
				'woocommerce_free_sale_price_html',
				'woocommerce_price_html',
				'woocommerce_sale_price_html',
				// Grouped Products
				'woocommerce_grouped_price_html',
				// Variable Products
				'woocommerce_variable_empty_price_html',
				'woocommerce_variable_free_price_html',
				'woocommerce_variable_free_sale_price_html',
				'woocommerce_variable_price_html',
				'woocommerce_variable_sale_price_html',
				// Variable Products - Variations
				'woocommerce_variation_empty_price_html',
				'woocommerce_variation_free_price_html',
				'woocommerce_variation_price_html',
				'woocommerce_variation_sale_price_html',
				// WooCommerce Subscription
				'woocommerce_subscriptions_product_price_string',
				'woocommerce_variable_subscription_price_html',
			);
			foreach ( $this->prices_filters as $the_filter ) {
				add_filter( $the_filter, array( $this, 'custom_price' ), 100, 2 );
			}
		}
	}

	/**
	 * save_custom_price_labels.
	 */
	function save_custom_price_labels( $post_id, $post ) {
		if ( ! isset( $_POST['woojetpack_price_labels_save_post'] ) ) {
			return;
		}
		foreach ( $this->custom_tab_sections as $custom_tab_section ) {
			foreach ( $this->custom_tab_section_variations as $custom_tab_section_variation ) {
				$option_name = $this->custom_tab_group_name . $custom_tab_section . $custom_tab_section_variation;
				if ( $custom_tab_section_variation == '_text' ) {
					if ( isset( $_POST[ $option_name ] ) ) {
						update_post_meta( $post_id, '_' . $option_name, $_POST[ $option_name ] );
					}
				} else {
					if ( isset( $_POST[ $option_name ] ) ) {
						update_post_meta( $post_id, '_' . $option_name, $_POST[ $option_name ] );
					} else {
						update_post_meta( $post_id, '_' . $option_name, 'off' );
					}
				}
			}
		}
	}

	/**
	 * add_price_label_meta_box.
	 *
	 * @version 2.4.8
	 */
	function add_price_label_meta_box() {
		add_meta_box(
			'wc-jetpack-price-labels',
			__( 'Booster: Custom Price Labels', 'woocommerce-jetpack' ),
			array( $this, 'create_price_label_meta_box' ),
			'product',
			'normal',
			'high'
		);
	}

	/*
	 * create_price_label_meta_box - back end.
	 *
	 * @version 2.4.8
	 */
	function create_price_label_meta_box() {
		$current_post_id = get_the_ID();
		echo '<table style="width:100%;">';
		echo '<tr>';
		foreach ( $this->custom_tab_sections as $custom_tab_section ) {
			echo '<td style="width:25%;"><h4>' . $this->custom_tab_sections_titles[ $custom_tab_section ] . '</h4></td>';
		}
		echo '</tr>';
		echo '<tr>';
		foreach ( $this->custom_tab_sections as $custom_tab_section ) {
			echo '<td style="width:25%;">';
			echo '<ul>';
			foreach ( $this->custom_tab_section_variations as $custom_tab_section_variation ) {
				$option_name = $this->custom_tab_group_name . $custom_tab_section . $custom_tab_section_variation;
				if ( $custom_tab_section_variation == '_text' ) {
					if ( $custom_tab_section != '_instead' ) {
						$disabled_if_no_plus = apply_filters( 'booster_get_message', '', 'readonly_string' );
					} else {
						$disabled_if_no_plus = '';
					}
					$label_text = get_post_meta( $current_post_id, '_' . $option_name, true );
					$label_text = str_replace ( '"', '&quot;', $label_text );
					echo '<li>' . $this->custom_tab_section_variations_titles[ $custom_tab_section_variation ] . '<textarea style="width:95%;min-width:100px;height:100px;" ' . $disabled_if_no_plus . ' name="' . $option_name . '">' . $label_text . '</textarea></li>';
				} else {
					if ( '_home' === $custom_tab_section_variation ) {
						echo '<li><h5>Hide by page type</h5></li>';
					}
					if ( '_variable' === $custom_tab_section_variation ) {
						echo '<li><h5>Variable products</h5></li>';
					}
					if ( '_instead' != $custom_tab_section ) {
						$disabled_if_no_plus = apply_filters( 'booster_get_message', '', 'disabled_string' );
					} else {
						$disabled_if_no_plus = '';
					}
					echo '<li><input class="checkbox" type="checkbox" ' . $disabled_if_no_plus . ' name="' . $option_name . '" id="' . $option_name . '" ' .
						checked( get_post_meta( $current_post_id, '_' . $option_name, true ), 'on', false ) . ' /> ' . $this->custom_tab_section_variations_titles[ $custom_tab_section_variation ] . '</li>';
				}
			}
			echo '</ul>';
			echo '</td>';
		}
		echo '</tr>';
		echo '<tr>';
		foreach ( $this->custom_tab_sections as $custom_tab_section ) {
			if ( '_instead' != $custom_tab_section )
				$disabled_if_no_plus = apply_filters( 'booster_get_message', '', 'desc_above' );
			else
				$disabled_if_no_plus = '';
			echo '<td style="width:25%;">' . $disabled_if_no_plus . '</td>';
		}
		echo '</tr>';
		echo '</table>';
		echo '<input type="hidden" name="woojetpack_price_labels_save_post" value="woojetpack_price_labels_save_post">';
	}

	/*
	 * customize_price
	 *
	 * @todo recheck if `str_replace( 'From: ', '', $price );` is necessary? Also this works only for English WP installs.
	 */
	function customize_price( $price, $custom_tab_section, $custom_label ) {
		switch ( $custom_tab_section ) {
			case '_instead':
				$price = $custom_label;
				break;
			case '_before':
				$price = apply_filters( 'booster_get_option', $price, $custom_label . $price );
				break;
			case '_between':
				$price = apply_filters( 'booster_get_option', $price, str_replace( '</del> <ins>', '</del>' . $custom_label . '<ins>', $price ) );
				break;
			case '_after':
				$price = apply_filters( 'booster_get_option', $price, $price . $custom_label );
				break;
		}
		return str_replace( 'From: ', '', $price );
	}

	/*
	 * custom_price - front end.
	 *
	 * @version 2.7.1
	 * @todo    rewrite this with less filters (e.g. `woocommerce_get_price_html` only) - at least for `! WCJ_IS_WC_VERSION_BELOW_3`
	 */
	function custom_price( $price, $product ) {

		if ( ! wcj_is_frontend() ) {
			return $price;
		}
		$current_filter_name = current_filter();
		if ( 'woocommerce_cart_item_price' === $current_filter_name ) {
			$product = $product['data'];
		}
		$_product_id   = wcj_get_product_id_or_variation_parent_id( $product );
		$_product_type = ( WCJ_IS_WC_VERSION_BELOW_3 ? $product->product_type : $product->get_type() );
		if ( WCJ_IS_WC_VERSION_BELOW_3 && 'woocommerce_get_price_html' === $current_filter_name && ! in_array( $_product_type, apply_filters( 'wcj_price_labels_woocommerce_get_price_html_allowed_post_types', array( 'booking' ), $_product_type ) ) ) {
			return $price;
		}
		if ( ! WCJ_IS_WC_VERSION_BELOW_3 && 'woocommerce_variable_price_html' === $current_filter_name ) {
			return $price;
		}
		if ( ! WCJ_IS_WC_VERSION_BELOW_3 && 'woocommerce_get_price_html' === $current_filter_name && $product->is_type( 'variation' ) ) {
			$current_filter_name = 'woocommerce_variation_price_html';
		}
		if ( ! WCJ_IS_WC_VERSION_BELOW_3 && 'woocommerce_get_price_html' === $current_filter_name && $product->is_type( 'variable' ) ) {
			$current_filter_name = 'woocommerce_variable_price_html';
		}
		if ( 'subscription' === $_product_type && 'woocommerce_subscriptions_product_price_string' !== $current_filter_name ) {
			return $price;
		}
		if ( 'variable-subscription' === $_product_type && 'woocommerce_variable_subscription_price_html' !== $current_filter_name ) {
			return $price;
		}
		if ( 'subscription_variation' === $_product_type && 'woocommerce_subscriptions_product_price_string' !== $current_filter_name ) {
			return $price;
		}
		if ( 'subscription_variation' === $_product_type && 'woocommerce_subscriptions_product_price_string' === $current_filter_name ) {
			$current_filter_name = 'woocommerce_variation_subscription_price_html';
		}

		// Global
		$do_apply_global = true;
		$products_incl = get_option( 'wcj_global_price_labels_products_incl', array() );
		if ( ! empty( $products_incl ) ) {
			$do_apply_global = ( in_array( $_product_id, $products_incl ) ) ? true : false;
		}
		$products_excl = get_option( 'wcj_global_price_labels_products_excl', array() );
		if ( ! empty( $products_excl ) ) {
			$do_apply_global = ( in_array( $_product_id, $products_excl ) ) ? false : true;
		}
		$product_categories = get_the_terms( $_product_id, 'product_cat' );
		$product_categories_incl = get_option( 'wcj_global_price_labels_product_cats_incl', array() );
		if ( ! empty( $product_categories_incl ) ) {
			$do_apply_global = false;
			if ( ! empty( $product_categories ) ) {
				foreach ( $product_categories as $product_category ) {
					if ( in_array( $product_category->term_id, $product_categories_incl ) ) {
						$do_apply_global = true;
						break;
					}
				}
			}
		}
		$product_categories_excl = get_option( 'wcj_global_price_labels_product_cats_excl', array() );
		if ( ! empty( $product_categories_excl ) ) {
			$do_apply_global = true;
			if ( ! empty( $product_categories ) ) {
				foreach ( $product_categories as $product_category ) {
					if ( in_array( $product_category->term_id, $product_categories_excl ) ) {
						$do_apply_global = false;
						break;
					}
				}
			}
		}
		if ( $do_apply_global ) {
			// Check product type
			$product_types_incl = get_option( 'wcj_global_price_labels_product_types_incl', '' );
			if ( ! empty( $product_types_incl ) ) {
				$do_apply_global = false;
				foreach ( $product_types_incl as $product_type_incl ) {
					if ( $product->is_type( $product_type_incl ) ) {
						$do_apply_global = true;
						break;
					}
				}
			}
		}
		if ( $do_apply_global ) {
			// Global price labels - Add text before price
			$text_to_add_before = apply_filters( 'booster_get_option', '', get_option( 'wcj_global_price_labels_add_before_text' ) );
			if ( '' != $text_to_add_before ) {
				if ( apply_filters( 'wcj_price_labels_check_on_applying_label', true, $price, $text_to_add_before ) ) {
					$price = $text_to_add_before . $price;
				}
			}
			// Global price labels - Add text after price
			$text_to_add_after = get_option( 'wcj_global_price_labels_add_after_text' );
			if ( '' != $text_to_add_after ) {
				if ( apply_filters( 'wcj_price_labels_check_on_applying_label', true, $price, $text_to_add_after ) ) {
					$price = $price . $text_to_add_after;
				}
			}
			// Global price labels - Between regular and sale prices
			$text_to_add_between_regular_and_sale = get_option( 'wcj_global_price_labels_between_regular_and_sale_text' );
			if ( '' != $text_to_add_between_regular_and_sale ) {
				$price = apply_filters( 'booster_get_option', $price, str_replace( '</del> <ins>', '</del>' . $text_to_add_between_regular_and_sale . '<ins>', $price ) );
			}
			// Global price labels - Remove text from price
			$text_to_remove = apply_filters( 'booster_get_option', '', get_option( 'wcj_global_price_labels_remove_text' ) );
			if ( '' != $text_to_remove ) {
				$price = str_replace( $text_to_remove, '', $price );
			}
			// Global price labels - Replace in price
			$text_to_replace = apply_filters( 'booster_get_option', '', get_option( 'wcj_global_price_labels_replace_text' ) );
			$text_to_replace_with = apply_filters( 'booster_get_option', '', get_option( 'wcj_global_price_labels_replace_with_text' ) );
			if ( '' != $text_to_replace && '' != $text_to_replace_with ) {
				$price = str_replace( $text_to_replace, $text_to_replace_with, $price );
			}
			// Global price labels - Instead of the price
			if ( '' != ( $text_instead = get_option( 'wcj_global_price_labels_instead_text', '' ) ) ) {
				$price = $text_instead;
			}
		}

		// Per product
		if ( 'yes' === get_option( 'wcj_local_price_labels_enabled', 'yes' ) ) {
			foreach ( $this->custom_tab_sections as $custom_tab_section ) {
				$labels_array = array();
				foreach ( $this->custom_tab_section_variations as $custom_tab_section_variation ) {
					$option_name = $this->custom_tab_group_name . $custom_tab_section . $custom_tab_section_variation;
					$labels_array[ 'variation' . $custom_tab_section_variation ] = get_post_meta( $_product_id, '_' . $option_name, true );
				}
				if ( 'on' === $labels_array[ 'variation_enabled' ] ) {
					if (
						( ( 'off' === $labels_array['variation_home'] )     && ( is_front_page() ) ) ||
						( ( 'off' === $labels_array['variation_products'] ) && ( is_archive() ) ) ||
						( ( 'off' === $labels_array['variation_single'] )   && ( is_single() ) ) ||
						( ( 'off' === $labels_array['variation_page'] )     && ( is_page() && ! is_front_page() ) )
					) {
						if ( 'woocommerce_cart_item_price' === $current_filter_name && 'on' === $labels_array['variation_cart'] ) {
							continue;
						}
						$variable_filters_array = array(
							'woocommerce_variable_empty_price_html',
							'woocommerce_variable_free_price_html',
							'woocommerce_variable_free_sale_price_html',
							'woocommerce_variable_price_html',
							'woocommerce_variable_sale_price_html',
							'woocommerce_variable_subscription_price_html',
						);
						$variation_filters_array = array(
							'woocommerce_variation_empty_price_html',
							'woocommerce_variation_free_price_html',
							'woocommerce_variation_price_html',
							'woocommerce_variation_sale_price_html',
							'woocommerce_variation_subscription_price_html', // pseudo filter!
						);
						if (
							(   in_array( $current_filter_name, $variable_filters_array )  && ( 'off' === $labels_array['variation_variable'] ) ) ||
							(   in_array( $current_filter_name, $variation_filters_array ) && ( 'off' === $labels_array['variation_variation'] ) ) ||
							( ! in_array( $current_filter_name, $variable_filters_array )  && ! in_array( $current_filter_name, $variation_filters_array ) )
						) {
							$price = $this->customize_price( $price, $custom_tab_section, $labels_array['variation_text'] );
						}
					}
				}
			}
		}

		// For debug
//		return do_shortcode( $price . $current_filter_name . ( WCJ_IS_WC_VERSION_BELOW_3 ? $product->product_type : $product->get_type() ) . $labels_array['variation_variable'] . $labels_array['variation_variation'] );

		global $wcj_product_id_for_shortcode;
		$wcj_product_id_for_shortcode = wcj_get_product_id( $product );
		$result = do_shortcode( $price );
		$wcj_product_id_for_shortcode = 0;
		return $result;
	}

	/*
	 * add_settings.
	 *
	 * @version 2.7.0
	 * @since   2.3.7
	 */
	function add_settings() {

		$product_cats = array();
		$product_categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		foreach ( $product_categories as $product_category ) {
			$product_cats[ $product_category->term_id ] = $product_category->name;
		}

		$products = wcj_get_products();

		$settings = array(
			array(
				'title'     => __( 'Custom Price Labels - Globally', 'woocommerce-jetpack' ),
				'type'      => 'title',
				'desc'      => __( 'This section lets you set price labels for all products globally.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_options',
			),
			array(
				'title'     => __( 'Add before the price', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Enter text to add before all products prices. Leave blank to disable.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_add_before_text',
				'default'   => '',
				'type'      => 'custom_textarea',
				'desc'      => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => apply_filters( 'booster_get_message', '', 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Add after the price', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Enter text to add after all products prices. Leave blank to disable.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_add_after_text',
				'default'   => '',
				'type'      => 'custom_textarea',
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Add between regular and sale prices', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Enter text to add between regular and sale prices. Leave blank to disable.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_between_regular_and_sale_text',
				'default'   => '',
				'type'      => 'custom_textarea',
				'desc'      => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => apply_filters( 'booster_get_message', '', 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Remove from price', 'woocommerce-jetpack' ),
//				'desc'      => __( 'Enable the Custom Price Labels feature', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Enter text to remove from all products prices. Leave blank to disable.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_remove_text',
				'default'   => '',
				'type'      => 'custom_textarea',
				'desc'      => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => apply_filters( 'booster_get_message', '', 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Replace in price', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Enter text to replace in all products prices. Leave blank to disable.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_replace_text',
				'default'   => '',
				'type'      => 'custom_textarea',
				'desc'      => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes'
				            => apply_filters( 'booster_get_message', '', 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => '',
				'desc_tip'  => __( 'Enter text to replace with. Leave blank to disable.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_replace_with_text',
				'default'   => '',
				'type'      => 'custom_textarea',
				'desc'      => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => apply_filters( 'booster_get_message', '', 'readonly' ),
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Instead of the price', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Enter text to display instead of the price. Leave blank to disable.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_instead_text',
				'default'   => '',
				'type'      => 'custom_textarea',
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Products - Include', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Apply global price labels only for selected products. Leave blank to disable the option.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_products_incl',
				'default'   => '',
				'type'      => 'multiselect',
				'class'     => 'chosen_select',
				'css'       => 'width: 450px;',
				'options'   => $products,
			),
			array(
				'title'     => __( 'Products - Exclude', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Do not apply global price labels only for selected products. Leave blank to disable the option.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_products_excl',
				'default'   => '',
				'type'      => 'multiselect',
				'class'     => 'chosen_select',
				'css'       => 'width: 450px;',
				'options'   => $products,
			),
			array(
				'title'     => __( 'Product Categories - Include', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Apply global price labels only for selected product categories. Leave blank to disable the option.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_product_cats_incl',
				'default'   => '',
				'type'      => 'multiselect',
				'class'     => 'chosen_select',
				'css'       => 'width: 450px;',
				'options'   => $product_cats,
			),
			array(
				'title'     => __( 'Product Categories - Exclude', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Do not apply global price labels only for selected product categories. Leave blank to disable the option.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_product_cats_excl',
				'default'   => '',
				'type'      => 'multiselect',
				'class'     => 'chosen_select',
				'css'       => 'width: 450px;',
				'options'   => $product_cats,
			),
			array(
				'title'     => __( 'Product Types - Include', 'woocommerce-jetpack' ),
				'desc_tip'  => __( 'Apply global price labels only for selected product types. Leave blank to disable the option.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_global_price_labels_product_types_incl',
				'default'   => '',
				'type'      => 'multiselect',
				'class'     => 'chosen_select',
				'css'       => 'width: 450px;',
				'options'   => array_merge( wc_get_product_types(), array( 'variation' => __( 'Variable product\'s variation', 'woocommerce-jetpack' ) ) ),
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'wcj_global_price_labels_options',
			),
			array(
				'title'     => __( 'Custom Price Labels - Per Product', 'woocommerce-jetpack' ),
				'type'      => 'title',
				'id'        => 'wcj_local_price_labels_options'
			),
			array(
				'title'     => __( 'Enable', 'woocommerce-jetpack' ),
				'desc'      => __( 'This will add metaboxes to each product\'s admin edit page.', 'woocommerce-jetpack' ),
				'id'        => 'wcj_local_price_labels_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'wcj_local_price_labels_options',
			),
		);
		return $settings;
	}
}

endif;

return new WCJ_Price_Labels();
