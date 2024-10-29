<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Analytics_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$settings = get_option( 'mwb_anawoo_settings', array() );

		if ( empty( $settings ) ) {

			$settings['mwb_anawoo_analytics_id'] = ' ';

			$settings['mwb_anawoo_enable_gtag'] = 'on';

			$settings['mwb_anawoo_enable_ec'] = 'on';

			$settings['mwb_anawoo_ip_anoymization'] = '';

			$settings['mwb_anawoo_opt_out'] = '';

			$settings['mwb_anawoo_excluded_roles'] = array( 'administrator' );

			update_option( 'mwb_anawoo_settings', serialize( $settings ) );

		}

	}
}
