<div class="mwb_anawoo_admin_body_wrap">
	<div class="mwb_anawoo_admin_table_wrap">
		<div class="mwb_anawoo_admin_body">
			<form method="POST">
				<table class="mwb_anawoo_admin_table">
					<tbody>
						<tr>
							<td>
								<label><?php
										/**
										 * The doc comment of the plugin.
										 *
										 * @link       https://makewebbetter.com/
										 * @package    analytics-for-woocommerce
										 * @subpackage analytics-for-woocommerce/public
										 */

										echo esc_attr( 'Google Analytics ID', 'anawoo' );
								?> 		
								</label>
							</td>
							<td>
								<input type="text" name="mwb_anawoo_analytics_id" value="<?php echo esc_attr( $data['mwb_anawoo_analytics_id'] ); ?>" required = "required">

								<?php
								$desc = __( 'Enter you Google Analytics Tracking ID here.', 'anawoo' );

								echo wp_kses_post( wc_help_tip( $desc ) );
								?>

							</td>
						</tr>
						<tr>
							<td>
								<label><?php esc_html_e( 'Add Global Tracking Code', 'anawoo' ); ?></label>
							</td>
							<td>
								<?php $mwb_anawoo_enable_gtag = ! empty( $data['mwb_anawoo_enable_gtag'] ) ? 'checked' : ''; ?>
								<label class="mwb_anawoo_admin-switch">
									<input type="checkbox" name="mwb_anawoo_enable_gtag"  <?php echo esc_attr( $mwb_anawoo_enable_gtag ); ?>>
									<span class="mwb_anawoo_admin-slider mwb_anawoo_admin-round"></span>
								</label>
								<?php
								$desc = __( 'Switch on this add the Global tracking code (gtag.js) to your site. Do not enable this feature if you have already implemented the tracking code.', 'anawoo' );

								echo wp_kses_post( wc_help_tip( $desc ) );
								?>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php esc_html_e( 'Enable Enhanced E-commerce Tracking', 'anawoo' ); ?></label>
							</td>
							<td>
								<?php $mwb_anawoo_enable_ec = ! empty( $data['mwb_anawoo_enable_ec'] ) ? 'checked' : ''; ?>
								<label class="mwb_anawoo_admin-switch">
									<input type="checkbox" name="mwb_anawoo_enable_ec"<?php echo esc_attr( $mwb_anawoo_enable_ec ); ?>>
									<span class="mwb_anawoo_admin-slider mwb_anawoo_admin-round"></span>
								</label>
								<?php

								$desc = __( 'Switch on this to implement Enhanced E-commerce Tracking scripts to your woocommerce pages', 'anawoo' );
								echo wp_kses_post( wc_help_tip( $desc ) );
								?>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php esc_html_e( 'Enable I.P. Anoymization', 'anawoo' ); ?></label>
							</td>
							<td>
								<?php $mwb_anawoo_ip_anoymization = ! empty( $data['mwb_anawoo_ip_anoymization'] ) ? 'checked' : ''; ?>
								<label class="mwb_anawoo_admin-switch">
									<input type="checkbox" name="mwb_anawoo_ip_anoymization"  <?php echo esc_attr( $mwb_anawoo_ip_anoymization ); ?> >
									<span class="mwb_anawoo_admin-slider mwb_anawoo_admin-round"></span>
								</label>

								<?php

								$desc = __( 'Switch on this to prevent Google Analytics from collecting I.P. Adresses of users', 'anawoo' );
								echo wp_kses_post( wc_help_tip( $desc ) );
								?>

							</td>
						</tr>
						<tr>
							<td>
								<label><?php esc_html_e( 'Disable Tracking For User roles', 'anawoo' ); ?></label>
							</td>
							<td>

								<?php

								$roles = Analytics_For_Woocommerce_Admin::mwb_anawoo_get_all_user_roles();
								?>
								<select name = "mwb_anawoo_excluded_roles[]" class="mwb_anawoo_excluded_roles" id ="mwb_anawoo_excluded_roles"  multiple="multiple">
									<?php
									foreach ( $roles as $key => $value ) {

										$selected  = '';
										if ( in_array( $key, $exluded_roles ) ) {
											$selected = 'selected';
										};
										?>

										<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>>
																  <?php
																	echo esc_attr( $value );
																	?>
										</option>

									<?php }; ?>
								</select>

								<?php

								$desc = __( 'Select user role on which tracking will be disabled', 'anawoo' );

								echo wp_kses_post( wc_help_tip( $desc ) );
								?>
							</td>
						</tr>
					</tbody>
				</table>
				<p id="mwb_anawoo_admin_save">
					<input type="submit" id="mwb_anawoo_save_admin_settings" name="mwb_anawoo_save_admin_settings" class="button-primary" value="<?php esc_html_e( 'Save Settings', 'anawoo' ); ?>" />
				</p>
			</form>
		</div>
	</div>
	<div class="mwb_anawoo_admin_table_sidebar">
		<div class="mwb_anawoo_table_sidebar">
			<h4><?php esc_html_e( 'Need Help ??', 'anawoo' ); ?></h4>
			<ul>
				<li><a target="_blank" href="https://docs.makewebbetter.com/analytics-for-woocommerce/#how-to-get-google-analytics-id"><?php esc_html_e( 'How to get analytics id ?', 'anawoo' ); ?></a></li>
				<li><a target="_blank" href="https://docs.makewebbetter.com/analytics-for-woocommerce/#how-to-add-global-tracking-code"><?php esc_html_e( 'How to get tracking code?', 'anawoo' ); ?></a></li>
				<li><a target="_blank" href="https://docs.makewebbetter.com/analytics-for-woocommerce/#how-to-enable-enhanced-e-commerce-tracking"><?php esc_html_e( 'How to enable enhanced e-commnerce in GA?', 'anawoo' ); ?></a></li>
				<li><a target="_blank" href="https://docs.makewebbetter.com/analytics-for-woocommerce/#how-to-disable-tracking-for-user-roles"><?php esc_html_e( 'How to setup checkout funnel steps?', 'anawoo' ); ?></a></li>
				<li><a target="_blank" href="https://docs.makewebbetter.com/analytics-for-woocommerce/#how-to-enable-i-p-anoymization"><?php esc_html_e( 'What is IP Anonymization?', 'anawoo' ); ?></a>
				</li>
			<ul>
		</div>
	</div>
</div>
