<?php

namespace TH\PasswordSecurity;

defined( 'ABSPATH' ) || exit;


/**
 * Get the password strength setting value, or fall back to default "Medium-strength required".
 *
 * @return int|null
 */
function password_strength(): ?int {

	static $password_strength = null;

	if ( null === $password_strength ) {

		$password_strength = 2;
		$password_strength_field = get_option( 'th_custom_password_strength' );
		if ( is_array( $password_strength_field ) && isset( $password_strength_field['option'] ) ) {
			$password_strength = (int) $password_strength_field['option'] ?: 2;
		}

		if ( defined( 'TH_CUSTOM_PASSWORD_STRENGTH' ) && is_numeric( TH_CUSTOM_PASSWORD_STRENGTH ) ) {
			$str_override = TH_CUSTOM_PASSWORD_STRENGTH;
		} else {
			$str_override = apply_filters( 'th_password_strength_override', false );
		}

		if ( $str_override !== false ) {

			$str_override = (int) $str_override;
			$allowed      = [ 1, 2, 3 ];

			if ( in_array( $str_override, $allowed ) ) {
				$password_strength = $str_override;
			}
		}
	}

	return $password_strength;
}


add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_password_strength_script' );
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\load_password_strength_script' );
/**
 * Enqueue custom password-strength validation js.
 *
 * @return void
 */
function load_password_strength_script(): void {

	$password_strength = password_strength();

	$script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	$suffix       = $script_debug ? '' : '.min';

	if ( $password_strength > 1 ) {

		wp_enqueue_script(
			'pw-password-strength',
			TH_PASSWORD_SECURITY_DIR_URL . 'assets/password-strength' . $suffix . '.js',
			[ 'jquery', 'password-strength-meter', 'zxcvbn-async' ],
			TH_PASSWORD_SECURITY_VERSION,
			true
		);

		wp_localize_script('pw-password-strength', 'th_pass_strength_setting', array(
			'strength' => (int) $password_strength,
		));
	}

	if ( $password_strength == 3 ) {

		// Translate some password-strength labels.
		wp_localize_script('password-strength-meter', 'pwsL10n', array(
			'unknown'  => _x( 'Password strength unknown', 'password strength' ),
			'short'    => _x( 'Very weak', 'password strength' ),
			'bad'      => _x( 'Weak', 'password strength' ) . ' - ' . _x( 'Medium', 'password strength' ),
			'good'     => _x( 'Weak', 'password strength' ) . ' - ' . _x( 'Medium', 'password strength' ),
			'strong'   => _x( 'Strong', 'password strength' ),
			'mismatch' => _x( 'Mismatch', 'password mismatch' ),
		));
	}
}

