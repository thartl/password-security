<?php

namespace ParkdaleWire\PasswordSecurity;

defined( 'ABSPATH' ) || exit;


/**
 * Get the password strength setting value, or fall back to default "Medium-strength required".
 *
 * @return int|null
 */
function password_strength(): ?int {

	static $password_strength = null;

	if ( null === $password_strength ) {
		$password_strength = (int) ( get_option( 'th_custom_password_strength' )['option'] ?? 2 );
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

	if ( $password_strength > 1 ) {

		wp_enqueue_script(
			'pw-password-strength',
			TH_PASSWORD_SECURITY_DIR_URL . 'assets/password-strength.js',
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

