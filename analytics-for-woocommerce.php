<?php
/**
 * Analytic for woocommerce is very powerfull plugin which implements <strong>Enhanced E-commerce Tracking Scripts</strong> in your woocommerce
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           analytics-for-woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Analytics for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/analytics-for-woocommerce/
 * Requires at least: 4.0
 * Tested up to: 5.3.0
 * WC requires at least: 3.0.0
 * WC tested up to: 3.8.0
 * Description:       A very powerfull plugin which implements <strong>Enhanced E-commerce Tracking Scripts</strong> in your woocommerce store.
 * Version:           1.0.3
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       anawoo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$mwb_anawoo_activated = true;

$mwb_anawoo_org_flag = 1;

/**
 * Checking if WooCommerce is active
 */

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	$mwb_anawoo_activated = false;

	$mwb_anawoo_org_flag = 0;

}

if ( $mwb_anawoo_activated && $mwb_anawoo_org_flag ) {

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-mwb-enhanced-ecommerce-activator.php
	 */
	function activate_analytics_for_woocommerce() {

		require_once plugin_dir_path( __FILE__ ) . 'includes/class-analytics-for-woocommerce-activator.php';

		Analytics_For_Woocommerce_Activator::activate();

	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-mwb-enhanced-ecommerce-deactivator.php
	 */
	function deactivate_analytics_for_woocommerce() {

		require_once plugin_dir_path( __FILE__ ) . 'includes/class-analytics-for-woocommerce-deactivator.php';

		Analytics_For_Woocommerce_Deactivator::deactivate();

	}

	register_activation_hook( __FILE__, 'activate_analytics_for_woocommerce' );

	register_deactivation_hook( __FILE__, 'deactivate_analytics_for_woocommerce' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * Admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-analytics-for-woocommerce.php';

	/**
	 * Define constants of the plugin which are used in different path
	 * The core plugin class that is used to define internationalization,
	 *
	 * @since 1.0.0
	 */
	function mwb_anawoo_define_constants() {

		mwb_anawoo_define( 'MWB_ANAWOO_ABSPATH', dirname( __FILE__ ) . '/' );
		mwb_anawoo_define( 'MWB_ANAWOO_URL', plugin_dir_url( __FILE__ ) );
		$report_link = 'https://datastudio.google.com/u/0/reporting/1bmmhcWmw5vOqeqhybX8fnReLyCWcwct5/page/P5Fh';
		mwb_anawoo_define( 'MWB_ANAWOO_DEMO_REPORT', $report_link );
		mwb_anawoo_define( 'MWB_SUPPORT_EMAIL', 'support@makewebbetter.com' );
		mwb_anawoo_define( 'PLUGIN_NAME_VERSION', '1.0.3' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name   This parameter contain the name.
	 * @param string|bool $value  This parameter contain the value.
	 * @since 1.0.0.
	 */
	function mwb_anawoo_define( $name, $value ) {

		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	// Discontinue notice.
	add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'mwb_afw_add_discontinue_notice', 0, 3 );

	/**
	 * Begins execution of the plugin.
	 *
	 * @param mixed $plugin_file The plugin file name.
	 * @param mixed $plugin_data The plugin file data.
	 * @param mixed $status      The plugin file status.
	 * @since 1.0.0
	 */
	function mwb_afw_add_discontinue_notice( $plugin_file, $plugin_data, $status ) {
		include_once plugin_dir_path( __FILE__ ) . 'extra-templates/makewebetter-plugin-discontinue-notice.html';
	}


	/**
	 * Setting Page Link
	 *
	 * @param string $actions   This parameter contain the actions.
	 * @param string $plugin_file   This parameter contain the plugin_file.
	 * @since 1.0.
	 * @author MakeWebBetter
	 * @link http://makewebbetter.com/
	 */
	function mwb_anawoo_admin_settings( $actions, $plugin_file ) {

		static $plugin;

		if ( ! isset( $plugin ) ) {

			$plugin = plugin_basename( __FILE__ );

		}

		if ( $plugin == $plugin_file ) {

			$settings = array(

				'settings' => '<a href="' . admin_url( 'admin.php?page=mwb_anawoo' ) . '">' . __( 'Settings', 'anawoo' ) . '</a>',

			);

			$actions = array_merge( $settings, $actions );
		}
		return $actions;
	}

	// Add link for settings.
	add_filter( 'plugin_action_links', 'mwb_anawoo_admin_settings', 10, 5 );

	/**
	 * Add plugin row meta
	 *
	 * @param string $links   This parameter contain the links.
	 * @param string $file   This parameter contain the file.
	 * @since 1.0.0
	 */
	function mwb_anawoo_plugin_row_meta( $links, $file ) {

		if ( strpos( $file, 'analytics-for-woocommerce' ) !== false ) {

			$row_meta = array(

				'docs' => '<a style="color:#FFF;background-color:#5ad75a;padding:5px;border-radius:6px;" href="https://docs.makewebbetter.com/" target="_blank">' . esc_html__( 'Documentation', 'anawoo' ) . '</a>',

			);

			return array_merge( $links, $row_meta );

		}

		return (array) $links;

	}

	add_filter( 'plugin_row_meta', 'mwb_anawoo_plugin_row_meta', 10, 2 );

	/**
	 * Auto Redirection to settings page after plugin activation
	 *
	 * @since 1.0.0
	 * @author MakeWebBetter
	 * @link https://makewebbetter.com/
	 * @param string $plugin This is the plugin parameter.
	 */
	function mwb_anawoo_activation_redirect( $plugin ) {

		if ( plugin_basename( __FILE__ ) == $plugin ) {

			exit( esc_url( wp_safe_redirect( admin_url( 'admin.php?page=mwb_anawoo' ) ) ) );

		}
	}

	// redirect to settings page as soon as plugin is activated.

	add_action( 'activated_plugin', 'mwb_anawoo_activation_redirect' );

	/**
	 * Begins execution of the plugin.
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since 1.0.0
	 */
	function run_analytics_for_woocommerce() {

		mwb_anawoo_define_constants();
		$plugin = new Analytics_For_Woocommerce();
		$plugin->run();
	}
	run_analytics_for_woocommerce();
}

if ( ! $mwb_anawoo_activated && 0 === $mwb_anawoo_org_flag ) {

	add_action( 'admin_init', 'mwb_anawoo_plugin_deactivate' );

	/**
	 * Call Admin notices
	 *
	 * @name mwb_anawoo_plugin_deactivate()
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_anawoo_plugin_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'mwb_anawoo_plugin_error_notice' );
	}

	/**
	 * Show warning message if woocommerce is not install
	 *
	 * @since 1.0.0
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_anawoo_plugin_error_notice() {

		?>
		<div class="error notice is-dismissible">
			<p><?php esc_html_e( 'WooCommerce is not activated, Please activate WooCommerce first to install MWB Enhanced E-commerce.', 'anawoo' ); ?></p>
		</div>
		<style>
		#message{display:none;}
	</style>
		<?php
	}
}
