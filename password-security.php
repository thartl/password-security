<?php
/*
Plugin Name:  Password Security
Description:  Helps enforce password security strength for WordPress user accounts.
Author:       Tomas Hartl
Version:      0.3.2
Requires PHP: 7.4
Requires WP:  6.1
Tested up to: 6.4.2


// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************
*/

defined( 'ABSPATH' ) || exit;


const TH_PASSWORD_SECURITY_VERSION = '0.3.1';

define( 'TH_PASSWORD_SECURITY_BASENAME', plugin_basename( __FILE__ ) );

$th_password_security_url = plugin_dir_url( __FILE__ );
if ( is_ssl() ) {
	$th_password_security_url = str_replace( 'http://', 'https://', $th_password_security_url );
}
define( 'TH_PASSWORD_SECURITY_DIR_URL', $th_password_security_url );


require_once __DIR__ . '/src/common.php';

if ( is_admin() ) {
	require_once __DIR__ . '/src/admin.php';
}

