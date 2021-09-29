<?php
/**
 * WooCommerce Jetpack Product Add To Cart
 *
 * The WooCommerce Jetpack Product Add To Cart class.
 *
 * @version 2.6.0
 * @since   2.2.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_Product_Add_To_Cart' ) ) :

class WCJ_Product_Add_To_Cart extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.6.0
	 */
	function __construct() {

		$this->id         = 'product_add_to_cart';
		$this->short_desc = __( 'Product Add to Cart', 'woocommerce-jetpack' );
		$this->desc       = __( 'Set any local url to redirect to on WooCommerce Add to Cart.', 'woocommerce-jetpack' )
			. ' ' . __( 'Automatically add to cart on product visit.', 'woocommerce-jetpack' )
			. ' ' . __( 'Display radio buttons instead of drop box for variable products.', 'woocommerce-jetpack' )
			. ' ' . __( 'Disable quantity input.', 'woocommerce-jetpack' )
			. ' ' . __( 'Disable add to cart button on per product basis.', 'woocommerce-jetpack' )
			. ' ' . __( 'Open external products on add to cart in new window.', 'woocommerce-jetpack' )
			. ' ' . __( 'Replace Add to Cart button on archives with button from single product pages.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-product-add-to-cart/';
		parent::__construct();

		if ( $this->is_enabled() ) {

			// Metaboxes
			if (
				'yes' === get_option( 'wcj_add_to_cart_button_per_product_enabled', 'no' ) ||
				'yes' === get_option( 'wcj_add_to_cart_button_custom_loop_url_per_product_enabled', 'no' ) ||
				'yes' === get_option( 'wcj_add_to_cart_button_ajax_per_product_enabled', 'no' ) ||
				'per_product' === get_option( 'wcj_add_to_cart_on_visit_enabled', 'no' )
			) {
				add_action( 'add_meta_boxes',    array( $this, 'add_meta_box' ) );
				add_action( 'save_post_product', array( $this, 'save_meta_box' ), PHP_INT_MAX, 2 );
			}

			// Local Redirect
			if ( 'yes' === get_option( 'wcj_add_to_cart_redirect_enabled', 'no' ) ) {
				add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'redirect_to_url' ), PHP_INT_MAX );
			}

			// Add to Cart on Visit
			if ( 'no' != get_option( 'wcj_add_to_cart_on_visit_enabled', 'no' ) ) {
				add_action( 'wp', array( $this, 'add_to_cart_on_visit' ), 98 );
			}

			// Variable Add to Cart Template
			if ( 'yes' === apply_filters( 'booster_get_option', 'wcj', get_option( 'wcj_add_to_cart_variable_as_radio_enabled', 'no' ) ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_variable_add_to_cart_scripts' ) );
				add_filter( 'wc_get_template', array( $this, 'change_variable_add_to_cart_template' ), PHP_INT_MAX, 5 );
			}

			// Replace Add to Cart Loop with Single
			if ( 'yes' === get_option( 'wcj_add_to_cart_replace_loop_w_single_enabled', 'no' ) ) {
				add_action( 'init', array( $this, 'add_to_cart_replace_loop_w_single' ), PHP_INT_MAX );
			} elseif ( 'variable_only' === get_option( 'wcj_add_to_cart_replace_loop_w_single_enabled', 'no' ) ) {
				add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_variable_replace_loop_w_single' ), PHP_INT_MAX );
			}

			// Quantity
			if ( 'yes' === get_option( 'wcj_add_to_cart_quantity_disable', 'no' ) || 'yes' === get_option( 'wcj_add_to_cart_quantity_disable_cart', 'no' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_disable_quantity_add_to_cart_script' ) );
			}

			// Button - Disabling - Archives
			if ( 'yes' === get_option( 'wcj_add_to_cart_button_disable_archives', 'no' ) ) {
				add_action( 'init', array( $this, 'add_to_cart_button_disable_archives' ), PHP_INT_MAX );
			}
			// Button - Disabling - Single Product
			if ( 'yes' === get_option( 'wcj_add_to_cart_button_disable_single', 'no' ) ) {
				add_action( 'init', array( $this, 'add_to_cart_button_disable_single' ), PHP_INT_MAX );
			}
			// Button per product - Disabling
			if ( 'yes' === get_option( 'wcj_add_to_cart_button_per_product_enabled', 'no' ) ) {
				add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'add_to_cart_button_disable_start' ), PHP_INT_MAX, 0 );
				add_action( 'woocommerce_after_add_to_cart_button',  array( $this, 'add_to_cart_button_disable_end' ),   PHP_INT_MAX, 0 );
				add_filter( 'woocommerce_loop_add_to_cart_link',     array( $this, 'add_to_cart_button_loop_disable' ),  PHP_INT_MAX, 2 );
			}
			// Button per product Custom URL
			if ( 'yes' === get_option( 'wcj_add_to_cart_button_custom_loop_url_per_product_enabled', 'no' ) ) {
				add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'custom_add_to_cart_loop_url' ), PHP_INT_MAX, 2 );
			}
			// Button per product AJAX
			if ( 'yes' === get_option( 'wcj_add_to_cart_button_ajax_per_product_enabled', 'no' ) ) {
				add_filter( 'woocommerce_product_supports', array( $this, 'manage_add_to_cart_ajax' ), PHP_INT_MAX, 3 );
			}

			// External Products
			if ( 'yes' === get_option( 'wcj_add_to_cart_button_external_open_new_window_single', 'no' ) ) {
				add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'replace_external_with_custom_add_to_cart_on_single_start' ), PHP_INT_MAX );
				add_action( 'woocommerce_after_add_to_cart_button',  array( $this, 'replace_external_with_custom_add_to_cart_on_single_end' ), PHP_INT_MAX );
			}
			if ( 'yes' === get_option( 'wcj_add_to_cart_button_external_open_new_window_loop', 'no' ) ) {
				add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'replace_external_with_custom_add_to_cart_in_loop' ), PHP_INT_MAX );
			}
		}
	}

	/**
	 * add_to_cart_button_disable_single.
	 *
	 * @version 2.6.0
	 * @since   2.6.0
	 */
	function add_to_cart_button_disable_single() {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}

	/**
	 * add_to_cart_button_disable_archives.
	 *
	 * @version 2.6.0
	 * @since   2.6.0
	 */
	function add_to_cart_button_disable_archives() {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}

	/**
	 * add_to_cart_variable_replace_loop_w_single.
	 *
	 * @version 2.6.0
	 * @since   2.6.0
	 */
	function add_to_cart_variable_replace_loop_w_single( $link ) {
		global $product;
		if ( $product->is_type( 'variable' ) ) {
			do_action( 'woocommerce_variable_add_to_cart' );
			return '';
		}
		return $link;
	}

	/**
	 * add_to_cart_replace_loop_w_single.
	 *
	 * @version 2.6.0
	 * @since   2.6.0
	 */
	function add_to_cart_replace_loop_w_single() {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',   10 );
		add_action(    'woocommerce_after_shop_loop_item', 'woocommerce_template_single_add_to_cart', 30 );
	}

	/**
	 * replace_external_with_custom_add_to_cart_on_single_start.
	 *
	 * @version 2.5.3
	 * @since   2.5.3
	 */
	function replace_external_with_custom_add_to_cart_on_single_start() {
		global $product;
		if ( $product->is_type( 'external' ) ) {
			ob_start();
		}
	}

	/**
	 * replace_external_with_custom_add_to_cart_on_single_end.
	 *
	 * @version 2.5.3
	 * @since   2.5.3
	 */
	function replace_external_with_custom_add_to_cart_on_single_end() {
		global $product;
		if ( $product->is_type( 'external' ) ) {
			$button_html = ob_get_contents();
			ob_end_clean();
			echo str_replace( '<a href=', '<a target="_blank" href=', $button_html );
		}
	}

	/**
	 * replace_external_with_custom_add_to_cart_in_loop.
	 *
	 * @version 2.5.3
	 * @since   2.5.3
	 */
	function replace_external_with_custom_add_to_cart_in_loop( $link_html ) {
		global $product;
		if ( $product->is_type( 'external' ) ) {
			$link_html = str_replace( '<a rel=', '<a target="_blank" rel=', $link_html );
		}
		return $link_html;
	}

	/**
	 * manage_add_to_cart_ajax.
	 *
	 * @version 2.5.6
	 * @since   2.5.6
	 */
	function manage_add_to_cart_ajax( $supports, $feature, $_product ) {
		if ( 'ajax_add_to_cart' === $feature && 0 != get_the_ID() && 'as_shop_default' != ( $value = get_post_meta( get_the_ID(), '_' . 'wcj_add_to_cart_button_ajax_disable', true ) ) ) {
			return ( 'yes' === $value ) ? false : true;
		}
		return $supports;
	}

	/**
	 * custom_add_to_cart_loop_url.
	 *
	 * @version 2.5.6
	 * @since   2.5.6
	 */
	function custom_add_to_cart_loop_url( $url, $_product ) {
		if ( 0 != get_the_ID() && '' != ( $custom_url = get_post_meta( get_the_ID(), '_' . 'wcj_add_to_cart_button_loop_custom_url', true ) ) ) {
			return $custom_url;
		}
		return $url;
	}

	/**
	 * add_to_cart_button_loop_disable.
	 *
	 * @version 2.5.2
	 * @since   2.5.2
	 */
	function add_to_cart_button_loop_disable( $link, $_product ) {
		if ( 0 != get_the_ID() && 'yes' === get_post_meta( get_the_ID(), '_' . 'wcj_add_to_cart_button_loop_disable', true ) ) {
			return '';
		}
		return $link;
	}

	/**
	 * add_to_cart_button_disable_end.
	 *
	 * @version 2.5.2
	 * @since   2.5.2
	 */
	function add_to_cart_button_disable_end() {
		if ( 0 != get_the_ID() && 'yes' === get_post_meta( get_the_ID(), '_' . 'wcj_add_to_cart_button_disable', true ) ) {
			ob_end_clean();
		}
	}

	/**
	 * add_to_cart_button_disable_start.
	 *
	 * @version 2.5.2
	 * @since   2.5.2
	 */
	function add_to_cart_button_disable_start() {
		if ( 0 != get_the_ID() && 'yes' === get_post_meta( get_the_ID(), '_' . 'wcj_add_to_cart_button_disable', true ) ) {
			ob_start();
		}
	}

	/**
	 * get_meta_box_options.
	 *
	 * @version 2.6.0
	 * @since   2.5.2
	 */
	function get_meta_box_options() {
		$options = array();
		if ( 'per_product' === get_option( 'wcj_add_to_cart_on_visit_enabled', 'no' ) ) {
			$options = array_merge( $options, array(
				array(
					'name'       => 'wcj_add_to_cart_on_visit_enabled',
					'default'    => 'no',
					'type'       => 'select',
					'options'    => array(
						'yes' => __( 'Yes', 'woocommerce-jetpack' ),
						'no'  => __( 'No', 'woocommerce-jetpack' ),
					),
					'title'      => __( 'Add to Cart on Visit', 'woocommerce-jetpack' ),
				),
			) );
		}
		if ( 'yes' === get_option( 'wcj_add_to_cart_button_per_product_enabled', 'no' ) ) {
			$options = array_merge( $options, array(
				array(
					'name'       => 'wcj_add_to_cart_button_disable',
					'default'    => 'no',
					'type'       => 'select',
					'options'    => array(
						'yes' => __( 'Yes', 'woocommerce-jetpack' ),
						'no'  => __( 'No', 'woocommerce-jetpack' ),
					),
					'title'      => __( 'Disable Add to Cart Button (Single Product Page)', 'woocommerce-jetpack' ),
				),
				array(
					'name'       => 'wcj_add_to_cart_button_loop_disable',
					'default'    => 'no',
					'type'       => 'select',
					'options'    => array(
						'yes' => __( 'Yes', 'woocommerce-jetpack' ),
						'no'  => __( 'No', 'woocommerce-jetpack' ),
					),
					'title'      => __( 'Disable Add to Cart Button (Category/Archives)', 'woocommerce-jetpack' ),
				),
			) );
		}
		if ( 'yes' === get_option( 'wcj_add_to_cart_button_custom_loop_url_per_product_enabled', 'no' ) ) {
			$options = array_merge( $options, array(
				array(
					'name'       => 'wcj_add_to_cart_button_loop_custom_url',
					'default'    => '',
					'type'       => 'text',
					'title'      => __( 'Custom Add to Cart Button URL (Category/Archives)', 'woocommerce-jetpack' ),
				),
			) );
		}
		if ( 'yes' === get_option( 'wcj_add_to_cart_button_ajax_per_product_enabled', 'no' ) ) {
			$options = array_merge( $options, array(
				array(
					'name'       => 'wcj_add_to_cart_button_ajax_disable',
					'default'    => 'as_shop_default',
					'type'       => 'select',
					'options'    => array(
						'as_shop_default' => __( 'As shop default (no changes)', 'woocommerce-jetpack' ),
						'yes'             => __( 'Disable', 'woocommerce-jetpack' ),
						'no'              => __( 'Enable', 'woocommerce-jetpack' ),
					),
					'title'      => __( 'Disable Add to Cart Button AJAX', 'woocommerce-jetpack' ),
				),
			) );
		}
		return $options;
	}

	/**
	 * enqueue_disable_quantity_add_to_cart_script.
	 *
	 * @version 2.5.2
	 * @since   2.5.2
	 * @todo    add "hide" (not just disable) option
	 */
	function enqueue_disable_quantity_add_to_cart_script() {
		if (
			( 'yes' === get_option( 'wcj_add_to_cart_quantity_disable', 'no' ) && is_product() ) ||
			( 'yes' === get_option( 'wcj_add_to_cart_quantity_disable_cart', 'no' ) && is_cart() )
		) {
			wp_enqueue_script( 'wcj-disable-quantity', wcj_plugin_url() . '/includes/js/wcj-disable-quantity.js', array( 'jquery' ) );
		}
	}

	/**
	 * enqueue_variable_add_to_cart_scripts.
	 *
	 * @version 2.4.8
	 * @since   2.4.8
	 */
	function enqueue_variable_add_to_cart_scripts() {
		wp_enqueue_script( 'wcj-variations', wcj_plugin_url() . '/includes/js/wcj-variations-frontend.js', array( 'jquery' ) );
	}

	/**
	 * change_variable_add_to_cart_template.
	 *
	 * @version 2.4.8
	 * @since   2.4.8
	 * @todo    fix - variations images to changing (maybe check Crowdfunding plugin)
	 */
	function change_variable_add_to_cart_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'single-product/add-to-cart/variable.php' == $template_name ) {
			$located = untrailingslashit( realpath( plugin_dir_path( __FILE__ ) . '/..' ) ) . '/includes/templates/wcj-add-to-cart-variable.php';
		}
		return $located;
	}

	/*
	 * redirect_to_url.
	 */
	function redirect_to_url( $url ) {
		global $woocommerce;
		$checkout_url = get_option( 'wcj_add_to_cart_redirect_url' );
		if ( '' === $checkout_url ) {
			$checkout_url = $woocommerce->cart->get_checkout_url();
		}
		return $checkout_url;
	}

	/*
	 * Add item to cart on visit.
	 *
	 * @version 2.7.0
	 */
	function add_to_cart_on_visit() {
		if ( ! is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && is_product() && ( $product_id = get_the_ID() ) ) {
			// If "per product" is selected - check product's settings (i.e. meta)
			if ( 'per_product' === get_option( 'wcj_add_to_cart_on_visit_enabled', 'no' ) ) {
				if ( 'yes' !== get_post_meta( $product_id, '_' . 'wcj_add_to_cart_on_visit_enabled', true ) ) {
					return;
				}
			}
			if ( isset( WC()->cart ) ) {
				// Check if product already in cart
				if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
					foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
						$_product = $values['data'];
						if ( wcj_get_product_id_or_variation_parent_id( $_product ) == $product_id ) {
							// Product found - do not add it
							return;
						}
					}
					// Product not found - add it
					WC()->cart->add_to_cart( $product_id );
				} else {
					// No products in cart - add it
					WC()->cart->add_to_cart( $product_id );
				}
			}
		}
	}

	/**
	 * get_settings.
	 *
	 * @version 2.7.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'    => __( 'Add to Cart Local Redirect Options', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'desc'     => __( 'This section lets you set any local URL to redirect to after successfully adding product to cart. Leave empty to redirect to checkout page (skipping the cart page).', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_redirect_options',
			),
			array(
				'title'    => __( 'Local Redirect', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_redirect_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Local Redirect URL', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'Performs a safe (local) redirect, using wp_redirect().', 'woocommerce-jetpack' ),
				'desc'     => __( 'Local redirect URL. Leave empty to redirect to checkout.', 'woocommerce-jetpack' ) .
					' ' . sprintf(
						__( 'For archives - "Enable AJAX add to cart buttons on archives" checkbox in <a href="%s">WooCommerce > Settings > Products > Display</a> must be disabled.', 'woocommerce-jetpack' ),
						admin_url( 'admin.php?page=wc-settings&tab=products&section=display' )
					),
				'id'       => 'wcj_add_to_cart_redirect_url',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'width:50%;min-width:300px;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_redirect_options',
			),
			array(
				'title'    => __( 'Add to Cart on Visit', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'desc'     => __( 'This section lets you enable automatically adding product to cart on visiting the product page. Product is only added once, so if it is already in cart - duplicate product is not added. ', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_on_visit_options',
			),
			array(
				'title'    => __( 'Add to Cart on Visit', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'If "Per Product" is selected - meta box will be added to each product\'s edit page.', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_on_visit_enabled',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'          => __( 'Disabled', 'woocommerce-jetpack' ),
					'yes'         => __( 'All products', 'woocommerce-jetpack' ),
					'per_product' => __( 'Per product', 'woocommerce-jetpack' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_on_visit_options',
			),
			array(
				'title'    => __( 'Add to Cart Variable Product', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_add_to_cart_variable_options',
			),
			array(
				'title'    => __( 'Display Radio Buttons Instead of Drop Box', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_variable_as_radio_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'booster_get_message', '', 'disabled' ),
				'desc_tip' => apply_filters( 'booster_get_message', '', 'desc' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_variable_options',
			),
			array(
				'title'    => __( 'Replace Add to Cart Button on Archives with Single', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_add_to_cart_replace_loop_w_single_options',
			),
			array(
				'title'    => __( 'Replace Add to Cart Button on Archives with Button from Single Product Pages', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_replace_loop_w_single_enabled',
				'default'  => 'no',
				'type'     => 'select',
				'options'  => array(
					'no'            => __( 'Disable', 'woocommerce-jetpack' ),
					'yes'           => __( 'Enable', 'woocommerce-jetpack' ),
					'variable_only' => __( 'Variable products only', 'woocommerce-jetpack' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_replace_loop_w_single_options',
			),
			array(
				'title'    => __( 'Add to Cart Quantity', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_add_to_cart_quantity_options',
			),
			array(
				'title'    => __( 'Disable Quantity Field for All Products', 'woocommerce-jetpack' ),
				'desc'     => __( 'Disable on Single Product Page', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_quantity_disable',
				'default'  => 'no',
				'type'     => 'checkbox',
				'checkboxgroup' => 'start',
			),
			array(
				'desc'     => __( 'Disable on Cart Page', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_quantity_disable_cart',
				'default'  => 'no',
				'type'     => 'checkbox',
				'checkboxgroup' => 'end',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_quantity_options',
			),
			array(
				'title'    => __( 'Add to Cart Button Disabling', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_add_to_cart_button_options',
			),
			array(
				'title'    => __( 'Disable Add to Cart Buttons on per Product Basis', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'This will add meta box to each product\'s edit page', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_button_per_product_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Disable Add to Cart Buttons on All Category/Archives Pages', 'woocommerce-jetpack' ),
				'desc'     => __( 'Disable Buttons', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_button_disable_archives',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Disable Add to Cart Buttons on All Single Product Pages', 'woocommerce-jetpack' ),
				'desc'     => __( 'Disable Buttons', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_button_disable_single',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_button_options',
			),
			array(
				'title'    => __( 'Add to Cart Button Custom URL', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_add_to_cart_button_custom_url_options',
			),
			array(
				'title'    => __( 'Custom Add to Cart Buttons URL on Archives on per Product Basis', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'This will add meta box to each product\'s edit page', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_button_custom_loop_url_per_product_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_button_custom_url_options',
			),
			array(
				'title'    => __( 'Add to Cart Button AJAX', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_add_to_cart_button_ajax_options',
			),
			array(
				'title'    => __( 'Disable/Enable Add to Cart Button AJAX on per Product Basis', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'This will add meta box to each product\'s edit page', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_button_ajax_per_product_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_button_ajax_options',
			),
			array(
				'title'    => __( 'External Products', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_add_to_cart_button_external_product_options',
			),
			array(
				'title'    => __( 'Open External Products on Add to Cart in New Window', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable on Single Product Pages', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_button_external_open_new_window_single',
				'default'  => 'no',
				'type'     => 'checkbox',
				'checkboxgroup' => 'start',
			),
			array(
				'desc'     => __( 'Enable on Category/Archive Pages', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_button_external_open_new_window_loop',
				'default'  => 'no',
				'type'     => 'checkbox',
				'checkboxgroup' => 'end',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_button_external_product_options',
			),
		);
		return $this->add_standard_settings( $settings );
	}
}

endif;

return new WCJ_Product_Add_To_Cart();
