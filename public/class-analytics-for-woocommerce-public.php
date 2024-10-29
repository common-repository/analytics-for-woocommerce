<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/public
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Analytics_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Adds global gtag.
	 *
	 * @since    1.0.0
	 */
	public function mwb_anawoo_add_gtag() {

		$setting_data = unserialize( get_option( 'mwb_anawoo_settings' ) );

		$excluded_user_roles = array();

		$setting_data = unserialize( get_option( 'mwb_anawoo_settings' ) );

		if ( isset( $setting_data['mwb_anawoo_excluded_roles'] ) ) {

			$excluded_user_roles = $setting_data['mwb_anawoo_excluded_roles'];

		}

		$current_user_roles = array();

		$track = true;

		if ( is_user_logged_in() ) {

			$current_user = wp_get_current_user();

			$current_user_roles = $current_user->roles;

			if ( ! empty( $current_user_roles ) && ! empty( $excluded_user_roles ) ) {

				foreach ( $excluded_user_roles as $key => $role ) {

					if ( in_array( $role, $current_user_roles ) ) {

						$track = false;

						break;

					}
				}
			}
		}

		$gtag_id = isset( $setting_data['mwb_anawoo_analytics_id'] ) ? $setting_data['mwb_anawoo_analytics_id'] : '';

		$mwb_anawoo_ip_anoymization = isset( $setting_data['mwb_anawoo_ip_anoymization'] ) ? $setting_data['mwb_anawoo_ip_anoymization'] : '';

		if ( ! empty( $mwb_anawoo_ip_anoymization ) ) {

			$ip_anonymization = '"anonymize_ip":true,';

		} else {

			$ip_anonymization = '';

		}

		$domain_name = 'auto';

		$opt_out = isset( $setting_data['mwb_anawoo_opt_out'] ) ? $setting_data['mwb_anawoo_opt_out'] : '';

		if ( ! empty( $opt_out ) && $track ) {

			echo '<script>

			var gaProperty = "' . esc_js( $gtag_id ) . '";

			var disableTracking = "ga-disable-" + gaProperty;

			if (document.cookie.indexOf(disableTracking + "=true") > -1) {

				window[disableTracking] = true;

			}

			function mwb_opt_out() {

				var expDate = new Date;

				expDate.setMonth(expDate.getMonth() + 26);

				document.cookie = disableTracking + "=true; expires="+expDate.toGMTString()+";path=/";

				window[disableTracking] = true;

			}

			</script>';

		}

		if ( ! empty( $gtag_id ) && $track ) {

			echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_js( $gtag_id ) . '"></script>

			<script>

			window.dataLayer = window.dataLayer || [];

			function gtag(){dataLayer.push(arguments);}

			gtag("js", new Date());

			gtag("config", "' . esc_js( $gtag_id ) . '",{' . esc_js( $ip_anonymization ) . ' "cookie_domain":"' . esc_js( $domain_name ) . '"});

			</script>';

		}

	}
	/**
	 * Adds tracking scripts .
	 *
	 * @since    1.0.0
	 */
	public function mwb_anawoo_add_footer_scripts() {

		$currency = get_woocommerce_currency();

		$js_data = array(
			'page' => '',
			'currency' => $currency,
		);

		$excluded_user_roles = array();

		$setting_data = unserialize( get_option( 'mwb_anawoo_settings' ) );

		if ( isset( $setting_data['mwb_anawoo_excluded_roles'] ) ) {

			$excluded_user_roles = $setting_data['mwb_anawoo_excluded_roles'];

		}

		$current_user_roles = array();

		$track = true;

		if ( is_user_logged_in() ) {

			$current_user = wp_get_current_user();

			$current_user_roles = $current_user->roles;

			if ( ! empty( $current_user_roles ) && ! empty( $excluded_user_roles ) ) {

				foreach ( $excluded_user_roles as $key => $role ) {

					if ( in_array( $role, $current_user_roles ) ) {

						$track = false;

						break;

					}
				}
			}
		}

		if ( $track ) {

			if ( is_product() ) {

				global $product,$woocommerce;

				$js_data['page'] = 'single_product';

				$category = get_the_terms( $product->get_id(), 'product_cat' );

				$currency = get_woocommerce_currency();

				$categories = '';

				if ( $category ) {

					foreach ( $category as $term ) {

						$categories .= $term->name . ',';

					}
				}

				$categories = rtrim( $categories, ',' );

				$js_items = array();

				$js_items[] = array(

					'id' => esc_js( $product->get_id() ),

					'name' => $product->get_title(),

					'price' => esc_js( $product->get_price() ),

					'category' => $categories,

					'quantity' => '1',

				);

				$js_data['js_items'] = $js_items;

				$related_items = wc_get_related_products( $product->get_id() );

				$js_data['related_items'] = 'no';

				if ( ! empty( $related_items ) ) {

					$js_data['related_items'] = 'yes';

					$js_related_items = array();

					$js_imp_related_items = array();

					foreach ( $related_items as $key => $related_item ) {

						$rp_product = wc_get_product( $related_item );

						$js_related_items[ $rp_product->get_id() ] = array(

							'id' => esc_js( $rp_product->get_id() ),

							'name' => $rp_product->get_title(),

							'price' => esc_js( $rp_product->get_price() ),

							'category' => $categories,

							'quantity' => '1',

						);

						$js_imp_related_items[ get_post_permalink( $rp_product->get_id() ) ] = array(

							'id' => esc_js( $rp_product->get_id() ),

							'name' => $rp_product->get_title(),

							'price' => esc_js( $rp_product->get_price() ),

							'category' => $categories,

							'quantity' => '1',

						);

					}

					$js_data['js_related_items'] = $js_related_items;

					$js_data['js_imp_related_items'] = $js_imp_related_items;

				}

				$upsell_items = $product->get_upsell_ids();

				$js_data['upsell_items'] = 'no';

				if ( ! empty( $upsell_items ) ) {

					$js_data['upsell_items'] = 'yes';

					$js_upsell_items = array();

					$js_imp_upsell_items = array();

					foreach ( $upsell_items as $key => $up_item ) {

						$rp_product = wc_get_product( $up_item );

						$js_upsell_items[ $rp_product->get_id() ] = array(

							'id' => esc_js( $rp_product->get_id() ),

							'name' => $rp_product->get_title(),

							'price' => esc_js( $rp_product->get_price() ),

							'category' => $categories,

							'quantity' => '1',

						);

						$js_imp_upsell_items[ get_post_permalink( $rp_product->get_id() ) ] = array(

							'id' => esc_js( $rp_product->get_id() ),

							'name' => $rp_product->get_title(),

							'price' => esc_js( $rp_product->get_price() ),

							'category' => $categories,

							'quantity' => '1',

						);

					}

					$js_data['js_upsell_items'] = $js_upsell_items;

					$js_data['js_imp_upsell_items'] = $js_imp_upsell_items;

				}
			}

			if ( is_shop() || is_tax( 'product_cat' ) ) {

				if ( is_shop() ) {

					$js_data['page'] = 'shop';

				}

				if ( is_tax( 'product_cat' ) ) {

					$js_data['page'] = 'category';

				}

				$js_items = array();

				$js_items_imp = array();

				if ( wc()->query->get_main_query()->post_count > 0 ) {

					foreach ( wc()->query->get_main_query()->posts as $key => $post ) {

						$product = wc_get_product( $post->ID );

						$category = get_the_terms( $post->ID, 'product_cat' );

						$categories = '';

						if ( $category ) {

							foreach ( $category as $term ) {

								$categories .= $term->name . ',';

							}
						}

						$categories = rtrim( $categories, ',' );

						$item_array = array(

							'id' => esc_js( $post->ID ),

							'name' => $product->get_title(),

							'price' => esc_js( $product->get_price() ),

							'category' => $categories,

							'quantity' => '1',

						);

						$js_items[ $post->ID ] = $item_array;

						$js_items_imp[ get_post_permalink( $post->ID ) ] = $item_array;

					}
				}

				$js_data['js_items_imp'] = $js_items_imp;

				$js_data['js_items'] = $js_items;

			}

			if ( is_cart() ) {

				$js_data['page'] = 'cart';

				$cart_items = WC()->cart->get_cart();

				$js_items = array();

				if ( ! empty( $cart_items ) ) {

					foreach ( $cart_items as $key => $item ) {

						$product = wc_get_product( $item['product_id'] );

						$category = get_the_terms( $product->get_id(), 'product_cat' );

						$categories = '';

						if ( $category ) {

							foreach ( $category as $term ) {

								$categories .= $term->name . ',';

							}
						}

						$categories = rtrim( $categories, ',' );

						$js_items[ $item['product_id'] ] = array(

							'id' => esc_js( $item['product_id'] ),

							'name' => $product->get_title(),

							'price' => esc_js( $product->get_price() ),

							'category' => $categories,

							'quantity' => $item['quantity'],

						);

					}

					$cross_sell_items = WC()->cart->get_cross_sells();

					$js_data['cross_sell_items'] = 'no';

					if ( ! empty( $cross_sell_items ) ) {

						$js_data['cross_sell_items'] = 'yes';

						$js_cross_sell_items = array();

						$js_imp_cross_sell_items = array();

						foreach ( $cross_sell_items as $key => $cs_item ) {

							$rp_product = wc_get_product( $cs_item );

							$js_cross_sell_items[ $rp_product->get_id() ] = array(

								'id' => esc_js( $rp_product->get_id() ),

								'name' => $rp_product->get_title(),

								'price' => esc_js( $rp_product->get_price() ),

								'category' => $categories,

								'quantity' => '1',

							);

							$js_imp_cross_sell_items[ get_post_permalink( $rp_product->get_id() ) ] = array(

								'id' => esc_js( $rp_product->get_id() ),

								'name' => $rp_product->get_title(),

								'price' => esc_js( $rp_product->get_price() ),

								'category' => $categories,

								'quantity' => '1',

							);

						}

						$js_data['js_cross_sell_items'] = $js_cross_sell_items;

						$js_data['js_imp_cross_sell_items'] = $js_imp_cross_sell_items;

					}
				}

				$js_data['js_items'] = $js_items;

			}

			if ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {

				$js_data['page'] = 'checkout';

				$cart_items = WC()->cart->get_cart();

				$js_items = array();

				if ( ! empty( $cart_items ) ) {

					foreach ( $cart_items as $key => $item ) {

						$product = wc_get_product( $item['product_id'] );

						$category = get_the_terms( $product->get_id(), 'product_cat' );

						$categories = '';

						if ( $category ) {

							foreach ( $category as $term ) {

								$categories .= $term->name . ',';

							}
						}

						$categories = rtrim( $categories, ',' );

						$js_items[] = array(

							'id' => esc_js( $item['product_id'] ),

							'name' => $product->get_title(),

							'price' => esc_js( $product->get_price() ),

							'category' => $categories,

							'quantity' => $item['quantity'],

						);

					}
				}

				$coupons_list = '';

				$applied_coupons = WC()->cart->get_applied_coupons();

				if ( ! empty( $applied_coupons ) ) {

					$coupons_count = count( $applied_coupons );

					$i = 1;

					foreach ( $applied_coupons as $coupon ) {

						$coupons_list .= $coupon;

						if ( $i < $coupons_count ) {

							$coupons_list .= ', ';
						}

						$i++;

					}
				}

				$js_data['js_items'] = $js_items;

				$js_data['coupon'] = $coupons_list;

			}

			if ( is_wc_endpoint_url( 'order-received' ) ) {

				global $mwb_order_id;

				$js_data['page'] = 'thankyou';

				$order = wc_get_order( $mwb_order_id );

				$coupon_used = $order->get_used_coupons();

				$coupons_count = count( $coupon_used );

				$final_coupon_list = '';

				$i = 1;

				if ( ! empty( $coupon_used ) ) {

					foreach ( $coupon_used as $coupon ) {

						$final_coupon_list .= $coupon;

						if ( $i < $coupons_count ) {

							$final_coupon_list .= ', ';
						}

						$i++;

					}
				}

				if ( get_post_meta( $order->get_id(), 'mwb_anawoo_order_captured', true ) != 'yes' ) {

					$currency = get_woocommerce_currency();

					$transaction_id = $order->get_transaction_id();

					if ( empty( $currency ) ) {

						$currency = 'USD';

					}

					$order_items = $order->get_items();

					$js_items = array();

					if ( ! empty( $order_items ) ) {

						foreach ( $order_items as $key => $item ) {

							if ( $item->is_type( 'line_item' ) ) {

								$product = $item->get_product();

								$categories = get_the_terms( $product->get_id(), 'product_cat' );

								$category_arr = array();

								if ( ! empty( $categories ) ) {

									foreach ( $categories as $category ) {

										$category_arr[] = $category->name;

									}
								}

								$categories = join( ',', $category_arr );

								$js_item = array(

									'id' => esc_js( $item->get_product_id() ),

									'name' => $item->get_name(),

									'quantity' => esc_js( $item->get_quantity() ),

									'price' => esc_js( $product->get_price() ),

									'category' => $categories,

								);

								$js_items[] = $js_item;

							}
						}
					}

					$transaction_id = $order->get_transaction_id();

					if ( '' == $transaction_id ) {

						$transaction_id = $order->get_order_number();

					}

					$currency = get_woocommerce_currency();

					$js_code = array(

						'transaction_id' => esc_js( $transaction_id ),

						'value' => esc_js( $order->get_total() ),

						'affiliation' => esc_js( get_bloginfo( 'name' ) ),

						'tax' => $order->get_total_tax(),

						'shipping' => esc_js( $order->get_shipping_total() ),

						'coupon' => $final_coupon_list,

						'non_interaction' => true,

						'event_category' => 'MWB Enhanced-Ecommerce',

						'event_label' => 'mwb_order_confirmation',

						'items' => $js_items,

					);

					$js_data['js_items'] = $js_code;

					$js_data['coupon'] = $final_coupon_list;

					$js_data['currency'] = $currency;

					update_post_meta( $order->get_id(), 'mwb_anawoo_order_captured', 'yes' );

				}
			}

			if ( wc_post_content_has_shortcode( 'featured_products' ) ) {

				$js_data['page'] = 'featured';

				$data_store           = WC_Data_Store::load( 'product' );

				$featured             = $data_store->get_featured_product_ids();

				$product_ids          = array_keys( $featured );

				$parent_ids           = array_values( array_filter( $featured ) );

				$featured_product_ids = array_unique( array_merge( $product_ids, $parent_ids ) );

				$js_items = array();

				$js_items_imp = array();

				foreach ( $featured_product_ids as $key => $value ) {

					$product = wc_get_product( $value );

					$category = get_the_terms( $value, 'product_cat' );

					$categories = '';

					if ( $category ) {

						foreach ( $category as $term ) {

							$categories .= $term->name . ',';

						}
					}

					$categories = rtrim( $categories, ',' );

					$item_array = array(

						'id' => esc_js( $value ),

						'name' => $product->get_title(),

						'price' => esc_js( $product->get_price() ),

						'category' => $categories,

						'quantity' => '1',

					);

					$js_items[ $value ] = $item_array;

					$js_items_imp[ get_post_permalink( $value ) ] = $item_array;

				}

				$js_data['js_items_imp'] = $js_items_imp;

				$js_data['js_items'] = $js_items;

			}
		}

		wp_register_script( 'analytics-for-woocommerce-footer-script', plugin_dir_url( __FILE__ ) . 'js/analytics-for-woocommerce-footer-script.min.js', array( 'jquery' ), $this->version, true );

		$js_data['ajax_url'] = admin_url( 'admin-ajax.php' );

		wp_localize_script( 'analytics-for-woocommerce-footer-script', 'js_data', $js_data );

		wp_enqueue_script( 'analytics-for-woocommerce-footer-script' );

		$hide = false;

		$setting_data = unserialize( get_option( 'mwb_anawoo_settings' ) );

		$check_opt_out_cookie = 'ga-disable-' . $setting_data['mwb_anawoo_analytics_id'];

		if ( isset( $_COOKIE[ $check_opt_out_cookie ] ) && 'true' == $_COOKIE[ $check_opt_out_cookie ] ) {

			$hide = true;

		}

		$footer_text = 'Opt Out of Google Analytics Tracking ?';

		if ( isset( $setting_data['mwb_anawoo_opt_out'] ) && 'on' == ( $setting_data['mwb_anawoo_opt_out'] && ! $hide ) ) {

			echo '<a href="javascript:mwb_opt_out()">Click here to opt-out of Google Analytics</a>';

		}

	}

	/**
	 * Set global order id.
	 *
	 * @param string $order_id   This parameter contain the order_id.
	 * @since    1.0.0
	 */
	public function mwb_anawoo_thankyou_tracking( $order_id ) {

		$GLOBALS['mwb_order_id'] = $order_id;

		remove_action( 'wp_footer', array( $this, 'mwb_anawoo_add_gtag' ) );

	}

}
