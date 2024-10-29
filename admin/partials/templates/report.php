<?php
/**
 * The report functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    analytics-for-woocommerce
 * @subpackage analytics-for-woocommerce/public
 */

$report_data = '';
if ( ! empty( get_option( 'mwb_anawoo_report_settings' ) ) ) {
	$report_data = unserialize( get_option( 'mwb_anawoo_report_settings' ) );
}

$report_url = ' ';
$link_notice = ' ';
if ( isset( $report_data['mwb_anawoo_report_url'] ) && ! empty( $report_data['mwb_anawoo_report_url'] ) ) {
	$report_url = $report_data['mwb_anawoo_report_url'];
	$report_height = ! empty( $report_data['mwb_anawoo_report_height'] ) ? $report_data['mwb_anawoo_report_height'] : '1000';
	$report_width = ! empty( $report_data['mwb_anawoo_report_width'] ) ? $report_data['mwb_anawoo_report_width'] : '1000';
	$click_here_url = '<a href = "?page=mwb_anawoo&tab=report&configure_report=1">click here</a>';
	 /* translators: %s: search term */
	$link_notice = sprintf( __( 'To configure your report %s.', 'anawoo' ), $click_here_url );

}

?>

<?php if ( ' ' != $link_notice ) : ?>
<div class="mwb_anawoo_admin_notice">
	<p>
	<?php
	/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
	echo $link_notice;
	/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
	?>
	<span class="mwb_anawoo_admin_close">x</span></p>
</div>
<?php endif; ?>
<div class="mwb_anawoo_admin_body_wrap">
	<?php if ( ' ' == $report_url ) : ?>
<div class="mwb_anawoo_admin_table_wrap">
		<div class="mwb_anawoo_admin_body">
			<form method="post">
				<table class="mwb_anawoo_admin_table">
					<tbody>
						<tr>
							<td>
								<label><?php esc_html_e( 'Data Studio Report Url', 'anawoo' ); ?></label>
							</td>
							<td>
								<input type="text" name="mwb_anawoo_report_url" class="mwb_anawoo_report_url">
										<?php
										$desc = __( 'Enter your report embed url', 'anawoo' );

										echo wp_kses_post( wc_help_tip( $desc ) );
										?>
										<a href="<?php echo esc_url( MWB_ANAWOO_DEMO_REPORT ); ?>" target = "_blank">
											 <?php esc_html_e( 'Get MWB Demo Report', 'anawoo' ); ?>
										</a>
										
							</td>
						</tr>
						<tr>
							<td>
								<label><?php echo esc_attr( 'Report Height', 'anawoo' ); ?></label>
							</td>
							<td>
								<input type="number" name="mwb_anawoo_report_height" class="mwb_anawoo_report_url">
										<?php
										$desc = __( 'Enter height of report to adjust at your dashboard', 'anawoo' );
										echo wp_kses_post( wc_help_tip( $desc ) );
										?>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php esc_html_e( 'Report Width', 'anawoo' ); ?></label>
							</td>
							<td>
								<input type="number" name="mwb_anawoo_report_width" class="mwb_anawoo_report_url">
										<?php
										$desc = __( 'Enter width of report to adjust at your dashboard', 'anawoo' );
										echo wp_kses_post( wc_help_tip( $desc ) );
										?>
							</td>
						</tr>
					</tbody>
				</table>
				<p id="mwb_anawoo_admin_save">
					<input type="submit" id="mwb_anawoo_save_admin_settings" name="mwb_anawoo_save_report_settings" class="button-primary" value="<?php esc_html_e( 'Save Settings', 'anawoo' ); ?>" />
				</p>
			</form>
		</div>
	</div>
	<div class="mwb_anawoo_admin_table_sidebar">
		<div class="mwb_anawoo_table_sidebar mwb_anawoo_report">
			<h4><?php esc_html_e( 'Steps to access data studio reports', 'anawoo' ); ?></h4>
			<ul>
				<li>
					<?php esc_html_e( 'Login with your google analytics account', 'anawoo' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Access the template provided in the plugin ', 'anawoo' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Create a copy of template', 'anawoo' ); ?>

				</li>
				<li>
					<?php esc_html_e( 'Select data source', 'anawoo' ); ?>

				</li>
				<li>
					<?php esc_html_e( 'Edit the template if you want', 'anawoo' ); ?>
				<li>
					<?php esc_html_e( 'Get embed URL', 'anawoo' ); ?>
				</li>
				<li>
					<?php esc_html_e( 'Insert embed URL into the provided text field', 'anawoo' ); ?>
				</li>
				<li><?php esc_html_e( 'Save Settings', 'anawoo' ); ?></li>

				<li ><a  class = "mwb_anawoo_doc_link" href=<?php echo esc_url( 'https://docs.makewebbetter.com/analytics-for-woocommerce' ); ?> ><?php echo esc_attr_e( 'View Documentation', 'anawoo' ); ?></a>
				</li>
			<ul>
		</div>
	</div>
	<?php else : ?>
		<iframe width="<?php echo esc_attr( $report_width ); ?>" height="<?php echo esc_attr( $report_width ); ?>" src="<?php echo esc_attr( $report_url ); ?>" frameborder="0" style="border:0 ; overflow: hidden;"  scrolling = "no"></iframe>
	<?php endif; ?> 
</div>
