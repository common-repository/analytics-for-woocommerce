<?php
/**
 * The doc comment of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/public
 */

$notice = '';
// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
$tb = 'general';
if ( isset( $_GET['page'] ) && 'mwb_anawoo' == $_GET['page'] ) {
	if ( isset( $_GET['tab'] ) && '' != $_GET['tab'] ) {
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$tb = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
	}
	if ( isset( $_POST['mwb_anawoo_save_admin_settings'] ) ) {
		$anawoo_admin_setting = wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwb_anawoo_save_admin_settings'] ) ) );
	}
	if ( isset( $_GET['configure_report'] ) && ! isset( $anawoo_admin_setting ) ) {
		update_option( 'mwb_anawoo_report_settings', array() );
		$redirect = admin_url( 'admin.php?page=mwb_anawoo&tab=report' );
		wp_redirect( $redirect );
		die();
	}
}


if ( isset( $_POST['mwb_anawoo_save_admin_settings'] ) ) {

	$value = sanitize_text_field( wp_unslash( $_POST['mwb_anawoo_save_admin_settings'] ) );

	if ( '' != $value ) {
		unset( $_POST['mwb_anawoo_save_admin_settings'] );
		update_option( 'mwb_anawoo_settings', serialize( $_POST ) );
		$notice = __( 'Settings Saved Successfully', 'anawoo' );
	}
}
if ( isset( $_POST['mwb_anawoo_save_report_settings'] ) ) {

	$value = sanitize_text_field( wp_unslash( $_POST['mwb_anawoo_save_report_settings'] ) );

	if ( '' != $value ) {
		unset( $_POST['mwb_anawoo_save_report_settings'] );
		update_option( 'mwb_anawoo_report_settings', serialize( $_POST ) );
		$notice = __( 'Settings Saved Successfully', 'anawoo' );
	}
}

$data = unserialize( get_option( 'mwb_anawoo_settings' ) );
$exluded_roles = array();
if ( isset( $data['mwb_anawoo_excluded_roles'] ) ) {
	$exluded_roles = $data['mwb_anawoo_excluded_roles'];
}
?>
<div class="mwb_anawoo_admin_wrap">
	<div class="mwb_anawoo_admin_head">
		<img src="<?php echo esc_url( MWB_ANAWOO_URL . '/admin/img/pannel.jpg' ); ?>">
	</div>
	<?php
	if ( '' != $notice ) {
		?>
		<div class="mwb_anawoo_admin_notice">
			<p><?php echo esc_attr( $notice ); ?><span class="mwb_anawoo_admin_close">x</span></p>
		</div>
	<?php } ?>
	<div class="mwb_anawoo_admin_tab_wrap">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=mwb_anawoo' ) ); ?>" class="mwb_anawoo_admin_tab 
			<?php
			if ( 'general' == $tb ) {
				echo 'active';
			};
			?>
			"><?php esc_html_e( 'General Settings', 'anawoo' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mwb_anawoo&tab=report' ) ); ?>" class="mwb_anawoo_admin_tab 
				<?php
				if ( 'report' == $tb ) {
					echo 'active';
				};
				?>
				"><?php esc_html_e( 'Report', 'anawoo' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=mwb_anawoo&tab=contact' ) ); ?>" class="mwb_anawoo_admin_tab 
					<?php
					if ( 'contact' == $tb ) {
						echo 'active';
					};
					?>
					"><?php esc_html_e( 'Contact Us', 'anawoo' ); ?>
				</a>
			</div>
			<?php
			if ( '' != $tb ) {
				include_once MWB_ANAWOO_ABSPATH . 'admin/partials/templates/' . $tb . '.php';
			}
			?>
		</div>
