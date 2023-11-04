<?php

namespace ParkdaleWire\PasswordSecurity;

defined( 'ABSPATH' ) || exit;


function password_strength(): ?int {

	static $password_strength = null;

	if ( null === $password_strength ) {
		$password_strength = (int) ( get_option( 'th_custom_password_strength' )['option'] ?? 2 );
	}

	return $password_strength;
}


function print_password_security_styles(): void {

	echo /** @lang HTML */ <<<'CSS'
<style>
    tr#password ~ tr.pw-weak th,
    tr#password ~ tr.pw-weak td {
        padding-top: 0;
    }
    #resetpassform div.pw-weak span.pass-requirements,
    tr#password ~ tr.pw-weak td span.pass-requirements {
    	display: block;
    	color: #a2050a;
    	font-weight: 700;
    	padding-left: 3px;
    	margin-top: -10px;
    }
</style>
CSS;
}


add_action( 'admin_head', __NAMESPACE__ . '\add_disallow_weak_passwords_style' );
add_action( 'login_head', __NAMESPACE__ . '\add_disallow_weak_passwords_style' );
/**
 * Description.
 *
 * @return void
 */
function add_disallow_weak_passwords_style(): void {

	if ( password_strength() > 1 ) {

		print_password_security_styles();
	}
}


add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_password_strength_script' );
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\load_password_strength_script' );
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
}


