<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/admin
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Analytics_For_Woocommerce_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->mwb_anawoo_admin_actions();

	}

	/**
	 * All Admin Actions
	 *
	 * @since 1.0.0
	 */
	public function mwb_anawoo_admin_actions() {

		add_action( 'admin_menu', array( $this, 'add_mwb_anawoo_submenu' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$screen = get_current_screen();

		if ( isset( $screen->id ) && 'woocommerce_page_mwb_anawoo' == $screen->id ) {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/analytics-for-woocommerce-admin.min.css', array(), $this->version, 'all' );

			wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );

			wp_enqueue_style( 'woocommerce_admin_styles' );

		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();

		if ( isset( $screen->id ) && 'shop_order' == $screen->id ) {

			$setting_data = unserialize( get_option( 'mwb_anawoo_settings' ) );

			if ( isset( $setting_data['mwb_anawoo_enable_gtag'] ) && 'on' == $setting_data['mwb_anawoo_enable_gtag'] ) {

				$gtag_id = $setting_data['mwb_anawoo_analytics_id'];

				echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_js( $gtag_id ) . '"></script>

				<script>

				window.dataLayer = window.dataLayer || [];

				function gtag(){dataLayer.push(arguments);}

				gtag("js", new Date());

				gtag("config", "' . esc_js( $gtag_id ) . '");

				</script>';

			}

			if ( 'null' != get_option( 'mwb_anawoo_order_refunded', 'null' ) ) {

				$order_id = get_option( 'mwb_anawoo_order_refunded' );

				$order = wc_get_order( $order_id );

				$transaction_id = $order->get_transaction_id();

				if ( empty( $transaction_id ) ) {

					$transaction_id = $order->get_order_number();

				}

				$currency = get_woocommerce_currency();

				$js_code = array(

					'transaction_id' => esc_js( $transaction_id ),

					'affiliation' => esc_js( get_bloginfo( 'name' ) ),

					'non_interaction' => true,

					'event_category' => 'MWB Enhanced-Ecommerce',

					'event_label' => 'mwb_order_refunded',

				);

				$js_code = json_encode( $js_code );

				$code = 'gtag("set", {"currency": "' . $currency . '"}) ; ';

				$code .= 'gtag("event", "refund", ' . $js_code . ') ; ';

				wc_enqueue_js( $code );

				update_option( 'mwb_anawoo_order_refunded', 'null' );

			}
		}

		if ( isset( $screen->id ) && 'woocommerce_page_mwb_anawoo' == $screen->id ) {

			wp_enqueue_script( 'wc-enhanced-select' );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/analytics-for-woocommerce-admin.min.js', array( 'jquery', 'wc-enhanced-select' ), $this->version, false );

			wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'wc-enhanced-select' ), WC_VERSION );

			$locale  = localeconv();

			$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';

			$params = array(
				/* translators: %s: search term */
				'i18n_decimal_error'                => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'anawoo' ), $decimal ),
				/* translators: %s: search term */
				'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'anawoo' ), wc_get_price_decimal_separator() ),

				'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', 'anawoo' ),

				'i18_sale_less_than_regular_error'  => __( 'Please enter in a value less than the regular price.', 'anawoo' ),

				'decimal_point'                     => $decimal,

				'mon_decimal_point'                 => wc_get_price_decimal_separator(),

				'strings' => array(

					'import_products' => __( 'Import', 'anawoo' ),

					'export_products' => __( 'Export', 'anawoo' ),

				),

				'urls' => array(

					'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),

					'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),

				),

			);

			wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );

			wp_enqueue_script( 'woocommerce_admin' );

		}

	}

	/**
	 * Add Submenu in WooCommerce top menu
	 *
	 * @since 1.0.0
	 */
	public function add_mwb_anawoo_submenu() {

		add_submenu_page( 'woocommerce', __( 'Analytics For WooCommerce', 'anawoo' ), __( ' Analytics', 'anawoo' ), 'manage_woocommerce', 'mwb_anawoo', array( $this, 'mwb_anawoo_configurations' ) );

	}

	/**
	 * Menu configuration
	 *
	 * @since 1.0.0
	 */
	public function mwb_anawoo_configurations() {

		include_once MWB_ANAWOO_ABSPATH . 'admin/partials/analytics-for-woocommerce-admin-display.php';

	}

	/**
	 * Update option when an order is refunded
	 *
	 * @param string $order_id   This parameter contain the order_id.

	 * @since 1.0.0
	 */
	public function mwb_anawoo_woocommerce_order_status_refunded( $order_id ) {

		update_option( 'mwb_anawoo_order_refunded', $order_id );

	}

	/**
	 * Get all active user roles
	 *
	 * @since 1.0.0
	 */
	public static function mwb_anawoo_get_all_user_roles() {

		if ( ! function_exists( 'get_editable_roles' ) ) {

			require_once ABSPATH . 'wp-admin/includes/user.php';

		}

		global $wp_roles;

		$exiting_user_roles = array();

		$user_roles = ! empty( $wp_roles->role_names ) ? $wp_roles->role_names : array();

		if ( is_array( $user_roles ) && count( $user_roles ) ) {

			foreach ( $user_roles as $role => $role_info ) {

				$role_label = ! empty( $role_info ) ? $role_info : $role;

				$exiting_user_roles[ $role ] = $role_label;

			}
		}

		return $exiting_user_roles;

	}

}
