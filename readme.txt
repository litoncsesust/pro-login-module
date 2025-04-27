=== Pro Login Module ===
Contributors: mliton
Tags: login, register, user profile, email verification, ajax login
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A professional login, registration, and profile management plugin with AJAX support and email verification.


== Description ==

Pro Login Module is a powerful WordPress plugin that provides:

- Frontend Login Form with AJAX support
- Frontend Registration Form
- Frontend User Profile Update
- Email Verification System
- Session-based Success/Error Messaging
- Admin Settings Page (Enable Email Verification, Redirect Settings)
- Uninstall Script for clean removal
- Fully Modular and Scalable PSR-4 Architecture
- Translation-ready and Extendable

Built professionally with extensibility and user experience in mind!

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/pro-login-module` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. After activation, you will be redirected to **Settings → Pro Login Module** to configure settings.
4. Set up:
   - Enable or disable Email Verification
   - Configure Redirect URLs after login, registration, and profile update
5. Create a **"Please Verify Your Email"** page (optional but recommended).
6. Place the shortcodes on any page:

Shortcodes available:

- `[pro_login_form]` - Display the login form
- `[pro_register_form]` - Display the registration form
- `[pro_profile_form]` - Display the profile update form

== Frequently Asked Questions ==

= Can users register directly from the frontend? =
Yes! Use the `[pro_register_form]` shortcode anywhere.

= Is email verification mandatory? =
You can enable or disable email verification from the plugin settings.

= Will the plugin delete user data on uninstall? =
Only the plugin settings and email verification metadata will be removed. Your WordPress users remain safe.

= Does the login form work via AJAX? =
Yes! The login form submits via AJAX without page reload.

== Screenshots ==

1. Pro Login Module Settings Page
2. Frontend Login Form
3. Frontend Registration Form
4. Frontend Profile Update Form
5. Email Verification Success Message

== Changelog ==

= 1.0.0 =
* Initial release.
* Frontend login, registration, and profile shortcodes.
* Email verification system.
* Admin settings page with redirect options.
* AJAX login support.

== Upgrade Notice ==

= 1.0.0 =
First release - stable and production-ready.

== License ==

This plugin is licensed under the GPLv2 or later.

Copyright © Mohammad Liton
