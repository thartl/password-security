<?php

namespace ParkdaleWire\PasswordSecurity;

defined( 'ABSPATH' ) || exit;


add_action( 'admin_init', __NAMESPACE__ . '\register_settings' );
function register_settings() {

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


function render_password_strength_radio_buttons() {

	$option = get_option( 'th_custom_password_strength' )['option'] ?? '2';

	?>
    <fieldset>
        <label for="th-password-strength-default">
            <input type="radio" id="th-password-strength-default"
                   name="th_custom_password_strength[option]" <?php checked( $option, '1' ); ?> value="1">
            <span>Allow weak passwords, with confirmation (WordPress default)</span></label><br>
        <label for="th-password-strength-required-medium">
            <input type="radio" id="th-password-strength-required-medium"
                   name="th_custom_password_strength[option]" <?php checked( $option, '2' ); ?> value="2">
            <span>Require medium-strength passwords</span></label><br>
        <label for="th-password-strength-required-strong">
            <input type="radio" id="th-password-strength-required-strong"
                   name="th_custom_password_strength[option]" <?php checked( $option, '3' ); ?> value="3">
            <span>Require strong passwords</span></label><br>
    </fieldset>
	<?php
}


function th_password_strength_options_sanitize( $input ) {

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
function plugin_settings_link( $links ) {

	$settings_link = '<a href="' . admin_url( 'options-general.php#th-password-settings-section' ) . '">Settings</a>';
	$links[]       = $settings_link;

	return $links;
}
