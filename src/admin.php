<?php

namespace ParkdaleWire\PasswordSecurity;

defined( 'ABSPATH' ) || exit;


add_action( 'admin_init', __NAMESPACE__ . '\register_settings' );
/**
 * Add settings fields.
 *
 * @return void
 */
function register_settings(): void {

	register_setting( 'general', 'th_custom_password_strength', __NAMESPACE__ . '\th_password_strength_options_sanitize' );

	add_settings_section(
		'th_password_settings_section',
		'',
		'',
		'general',
		[
			'before_section' => '<div id="th-password-settings-section">',
			'after_section'  => '</div>',
		]
	);

	add_settings_field(
		'th-password-strength',
		'User password strength',
		__NAMESPACE__ . '\render_password_strength_radio_buttons',
		'general',
		'th_password_settings_section'
	);
}


/**
 * Print settings fields.
 *
 * @return void
 */
function render_password_strength_radio_buttons(): void {

	$disabled          = '';
	$override_note     = '';
	$config_override   = 0;
	$password_strength = (int) get_option( 'th_custom_password_strength' )['option'] ?? 2;

	if ( defined( 'TH_CUSTOM_PASSWORD_STRENGTH' ) && is_numeric( TH_CUSTOM_PASSWORD_STRENGTH ) ) {
		$str_override = TH_CUSTOM_PASSWORD_STRENGTH;
		$config_override = 1;
	} else {
		$str_override = apply_filters( 'th_password_strength_override', false );
	}

	if ( $str_override !== false ) {

		$str_override = (int) $str_override;
		$allowed      = [ 1, 2, 3 ];

		if ( in_array( $str_override, $allowed ) ) {

			$disabled = 'disabled';

			if ( $config_override ) {
				$override_note = '<p>Password strength is set in wp-config, via the `TH_CUSTOM_PASSWORD_STRENGTH` constant.<br>&nbsp;</p>';
			} else {
				$override_note = '<p>Password strength is set in code, via the `th_custom_password_strength` filter.<br>&nbsp;</p>';
			}

			if ( $password_strength != $str_override ) {
				update_option( 'th_custom_password_strength', array( 'option' => (string) $str_override ) );
			}

			$password_strength = $str_override;
		}
	}

	echo $override_note;

	?>
    <fieldset>
        <label for="th-password-strength-default">
            <input type="radio" id="th-password-strength-default" <?php echo $disabled; ?>
                   name="th_custom_password_strength[option]" <?php checked( $password_strength, '1' ); ?> value="1">
            <span>Allow weak passwords, with confirmation (WordPress default)</span></label><br>
        <label for="th-password-strength-required-medium">
            <input type="radio" id="th-password-strength-required-medium" <?php echo $disabled; ?>
                   name="th_custom_password_strength[option]" <?php checked( $password_strength, '2' ); ?> value="2">
            <span>Require medium-strength passwords</span></label><br>
        <label for="th-password-strength-required-strong">
            <input type="radio" id="th-password-strength-required-strong" <?php echo $disabled; ?>
                   name="th_custom_password_strength[option]" <?php checked( $password_strength, '3' ); ?> value="3">
            <span>Require strong passwords</span></label><br>
    </fieldset>
	<?php
}


/**
 * Sanitize password settings fields.
 *
 * @param $input
 *
 * @return array
 */
function th_password_strength_options_sanitize( $input ): array {

	$output  = [];
	$allowed = [ '1', '2', '3' ];

	foreach ( $input as $key => $value ) {

		if ( in_array( $value, $allowed, true ) ) {
			$output[ $key ] = $value;
		} else {
			$output[ $key ] = '2';
		}
	}

	return $output;
}


add_filter( 'plugin_action_links_' . TH_PASSWORD_SECURITY_BASENAME, __NAMESPACE__ . '\plugin_settings_link' );
/**
 * Add a Settings link.
 *
 * @param $links
 *
 * @return mixed
 */
function plugin_settings_link( $links ) {

	$settings_link = '<a href="' . admin_url( 'options-general.php#th-password-settings-section' ) . '">Settings</a>';
	$links[]       = $settings_link;

	return $links;
}
