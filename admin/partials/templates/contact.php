<?php
/**
 * The contact us functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/admin
 */

$mail_notice = ' ';
if ( ! empty( $_POST['mwb_anawoo_send_contact_details'] ) ) {

	$name = site_url();
	$name = ! empty( $name ) ? $name : '';

	$email = ! empty( $_POST['mwb_anawoo_contact_email'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_anawoo_contact_email'] ) ) : '';
	$subject = ! empty( $_POST['mwb_anawoo_contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_anawoo_contact_subject'] ) ) : '';
	$content = ! empty( $_POST['mwb_anawoo_contact_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mwb_anawoo_contact_content'] ) ) : '';

	if ( ! empty( $email ) && ! empty( $subject ) && ! empty( $content ) ) {

		// php mailer variables.
		$to = wp_verify_nonce( MWB_SUPPORT_EMAIL );

		$headers = array( 'Content-Type: text/html; charset=UTF-8', "From: $name <$email>" );

		$message = "<h2>Analytics for WooCommerce<h2>
		<p>$content</p>";


		// Here put your Validation and send mail.
		$sent = wp_mail( $to, $subject, stripslashes( $message ), $headers );

		if ( $sent ) {

			$mail_notice = __( 'E-Mail sent !', 'anawoo' );
		} else {

			$mail_notice = __( 'E-mail could not be sent !', 'anawoo' );
		}
	}
}
?>	
<?php if ( ' ' != $mail_notice ) : ?>
<div class="mwb_anawoo_admin_notice">
	<p><?php echo esc_attr( $mail_notice ); ?><span class="mwb_anawoo_admin_close">x</span></p>
</div>
<?php endif; ?>
<div class="mwb_anawoo_admin_body_wrap">
	<div class="mwb_anawoo_admin_table_wrap">
		<div class="mwb_anawoo_admin_body">
			<div class="mwb_anawoo_admin_intro">
				<p>
					<?php esc_html_e( 'Facing issues with our plugin? Send us an email !', 'anawoo' ); ?>
				</p>
			</div>
			<form method="post">
				<table class="mwb_anawoo_admin_table">
					<tbody>
						<tr>
							<td>
								<label><?php esc_html_e( 'Your Email', 'anawoo' ); ?></label>
							</td>
							<td>
								<input required="" type="text" name="mwb_anawoo_contact_email" class="mwb_anawoo_report_url" value="<?php echo esc_attr( get_option( 'admin_email', '' ) ); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<label><?php esc_html_e( 'Subject', 'anawoo' ); ?></label>
							</td>
							<td>
								<input required="" type="text" name="mwb_anawoo_contact_subject" class="mwb_anawoo_report_url" value="<?php echo esc_attr( "Regarding issue with 'Analytics For WooCommerce' Plugin", 'anawoo' ); ?>">
							</td>
						</tr>
						<tr>
							<td>
								<label><?php esc_html_e( 'Content','anawoo' ); ?></label>
							</td>
							<td>
								<textarea required="" rows="8" cols="50" name="mwb_anawoo_contact_content" class="mwb_anawoo_report_url" placeholder="<?php echo esc_attr( 'Please Mention your issue here', 'anawoo' ); ?>"></textarea>
							</td>
						</tr>
					</tbody>
				</table>
				<p id="mwb_anawoo_admin_save">
					<input type="submit" id="mwb_anawoo_save_admin_settings" name="mwb_anawoo_send_contact_details" class="button-primary" value="<?php echo esc_attr( 'Send', 'anawoo' ); ?>" />
				</p>
			</form>
		</div>
	</div>
</div>
