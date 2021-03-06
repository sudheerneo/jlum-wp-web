<?php
/**
 * WooCommerce Jetpack Add to Cart
 *
 * The WooCommerce Jetpack Add to Cart class.
 *
 * @version 2.4.6
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCJ_Add_To_Cart' ) ) :

class WCJ_Add_To_Cart extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.4.6
	 */
	public function __construct() {

		$this->id         = 'add_to_cart';
		$this->short_desc = __( 'Add to Cart Labels', 'woocommerce-jetpack' );
		$this->desc       = __( 'Change text for Add to Cart button by WooCommerce product type, by product category or for individual products.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-add-to-cart-labels/';
		parent::__construct();

		if ( $this->is_enabled() ) {
			include_once( 'add-to-cart/class-wcj-add-to-cart-per-category.php' );
			include_once( 'add-to-cart/class-wcj-add-to-cart-per-product.php' );
			include_once( 'add-to-cart/class-wcj-add-to-cart-per-product-type.php' );
		}
	}

	/**
	 * get_per_product_type_settings.
	 */
	function get_per_product_type_settings() {

		$settings = array();

		$settings[] = array( 'title' => __( 'Per Product Type Options', 'woocommerce-jetpack' ), 'type' => 'title', 'desc' => 'This sections lets you set text for add to cart button for various products types and various conditions.', 'id' => 'wcj_add_to_cart_text_options' );

		$settings[] = array(
				'title'     => __( 'Per Product Labels', 'woocommerce-jetpack' ),
				'desc'      => __( 'Enable Section', 'woocommerce-jetpack' ),
				'id'        => 'wcj_add_to_cart_text_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
			);

		$groups_by_product_type = array(

			array(
				'id'        => 'simple',
				'title'     => __( 'Simple product', 'woocommerce-jetpack' ),
				'default'   => 'Add to cart',
			),
			array(
				'id'        => 'variable',
				'title'     => __( 'Variable product', 'woocommerce-jetpack' ),
				'default'   => 'Select options',
			),
			array(
				'id'        => 'external',
				'title'     => __( 'External product', 'woocommerce-jetpack' ),
				'default'   => 'Buy product',
			),
			array(
				'id'        => 'grouped',
				'title'     => __( 'Grouped product', 'woocommerce-jetpack' ),
				'default'   => 'View products',
			),
			array(
				'id'        => 'other',
				'title'     => __( 'Other product', 'woocommerce-jetpack' ),
				'default'   => 'Read more',
			),
		);

		foreach ( $groups_by_product_type as $group_by_product_type ) {

			$settings[] =
				array(
					'title'    => $group_by_product_type['title'],
					'id'       => 'wcj_add_to_cart_text_on_single_' . $group_by_product_type['id'],
					'desc'     => __( 'Single product view.', 'woocommerce-jetpack' ),
					'desc_tip' => __( 'Leave blank to disable.', 'woocommerce-jetpack' ) . ' ' . __( 'Default: ', 'woocommerce-jetpack' ) . $group_by_product_type['default'],
					'default'  => $group_by_product_type['default'],
					'type'     => 'text',
					'css'      => 'width:30%;min-width:300px;',
				);

			$settings[] =
				array(
					'title'    => '',
					'id'       => 'wcj_add_to_cart_text_on_archives_' . $group_by_product_type['id'],
					'desc'     => __( 'Product category (archive) view.', 'woocommerce-jetpack' ),
					'desc_tip' => __( 'Leave blank to disable.', 'woocommerce-jetpack' ) . ' ' . __( 'Default: ', 'woocommerce-jetpack' ) . $group_by_product_type['default'],
					'default'  => $group_by_product_type['default'],
					'type'     => 'text',
					'css'      => 'width:30%;min-width:300px;',
				);

			if ( 'variable' !== $group_by_product_type['id'] )
				$settings = array_merge( $settings, array(

					array (
						'title'    => '',
						'desc'     => __( 'Products with price set to 0 (i.e. free). Single product view.', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable. Default: Add to cart', 'woocommerce-jetpack' ),
						'id'       => 'wcj_add_to_cart_text_on_single_zero_price_' . $group_by_product_type['id'],
						'default'  => __( 'Add to cart', 'woocommerce-jetpack' ),
						'type'     => 'text',
						'css'      => 'width:30%;min-width:300px;',
					),
					array (
						'title'    => '',
						'desc'     => __( 'Products with price set to 0 (i.e. free). Product category (archive) view.', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable. Default: Add to cart', 'woocommerce-jetpack' ),
						'id'       => 'wcj_add_to_cart_text_on_archives_zero_price_' . $group_by_product_type['id'],
						'default'  => __( 'Add to cart', 'woocommerce-jetpack' ),
						'type'     => 'text',
						'css'      => 'width:30%;min-width:300px;',
					),

					array (
						'title'    => '',
						'desc'     => __( 'Products with empty price. Product category (archive) view.', 'woocommerce-jetpack' ),
						'desc_tip' => __( 'Leave blank to disable. Default: Read More', 'woocommerce-jetpack' ),
						'id'       => 'wcj_add_to_cart_text_on_archives_no_price_' . $group_by_product_type['id'],
						'default'  => __( 'Read More', 'woocommerce-jetpack' ),
						'type'     => 'text',
						'css'      => 'width:30%;min-width:300px;',
					),
				) );

			if ( 'external' === $group_by_product_type['id'] ) continue;

			$settings[] =
				array(
					'title'    => '',
					'id'       => 'wcj_add_to_cart_text_on_single_in_cart_' . $group_by_product_type['id'],
					'desc'     => __( 'Already in cart. Single product view.', 'woocommerce-jetpack' ),
					'desc_tip' => __( 'Leave blank to disable.', 'woocommerce-jetpack' ) . ' ' .
						__( 'Try: ', 'woocommerce-jetpack' ) . __( 'Already in cart - Add Again?', 'woocommerce-jetpack' ) . ' ' .
						__( 'Default: ', 'woocommerce-jetpack' ) . __( 'Add to cart', 'woocommerce-jetpack' ),
					'default'  => __( 'Add to cart', 'woocommerce-jetpack' ),
					'type'     => 'text',
					'css'      => 'width:30%;min-width:300px;',
				);

			$settings[] =
				array(
					'title'    => '',
					'id'       => 'wcj_add_to_cart_text_on_archives_in_cart_' . $group_by_product_type['id'],
					'desc'     => __( 'Already in cart. Product category (archive) view.', 'woocommerce-jetpack' ),
					'desc_tip' => __( 'Leave blank to disable.', 'woocommerce-jetpack' ) . ' ' .
						__( 'Try: ', 'woocommerce-jetpack' ) . __( 'Already in cart - Add Again?', 'woocommerce-jetpack' ) . ' ' .
						__( 'Default: ', 'woocommerce-jetpack' ) . __( 'Add to cart', 'woocommerce-jetpack' ),
					'default'  => __( 'Add to cart', 'woocommerce-jetpack' ),
					'type'     => 'text',
					'css'      => 'width:30%;min-width:300px;',
				);
		}

		$settings[] = array( 'type' => 'sectionend', 'id' => 'wcj_add_to_cart_text_options' );

		return $settings;
	}

	/**
	 * get_per_product_settings.
	 */
	function get_per_product_settings() {
		$settings = array(
			array(
				'title'    => __( 'Per Product Options', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'desc'     => __( 'This section lets you set Add to Cart button text on per product basis. When enabled, label for each product can be changed in "Edit Product".', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_per_product_options',
			),
			array(
				'title'    => __( 'Per Product Labels', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable Section', 'woocommerce-jetpack' ),
				'desc_tip' => '',
				'id'       => 'wcj_add_to_cart_per_product_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_add_to_cart_per_product_options',
			),
		);
		return $settings;
	}

	/**
	 * get_per_category_settings.
	 */
	function get_per_category_settings() {

		$settings = array(

			array( 'title' => __( 'Per Category Options', 'woocommerce-jetpack' ), 'type' => 'title', 'desc' => __( 'This sections lets you set Add to Cart button text on per category basis.', 'woocommerce-jetpack' ), 'id' => 'wcj_add_to_cart_per_category_options' ),

			array(
				'title'    => __( 'Per Category Labels', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable Section', 'woocommerce-jetpack' ),
				'desc_tip' => '',
				'id'       => 'wcj_add_to_cart_per_category_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),

			array(
				'title'    => __( 'Category Groups Number', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'Click "Save changes" after you change this number.', 'woocommerce-jetpack' ),
				'id'       => 'wcj_add_to_cart_per_category_total_groups_number',
				'default'  => 1,
				'type'     => 'custom_number',
				'desc'     => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => array_merge(
					is_array( apply_filters( 'booster_get_message', '', 'readonly' ) ) ? apply_filters( 'booster_get_message', '', 'readonly' ) : array(),
					array(
						'step' => '1',
						'min'  => '1',
					)
				),
				'css'      => 'width:100px;',
			),
		);

		$product_cats = array();
		$product_categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ){
			foreach ( $product_categories as $product_category ) {
				$product_cats[ $product_category->term_id ] = $product_category->name;
			}
		}

		for ( $i = 1; $i <= apply_filters( 'booster_get_option', 1, get_option( 'wcj_add_to_cart_per_category_total_groups_number', 1 ) ); $i++ ) {

			/* $deprecated = get_option( 'wcj_add_to_cart_per_category_group_' . $i );
			if ( false !== $deprecated ) {
				if ( '' != $deprecated ) {
					update_option( 'wcj_add_to_cart_per_category_ids_group_' . $i, explode( ',', str_replace( ' ', '', $deprecated ) ) );
				}
				//delete_option( 'wcj_add_to_cart_per_category_group_' . $i );
			} */

			$settings = array_merge( $settings, array(
				array(
					'title'     => __( 'Group', 'woocommerce-jetpack' ) . ' #' . $i,
					'desc'      => __( 'Enable', 'woocommerce-jetpack' ),
					'id'        => 'wcj_add_to_cart_per_category_enabled_group_' . $i,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				/* array(
					'title'     => '',
					'desc'      => __( 'Product Category IDs List', 'woocommerce-jetpack' ),
					'desc_tip'  => __( 'Comma separated list of product category IDs.', 'woocommerce-jetpack' ),
					'id'        => 'wcj_add_to_cart_per_category_group_' . $i,
					'default'   => '',
					'type'      => 'text',
					'css'       => 'width:30%;min-width:300px;',
				), */
				array(
					'title'     => '',
					'desc'      => __( 'categories', 'woocommerce-jetpack' ),
					'desc_tip'  => __( '', 'woocommerce-jetpack' ),
					'id'        => 'wcj_add_to_cart_per_category_ids_group_' . $i,
					'default'   => '',
					'type'      => 'multiselect',
					'class'     => 'chosen_select',
					'css'       => 'width: 450px;',
					'options'   => $product_cats,
				),
				array(
					'title'     => '',
					'desc'      => __( 'Button text - single product view', 'woocommerce-jetpack' ),
					'id'        => 'wcj_add_to_cart_per_category_text_single_group_' . $i,
					'default'   => '',
					'type'      => 'textarea',
					'css'       => 'width:20%;min-width:200px;',
				),
				array(
					'title'     => '',
					'desc'      => __( 'Button text - product archive (category) view', 'woocommerce-jetpack' ),
					'id'        => 'wcj_add_to_cart_per_category_text_archive_group_' . $i,
					'default'   => '',
					'type'      => 'textarea',
					'css'       => 'width:20%;min-width:200px;',
				),
			) );
		}

		$settings = array_merge( $settings, array(

			array( 'type'  => 'sectionend', 'id' => 'wcj_add_to_cart_per_category_options' ),

		) );

		return $settings;
	}

	/**
	 * get_settings.
	 *
	 * @version 2.4.6
	 */
	function get_settings() {
		$settings = array();
		$settings = array_merge( $settings, $this->get_per_category_settings() );
		$settings = array_merge( $settings, $this->get_per_product_settings() );
		$settings = array_merge( $settings, $this->get_per_product_type_settings() );
		return $this->add_standard_settings( $settings );
	}
}

endif;

return new WCJ_Add_To_Cart();
