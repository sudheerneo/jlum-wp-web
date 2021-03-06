<?php
/**
 * WooCommerce Jetpack Product Info
 *
 * The WooCommerce Jetpack Product Info class.
 *
 * @version 2.5.3
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCJ_Product_Info' ) ) :

class WCJ_Product_Info extends WCJ_Module {

	/**
	 * search_and_replace_deprecated_shortcodes.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	private function search_and_replace_deprecated_shortcodes( $data ) {
		$search_and_replace_deprecated_shortcodes_array = array(
			'%sku%'                                    => '[wcj_product_sku]',
			'wcj_sku'                                  => 'wcj_product_sku',
			'%title%'                                  => '[wcj_product_title]',
			'wcj_title'                                => 'wcj_product_title',
			'%weight%'                                 => '[wcj_product_weight]',
			'wcj_weight'                               => 'wcj_product_weight',
			'%total_sales%'                            => '[wcj_product_total_sales]',
			'wcj_total_sales'                          => 'wcj_product_total_sales',
			'%shipping_class%'                         => '[wcj_product_shipping_class]',
			'wcj_shipping_class'                       => 'wcj_product_shipping_class',
			'%dimensions%'                             => '[wcj_product_dimensions]',
			'wcj_dimensions'                           => 'wcj_product_dimensions',
			'%formatted_name%'                         => '[wcj_product_formatted_name]',
			'wcj_formatted_name'                       => 'wcj_product_formatted_name',
			'%stock_availability%'                     => '[wcj_product_stock_availability]',
			'wcj_stock_availability'                   => 'wcj_product_stock_availability',
			'%tax_class%'                              => '[wcj_product_tax_class]',
			'wcj_tax_class'                            => 'wcj_product_tax_class',
			'%average_rating%'                         => '[wcj_product_average_rating]',
			'wcj_average_rating'                       => 'wcj_product_average_rating',
			'%categories%'                             => '[wcj_product_categories]',
			'wcj_categories'                           => 'wcj_product_categories',
			'%list_attributes%'                        => '[wcj_product_list_attributes]',
			'wcj_list_attributes'                      => 'wcj_product_list_attributes',
//			'%list_attribute%'                         => '[wcj_product_list_attribute]',
			'wcj_list_attribute options='              => 'wcj_product_list_attribute name=',
			'wcjp_list_attribute attribute_name='      => 'wcj_product_list_attribute name=',
			'%stock_quantity%'                         => '[wcj_product_stock_quantity]',
			'wcj_stock_quantity'                       => 'wcj_product_stock_quantity',
			'%sale_price%'                             => '[wcj_product_sale_price hide_currency="yes"]',
			'wcj_sale_price'                           => 'wcj_product_sale_price hide_currency="yes"',
			'%sale_price_formatted%'                   => '[wcj_product_sale_price]',
			'wcj_sale_price_formatted'                 => 'wcj_product_sale_price',
			'%regular_price%'                          => '[wcj_product_regular_price hide_currency="yes"]',
			'wcj_regular_price'                        => 'wcj_product_regular_price hide_currency="yes"',
			'%regular_price_formatted%'                => '[wcj_product_regular_price]',
			'wcj_regular_price_formatted'              => 'wcj_product_regular_price',
			'%regular_price_if_on_sale%'               => '[wcj_product_regular_price hide_currency="yes" show_always="no"]',
			'wcj_regular_price_if_on_sale'             => 'wcj_product_regular_price hide_currency="yes" show_always="no"',
			'%regular_price_if_on_sale_formatted%'     => '[wcj_product_regular_price show_always="no"]',
			'wcj_regular_price_if_on_sale_formatted'   => 'wcj_product_regular_price show_always="no"',
			'%time_since_last_sale%'                   => '[wcj_product_time_since_last_sale]',
			'wcj_time_since_last_sale'                 => 'wcj_product_time_since_last_sale',
			'%price_including_tax%'                    => '[wcj_product_price_including_tax hide_currency="yes"]',
			'wcj_price_including_tax'                  => 'wcj_product_price_including_tax hide_currency="yes"',
			'%price_including_tax_formatted%'          => '[wcj_product_price_including_tax]',
			'wcj_price_including_tax_formatted'        => 'wcj_product_price_including_tax',
			'%price_excluding_tax%'                    => '[wcj_product_price_excluding_tax hide_currency="yes"]',
			'wcj_price_excluding_tax'                  => 'wcj_product_price_excluding_tax hide_currency="yes"',
			'%price_excluding_tax_formatted%'          => '[wcj_product_price_excluding_tax]',
			'wcj_price_excluding_tax_formatted'        => 'wcj_product_price_excluding_tax',
			'%price%'                                  => '[wcj_product_price hide_currency="yes"]',
			'wcj_price'                                => 'wcj_product_price hide_currency="yes"',
			'%price_formatted%'                        => '[wcj_product_price]',
			'wcj_price_formatted'                      => 'wcj_product_price',
			'%you_save%'                               => '[wcj_product_you_save hide_currency="yes"]',
			'wcj_you_save'                             => 'wcj_product_you_save hide_currency="yes"',
			'%you_save_formatted%'                     => '[wcj_product_you_save]',
			'wcj_you_save_formatted'                   => 'wcj_product_you_save',
			'%you_save_percent%'                       => '[wcj_product_you_save_percent]',
			'wcj_you_save_percent'                     => 'wcj_product_you_save_percent',
			'wcj_available_variations'                 => 'wcj_product_available_variations',
		);
		return str_replace(
			array_keys(   $search_and_replace_deprecated_shortcodes_array ),
			array_values( $search_and_replace_deprecated_shortcodes_array ),
			$data
		);
	}

	/**
	 * Constructor.
	 *
	 * @version 2.5.3
	 */
	function __construct() {

		$this->id         = 'product_info';
		$this->short_desc = __( 'Product Info V1', 'woocommerce-jetpack' );
		$this->desc       = __( 'Add additional info to WooCommerce category and single product pages.', 'woocommerce-jetpack' );
		$this->link       = 'http://booster.io/features/woocommerce-product-info/';
		parent::__construct();

		$this->product_info_on_archive_filters_array = $this->get_product_info_on_archive_filters_array();
		$this->product_info_on_single_filters_array  = $this->get_product_info_on_single_filters_array();

		if ( $this->is_enabled() ) {
			$this->add_product_info_filters( 'archive' );
			$this->add_product_info_filters( 'single' );
		}
	}

	/**
	 * get_product_info_on_archive_filters_array.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	private function get_product_info_on_archive_filters_array() {
		return array(
			'woocommerce_before_shop_loop_item'       => __( 'Before product', 'woocommerce-jetpack' ),
			'woocommerce_before_shop_loop_item_title' => __( 'Before product title', 'woocommerce-jetpack' ),
			'woocommerce_after_shop_loop_item'        => __( 'After product', 'woocommerce-jetpack' ),
			'woocommerce_after_shop_loop_item_title'  => __( 'After product title', 'woocommerce-jetpack' ),
		);
	}

	/**
	 * get_product_info_on_single_filters_array.
	 *
	 * @version 2.4.0
	 * @since   2.4.0
	 */
	private function get_product_info_on_single_filters_array() {
		return array(
			'woocommerce_single_product_summary'        => __( 'Inside single product summary', 'woocommerce-jetpack' ),
			'woocommerce_before_single_product_summary' => __( 'Before single product summary', 'woocommerce-jetpack' ),
			'woocommerce_after_single_product_summary'  => __( 'After single product summary', 'woocommerce-jetpack' ),
		);
	}

	/**
	 * add_product_info_filters.
	 */
	function add_product_info_filters( $single_or_archive ) {
		// Product Info
		if ( ( 'yes' === get_option( 'wcj_product_info_on_' . $single_or_archive . '_enabled' ) ) &&
			 ( '' != get_option( 'wcj_product_info_on_' . $single_or_archive ) ) &&
			 ( '' != get_option( 'wcj_product_info_on_' . $single_or_archive . '_filter' ) ) &&
			 ( '' != get_option( 'wcj_product_info_on_' . $single_or_archive . '_filter_priority' ) ) )
				add_action( get_option( 'wcj_product_info_on_' . $single_or_archive . '_filter' ), array( $this, 'product_info' ), get_option( 'wcj_product_info_on_' . $single_or_archive . '_filter_priority' ) );
		// More product Info
		if ( 'yes' === get_option( 'wcj_more_product_info_on_' . $single_or_archive . '_enabled' ) ) {
				add_action( get_option( 'wcj_more_product_info_on_' . $single_or_archive . '_filter' ), array( $this, 'more_product_info' ), get_option( 'wcj_more_product_info_on_' . $single_or_archive . '_filter_priority' ) );
		}
	}

	/**
	 * product_info.
	 *
	 * @version 2.4.0
	 */
	function product_info() {
		$the_action_name = current_filter();
		if ( array_key_exists( $the_action_name, $this->product_info_on_archive_filters_array ) ) {
			$the_product_info = get_option( 'wcj_product_info_on_archive' );
			$the_product_info = $this->search_and_replace_deprecated_shortcodes( $the_product_info );
			$this->apply_product_info_short_codes( $the_product_info, false );
		}
		else if ( array_key_exists( $the_action_name, $this->product_info_on_single_filters_array ) ) {
			$the_product_info = get_option( 'wcj_product_info_on_single' );
			$the_product_info = $this->search_and_replace_deprecated_shortcodes( $the_product_info );
			$this->apply_product_info_short_codes( $the_product_info, false );
		}
	}

	/**
	 * more_product_info.
	 */
	function more_product_info() {
		$the_action_name = current_filter();
		if ( array_key_exists( $the_action_name, $this->product_info_on_archive_filters_array ) )
			$this->add_more_product_info( 'archive' );
		else if ( array_key_exists( $the_action_name, $this->product_info_on_single_filters_array ) )
			$this->add_more_product_info( 'single' );
	}

	/**
	 * add_more_product_info.
	 *
	 * @version 2.4.0
	 */
	function add_more_product_info( $single_or_archive ) {
		//$single_or_archive = 'archive';
		for ( $i = 1; $i <= apply_filters( 'booster_get_option', 4, get_option( 'wcj_more_product_info_on_' . $single_or_archive . '_fields_total', 4 ) ); $i++ ) {
			$field_id = 'wcj_more_product_info_on_' . $single_or_archive . '_' . $i ;
			$the_product_info = get_option( $field_id );
			$the_product_info = $this->search_and_replace_deprecated_shortcodes( $the_product_info );
			$this->apply_product_info_short_codes( $the_product_info, true );
		}
	}

	/**
	 * apply_product_info_short_codes.
	 *
	 * @version 2.4.0
	 */
	function apply_product_info_short_codes( $the_product_info, $remove_on_empty ) {

		$product_ids_to_exclude = get_option( 'wcj_product_info_products_to_exclude', '' );
		if ( '' != $product_ids_to_exclude ) {
			$product_ids_to_exclude = str_replace( ' ', '', $product_ids_to_exclude );
			$product_ids_to_exclude = explode( ',', $product_ids_to_exclude );
			$product_id = get_the_ID();
			if ( ! empty( $product_ids_to_exclude ) && is_array( $product_ids_to_exclude ) && in_array( $product_id, $product_ids_to_exclude ) ) {
				return;
			}
		}

		if ( '' == $the_product_info ) {
			return;
		}

		/* foreach ( $this->product_info_shortcodes_array as $product_info_short_code ) {
			if ( false !== strpos( $the_product_info, $product_info_short_code ) ) {
				// We found short code in the text
				$replace_with_phrase = $this->get_product_info_short_code( $product_info_short_code );
				if ( false === $replace_with_phrase && true === $remove_on_empty ) {
					// No phrase to replace exists, then empty the text and continue with next field
					$the_product_info = '';
					return;
				}
				else {
					if ( false === $replace_with_phrase ) $replace_with_phrase = '';
					// Replacing the short code
					$the_product_info = str_replace( $product_info_short_code, $replace_with_phrase, $the_product_info );
				}
			}
		} */

		//echo apply_filters( 'the_content', $the_product_info );
		echo do_shortcode( $the_product_info );
	}

	/**
	 * admin_add_product_info_fields_with_header.
	 */
	function admin_add_product_info_fields_with_header( &$settings, $single_or_archive, $title, $filters_array ) {
		$settings = array_merge( $settings, array(
			array(
				'title'    => $title,
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'id'       => 'wcj_more_product_info_on_' . $single_or_archive . '_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => '',
				'desc'     => __( 'Position', 'woocommerce-jetpack' ),
				'id'       => 'wcj_more_product_info_on_' . $single_or_archive . '_filter',
				'css'      => 'min-width:350px;',
				'class'    => 'chosen_select',
				'default'  => 'woocommerce_after_shop_loop_item_title',
				'type'     => 'select',
				'options'  => $filters_array, //$this->product_info_on_archive_filters_array,
				'desc_tip' => true,
			),
			array(
				'title'    => '',
				'desc_tip' => __( 'Priority (i.e. Order)', 'woocommerce-jetpack' ),
				'id'       => 'wcj_more_product_info_on_' . $single_or_archive . '_filter_priority',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'title'    => '',
				'desc_tip' => __( 'Number of product info fields. Click "Save changes" after you change this number.', 'woocommerce-jetpack' ),
				'id'       => 'wcj_more_product_info_on_' . $single_or_archive . '_fields_total',
				'default'  => 4,
				'type'     => 'number',
				'desc'     => apply_filters( 'booster_get_message', '', 'desc' ),
				'custom_attributes' => apply_filters( 'booster_get_message', '', 'readonly' ),
			),
		) );
		$this->admin_add_product_info_fields( $settings, $single_or_archive );
	}

	/**
	 * admin_add_product_info_fields.
	 *
	 * @version 2.4.0
	 */
	function admin_add_product_info_fields( &$settings, $single_or_archive ) {
		for ( $i = 1; $i <= apply_filters( 'booster_get_option', 4, get_option( 'wcj_more_product_info_on_' . $single_or_archive . '_fields_total', 4 ) ); $i++ ) {
			$field_id = 'wcj_more_product_info_on_' . $single_or_archive . '_' . $i ;
			$default_value = '';
			switch ( $i ) {
				case 1: $default_value = '<ul>'; break;
				case 2: $default_value = '<li>' . __( '[wcj_product_you_save before="You save: <strong>" hide_if_zero="yes" after="</strong>"][wcj_product_you_save_percent hide_if_zero="yes" before=" (" after="%)"]', 'woocommerce-jetpack' ) . '</li>'; break;
				case 3: $default_value = '<li>' . __( '[wcj_product_total_sales before="Total sales: "]', 'woocommerce-jetpack' ) . '</li>'; break;
				case 4: $default_value = '</ul>'; break;
			}
//			$desc = ( '' != $default_value ) ? __( 'Default', 'woocommerce-jetpack' ) . ': ' . esc_html( $default_value ) : '';
//			$short_codes_list = '%you_save%, %total_sales%';
//			$desc_tip = __( 'Field Nr. ', 'woocommerce-jetpack' ) . $i . '<br>' . __( 'Available short codes: ', 'woocommerce-jetpack' ) . $short_codes_list;
			$settings[] = array(
				'title'    => '',
//				'desc_tip' => $desc_tip,
//				'desc'     => $desc,
				'id'       => $field_id,
				'default'  => $default_value,
				'type'     => 'textarea',
				'css'      => 'width:50%;min-width:300px;',
			);
		}
	}

	/**
	 * Get settings.
	 *
	 * @version 2.4.0
	 */
	function get_settings() {

		$settings = array(
			array(
				'title'    => __( 'Products Info', 'woocommerce-jetpack' ), 'type' => 'title',
				'desc'     => __( 'For full list of short codes, please visit <a target="_blank" href="http://booster.io/shortcodes/">http://booster.io/shortcodes/</a>.', 'woocommerce-jetpack' ),
				'id'       => 'wcj_more_product_info_options',
			),
		);
		$this->admin_add_product_info_fields_with_header( $settings, 'archive', __( 'Product Info on Archive Pages', 'woocommerce-jetpack' ), $this->product_info_on_archive_filters_array );
		$this->admin_add_product_info_fields_with_header( $settings, 'single',  __( 'Product Info on Single Pages', 'woocommerce-jetpack' ),  $this->product_info_on_single_filters_array );
		$settings[] = array(
				'type'     => 'sectionend',
				'id'       => 'wcj_more_product_info_options',
		);

		$settings = array_merge( $settings, array(
			array(
				'title'    => __( 'Even More Products Info', 'woocommerce-jetpack' ),
				'type'     => 'title',
				'id'       => 'wcj_product_info_additional_options',
			),
			array(
				'title'    => __( 'Product Info on Archive Pages', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_on_archive_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => '',
				'desc_tip' => __( 'HTML info.', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_on_archive',
				'default'  => __( '[wcj_product_sku before="SKU: "]', 'woocommerce-jetpack' ),
				'type'     => 'textarea',
				'css'      => 'width:50%;min-width:300px;height:100px;',
			),
			array(
				'title'    => '',
				'desc'     => __( 'Position', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_on_archive_filter',
				'css'      => 'min-width:350px;',
				'class'    => 'chosen_select',
				'default'  => 'woocommerce_after_shop_loop_item_title',
				'type'     => 'select',
				'options'  => $this->product_info_on_archive_filters_array,
				'desc_tip' => true,
			),
			array(
				'title'    => '',
				'desc_tip' => __( 'Priority (i.e. Order)', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_on_archive_filter_priority',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'title'    => __( 'Product Info on Single Product Pages', 'woocommerce-jetpack' ),
				'desc'     => __( 'Enable', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_on_single_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => '',
				'desc_tip' => __( 'HTML info.', 'woocommerce-jetpack' ),// . ' ' . $this->list_short_codes(),
				'id'       => 'wcj_product_info_on_single',
				'default'  => __( 'Total sales: [wcj_product_total_sales]', 'woocommerce-jetpack' ),
				'type'     => 'textarea',
				'css'      => 'width:50%;min-width:300px;height:100px;',
			),
			array(
				'title'    => '',
				'desc'     => __( 'Position', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_on_single_filter',
				'css'      => 'min-width:350px;',
				'class'    => 'chosen_select',
				'default'  => 'woocommerce_after_single_product_summary',
				'type'     => 'select',
				'options'  => $this->product_info_on_single_filters_array,
				'desc_tip' => true,
			),
			array(
				'title'    => '',
				'desc_tip' => __( 'Priority (i.e. Order)', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_on_single_filter_priority',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'title'    => __( 'Product IDs to exclude', 'woocommerce-jetpack' ),
				'desc_tip' => __( 'Comma separated list of product IDs to exclude from product info.', 'woocommerce-jetpack' ),
				'id'       => 'wcj_product_info_products_to_exclude',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wcj_product_info_additional_options',
			),
		) );

		return $this->add_standard_settings( $settings );
	}
}

endif;

return new WCJ_Product_Info();
