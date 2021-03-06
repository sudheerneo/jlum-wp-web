<?php
/**
 * WooCommerce Jetpack PDF Invoices Templates
 *
 * The WooCommerce Jetpack PDF Invoices Templates class.
 *
 * @version 2.7.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WCJ_PDF_Invoicing_Templates' ) ) :

class WCJ_PDF_Invoicing_Templates extends WCJ_Module {

	/**
	 * Constructor.
	 *
	 * @version 2.3.7
	 */
	function __construct() {
		$this->id         = 'pdf_invoicing_templates';
		$this->parent_id  = 'pdf_invoicing';
		$this->short_desc = __( 'Templates', 'woocommerce-jetpack' );
		$this->desc       = '';
		parent::__construct( 'submodule' );
	}

	/**
	 * get_settings.
	 *
	 * @version 2.7.0
	 */
	function get_settings() {

		$settings = array();

		$invoice_types = ( 'yes' === get_option( 'wcj_invoicing_hide_disabled_docs_settings', 'no' ) ) ? wcj_get_enabled_invoice_types() : wcj_get_invoice_types();
		foreach ( $invoice_types as $invoice_type ) {

			ob_start();
			if ( false === strpos( $invoice_type['id'], 'custom_doc_' ) ) {
				include( 'defaults/wcj-content-template-' . $invoice_type['id'] . '.php' );
			} else {
				include( 'defaults/wcj-content-template-' . 'custom_doc' . '.php' );
			}
			$default_template = ob_get_clean();

			if ( false !== strpos( $invoice_type['id'], 'custom_doc' ) ) {
				$custom_doc_nr = ( 'custom_doc' === $invoice_type['id'] ) ? '1' : str_replace( 'custom_doc_', '', $invoice_type['id'] );
				$default_template = str_replace( '[wcj_custom_doc_number]', '[wcj_custom_doc_number doc_nr="' . $custom_doc_nr . '"]', $default_template );
				$default_template = str_replace( '[wcj_custom_doc_date]',   '[wcj_custom_doc_date doc_nr="'   . $custom_doc_nr . '"]', $default_template );
			}

			$settings = array_merge( $settings, array(
				array(
					'title'   => strtoupper( $invoice_type['desc'] ),
					'type'    => 'title',
					'id'      => 'wcj_invoicing_' . $invoice_type['id'] . '_templates_options',
				),
				array(
					'title'   => __( 'HTML Template', 'woocommerce-jetpack' ),
					'id'      => 'wcj_invoicing_' . $invoice_type['id'] . '_template',
					'default' => $default_template,
					'type'    => 'textarea',
					'css'     => 'width:66%;min-width:300px;height:500px;',
				),
				array(
					'type'    => 'sectionend',
					'id'      => 'wcj_invoicing_' . $invoice_type['id'] . '_templates_options',
				),
			) );
		}

		$settings = array_merge( $settings, array(
			array(
				'title' => __( 'Available Shortcodes', 'woocommerce-jetpack' ),
				'type'  => 'title',
				'desc'  => sprintf(
					__( 'For the list of available shortcodes, please visit %s.', 'woocommerce-jetpack' ),
					'<a target="_blank" href="http://booster.io/category/shortcodes/?utm_source=shortcodes_list&utm_medium=module_button&utm_campaign=booster_documentation">http://booster.io/category/shortcodes/</a>'
				),
				'id'    => 'wcj_invoicing_templates_desc',
			),
			array(
				'type'  => 'sectionend',
				'id'    => 'wcj_invoicing_templates_desc',
			),
		) );

		return $this->add_standard_settings( $settings );
	}

}

endif;

return new WCJ_PDF_Invoicing_Templates();
