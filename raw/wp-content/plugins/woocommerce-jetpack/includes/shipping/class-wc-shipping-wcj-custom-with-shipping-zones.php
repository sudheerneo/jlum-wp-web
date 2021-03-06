<?php
/**
 * WooCommerce Jetpack Custom Shipping with Shipping Zones
 *
 * The WooCommerce Jetpack Custom Shipping with Shipping Zones class.
 *
 * @version 2.7.0
 * @since   2.5.6
 * @author  Algoritmika Ltd.
 */

add_action( 'init', 'init_wc_shipping_wcj_custom_w_zones_class' );

if ( ! function_exists( 'init_wc_shipping_wcj_custom_w_zones_class' ) ) {

	function init_wc_shipping_wcj_custom_w_zones_class() {

		if ( class_exists( 'WC_Shipping_Method' ) ) {

			/*
			 * WC_Shipping_WCJ_Custom_W_Zones class.
			 *
			 * @version 2.6.0
			 * @since   2.5.6
			 */
			class WC_Shipping_WCJ_Custom_W_Zones extends WC_Shipping_Method {

				/**
				 * Constructor shipping class
				 *
				 * @version 2.5.6
				 * @since   2.5.6
				 * @access  public
				 * @return  void
				 */
				function __construct( $instance_id = 0 ) {
					$this->init( $instance_id );
				}

				/**
				 * Init settings
				 *
				 * @version 2.6.0
				 * @since   2.5.6
				 * @access  public
				 * @return  void
				 */
				function init( $instance_id ) {

					$this->id                 = 'booster_custom_shipping_w_zones';
					$this->method_title       = get_option( 'wcj_shipping_custom_shipping_w_zones_admin_title', __( 'Booster: Custom Shipping', 'woocommerce-jetpack' ) );
					$this->method_description = __( 'Booster: Custom Shipping Method', 'woocommerce-jetpack' );

					$this->instance_id = absint( $instance_id );
					$this->supports    = array(
						'shipping-zones',
						'instance-settings',
						'instance-settings-modal',
					);

					// Load the settings.
					$this->init_instance_form_fields();
//					$this->init_settings();

					// Define user set variables
					$this->title      = $this->get_option( 'title' );
					$this->cost       = $this->get_option( 'cost' );
					$this->min_weight = $this->get_option( 'min_weight' );
					$this->max_weight = $this->get_option( 'max_weight' );
					$this->type       = $this->get_option( 'type' );
					$this->weight_table_total_rows = $this->get_option( 'weight_table_total_rows' );
					/* for ( $i = 1; $i <= $this->weight_table_total_rows; $i++ ) {
						$option_name = 'weight_table_weight_row_' . $i;
						$this->{$option_name} = $this->get_option( $option_name );
						$option_name = 'weight_table_cost_row_' . $i;
						$this->{$option_name} = $this->get_option( $option_name );
					} */

					// Save settings in admin
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

					// Add weight table rows
					add_filter( 'woocommerce_shipping_instance_form_fields_' . $this->id, array( $this, 'add_weight_table_rows' ) );
				}

				/**
				 * add_weight_table_rows.
				 *
				 * @version 2.6.0
				 * @since   2.6.0
				 */
				function add_weight_table_rows( $instance_form_fields ) {
					if ( $this->instance_id ) {
						$settings = get_option( 'woocommerce_' . $this->id . '_' . $this->instance_id . '_settings' );
						$this->weight_table_total_rows = $settings['weight_table_total_rows'];
						for ( $i = 1; $i <= $this->weight_table_total_rows; $i++ ) {
							if ( ! isset( $instance_form_fields[ 'weight_table_weight_row_' . $i ] ) ) {
								$instance_form_fields = array_merge( $instance_form_fields, array(
									'weight_table_weight_row_' . $i => array(
										'title'       => __( 'Max Weight', 'woocommerce' ) . ' #' . $i,
										'type'        => 'number',
										'default'     => 0,
										'desc_tip'    => true,
										'custom_attributes' => array( 'step' => '0.000001', 'min'  => '0', ),
									),
									'weight_table_cost_row_' . $i => array(
										'title'       => __( 'Cost', 'woocommerce' ) . ' #' . $i,
										'type'        => 'number',
										'default'     => 0,
										'desc_tip'    => true,
										'custom_attributes' => array( 'step' => '0.000001', 'min'  => '0', ),
									),
								) );
							}
						}
					}
					return $instance_form_fields;
				}

				/**
				 * Is this method available?
				 *
				 * @version 2.5.7
				 * @since   2.5.7
				 * @param   array $package
				 * @return  bool
				 */
				public function is_available( $package ) {
					$available = parent::is_available( $package );
					if ( $available ) {
						$total_weight = WC()->cart->get_cart_contents_weight();
						if ( 0 != $this->min_weight && $total_weight < $this->min_weight ) {
							$available = false;
						} elseif ( 0 != $this->max_weight && $total_weight > $this->max_weight ) {
							$available = false;
						}
					}
					return $available;
				}

				/**
				 * Initialise Settings Form Fields
				 *
				 * @version 2.6.0
				 * @since   2.5.6
				 */
				function init_instance_form_fields() {
					$this->instance_form_fields = array(
						'title' => array(
							'title'       => __( 'Title', 'woocommerce' ),
							'type'        => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
							'default'     => __( 'Custom Shipping', 'woocommerce-jetpack' ),
							'desc_tip'    => true,
						),
						'type' => array(
							'title'       => __( 'Type', 'woocommerce' ),
							'type'        => 'select',
							'description' => __( 'Cost calculation type.', 'woocommerce-jetpack' ),
							'default'     => 'flat_rate',
							'desc_tip'    => true,
							'options'     => array(
								'flat_rate'                  => __( 'Flat Rate', 'woocommerce-jetpack' ),
								'by_total_cart_weight'       => __( 'By Total Cart Weight', 'woocommerce-jetpack' ),
								'by_total_cart_weight_table' => __( 'By Total Cart Weight Table', 'woocommerce-jetpack' ),
								'by_total_cart_quantity'     => __( 'By Total Cart Quantity', 'woocommerce-jetpack' ),
							),
						),
						'cost' => array(
							'title'       => __( 'Cost', 'woocommerce' ),
							'type'        => 'number',
							'description' => __( 'Cost. If calculating by weight - then cost per one weight unit. If calculating by quantity - then cost per one piece.', 'woocommerce-jetpack' ),
							'default'     => 0,
							'desc_tip'    => true,
							'custom_attributes' => array( 'step' => '0.000001', 'min'  => '0', ),
						),
						'min_weight' => array(
							'title'       => __( 'Min Weight', 'woocommerce' ),
							'type'        => 'number',
							'description' => __( 'Minimum total cart weight. Set zero to disable.', 'woocommerce-jetpack' ),
							'default'     => 0,
							'desc_tip'    => true,
							'custom_attributes' => array( 'step' => '0.000001', 'min'  => '0', ),
						),
						'max_weight' => array(
							'title'       => __( 'Max Weight', 'woocommerce' ),
							'type'        => 'number',
							'description' => __( 'Maximum total cart weight. Set zero to disable.', 'woocommerce-jetpack' ),
							'default'     => 0,
							'desc_tip'    => true,
							'custom_attributes' => array( 'step' => '0.000001', 'min'  => '0', ),
						),
						'weight_table_total_rows' => array(
							'title'       => __( 'Weight Table Total Rows', 'woocommerce' ),
							'type'        => 'number',
							'description' => __( 'Press "Save changes" and reload the page after you change this number.', 'woocommerce-jetpack' ),
							'default'     => 0,
							'desc_tip'    => true,
							'custom_attributes' => array( 'min'  => '0', ),
						),
					);
					/* for ( $i = 1; $i <= $this->get_option( 'weight_table_total_rows' ); $i++ ) {
						$this->instance_form_fields = array_merge( $this->instance_form_fields, array(
							'weight_table_weight_row_' . $i => array(
								'title'       => __( 'Max Weight', 'woocommerce' ) . ' #' . $i,
								'type'        => 'number',
								'default'     => 0,
								'desc_tip'    => true,
								'custom_attributes' => array( 'step' => '0.000001', 'min'  => '0', ),
							),
							'weight_table_cost_row_' . $i => array(
								'title'       => __( 'Cost', 'woocommerce' ) . ' #' . $i,
								'type'        => 'number',
								'default'     => 0,
								'desc_tip'    => true,
								'custom_attributes' => array( 'step' => '0.000001', 'min'  => '0', ),
							),
						) );
					} */
				}

				/**
				 * calculate_shipping_by_weight_table.
				 *
				 * @version 2.6.0
				 * @since   2.5.6
				 */
				function calculate_shipping_by_weight_table( $weight ) {
					if ( 0 == $this->weight_table_total_rows ) {
						return $this->cost * $weight; // fallback
					}
					$option_name_weight = $option_name_cost = '';
					for ( $i = 1; $i <= $this->weight_table_total_rows; $i++ ) {
						$option_name_weight = 'weight_table_weight_row_' . $i;
						$option_name_cost = 'weight_table_cost_row_' . $i;
						if ( $weight <= $this->get_option( $option_name_weight ) ) {
							return $this->get_option( $option_name_cost );
						}
					}
					return $this->get_option( $option_name_cost ); // fallback - last row
				}

				/**
				 * calculate_shipping function.
				 *
				 * @version 2.6.0
				 * @since   2.5.6
				 * @access  public
				 * @param   mixed $package
				 * @return  void
				 */
				function calculate_shipping( $package = array() ) {
					switch ( $this->type ) {
						case 'by_total_cart_quantity':
							$cart_quantity = 0;
							foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
								$cart_quantity += $values['quantity'];
							}
							$cost = $this->cost * $cart_quantity;
							break;
						case 'by_total_cart_weight':
							$cost = $this->cost * WC()->cart->get_cart_contents_weight();
							break;
						case 'by_total_cart_weight_table':
							$cost = $this->calculate_shipping_by_weight_table( WC()->cart->get_cart_contents_weight() );
							break;
						default: // 'flat_rate'
							$cost = $this->cost;
							break;
					}
					$rate = array(
						'id'       => $this->get_rate_id(),
						'label'    => $this->title,
						'cost'     => $cost,
						'calc_tax' => 'per_order',
					);
					// Register the rate
					$this->add_rate( $rate );
				}
			}

			/*
			 * add_wc_shipping_wcj_custom_w_zones_class.
			 *
			 * @version 2.5.6
			 * @since   2.5.6
			 */
			function add_wc_shipping_wcj_custom_w_zones_class( $methods ) {
				$methods[ 'booster_custom_shipping_w_zones' ] = 'WC_Shipping_WCJ_Custom_W_Zones';
				return $methods;
			}
			add_filter( 'woocommerce_shipping_methods', 'add_wc_shipping_wcj_custom_w_zones_class' );
		}
	}
}
