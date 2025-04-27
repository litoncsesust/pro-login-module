<?php
/*
Plugin Name: Pro Login Module
Description: A professional login, registration, and profile management plugin.
Version: 1.0.0
Author: Mohammad Liton
Author URI: https://www.linkedin.com/in/litonmohammad/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: pro-login-module
Domain Path: /languages
*/



if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Start session globally
add_action('init', function() {
    if (!session_id()) {
        session_start();
    }
}, 1);

add_action('plugins_loaded', function() {
    load_plugin_textdomain('pro-login-module', false, dirname(plugin_basename(__FILE__)) . '/languages');
});


// Plugin Constants
define('PRO_LOGIN_MODULE_PATH', plugin_dir_path(__FILE__));
define('PRO_LOGIN_MODULE_URL', plugin_dir_url(__FILE__));
define('PRO_LOGIN_MODULE_VERSION', '1.0');

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'ProLogin\\';
    $base_dir = __DIR__ . '/includes/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Activation/Deactivation Hooks
register_activation_hook(__FILE__, ['ProLogin\\Core\\Activator', 'activate']);
register_deactivation_hook(__FILE__, ['ProLogin\\Core\\Deactivator', 'deactivate']);

// Run the Plugin
function run_pro_login_module() {
    $plugin = new ProLogin\\Core\\Loader();
    $plugin->run();
}
run_pro_login_module();
