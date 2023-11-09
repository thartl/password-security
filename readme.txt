=== Password Security ===
Contributors: thartl
Requires at least: 6.1
Tested up to: 6.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Helps enforce password security strength for WordPress user accounts.

== Description ==

This plugin adds two options to the Settings > General screen:
* Require medium-strength passwords
* Require strong passwords

Initial setting after first-time installation is "Require medium-strength passwords".

Please note, Password Security only modifies the core password strength meter functionality.
Password strength is not validated on the server.
WP CLI password requirements are unmodified.

= Scope =
Only the following password forms are handled:
* User edit screen (user-edit.php)
* User profile screen (profile.php)
* Password reset page (wp-login.php?action=rp)

= Code overrides =
Settings can be overridden by a filter ('th_password_strength_override') or a constant (TH_CUSTOM_PASSWORD_STRENGTH).
To enforce default WordPress behaviour, set either to 1.
To force Medium-strength passwords, set to 2.
To force strong passwords, set to 3.
The constant trumps the filter.

With an active override, password-strength settings under Settings > General are read-only.

**Override memory**
Once an override is set up, and a supported password form is loaded, the override value is copied to the database.
When you deactivate an override, password requirements remain unchanged until you update the password strength setting under Settings > General.


== Installation ==

From your WordPress dashboard

1. **Visit** Plugins > Add New
2. **Search** for "Password Security"
3. **Install and Activate** Password Security from your Plugins page
4. **Navigate** to Settings > General.
5. **Select** a password-strength setting, near the bottom of Settings > General.


== Frequently Asked Questions ==

= What kind of support do you provide? =

**Support Forum** You may create a new thread on this plugin's support forum. We will do our best to answer your questions.
We will consider feature requests as time allows.

= How can I override the setting with a filter? =

**Example of a filter override:**
`
add_filter( 'th_password_strength_override', function () {
 	return 2; // Require medium-strength password
 } );`

= How can I override the setting with a constant? =

**Example of a constant override:**
`
const TH_CUSTOM_PASSWORD_STRENGTH = 3; // Require strong password`

= How can I override the setting via WP CLI? =

**Example - Strong password:**
wp config set TH_CUSTOM_PASSWORD_STRENGTH 3

= What are the override values? =

**1** = WordPress default
**2** = Medium-strength password required
**3** = Strong password required


== Changelog ==

= 0.3.1 =
* Fix PHP warning

= 0.3.0 =
* Add a filter override
* Add a constant override

= 0.2.0 =
* Simpler messaging
* Add a build script

= 0.1.0 =
* Initial beta release
