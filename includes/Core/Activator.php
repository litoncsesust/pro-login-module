<?php

namespace ProLogin\Core;

if (!defined('ABSPATH')) {
  exit;
}

class Activator
{

  public static function activate()
  {
    // Optional: Set default plugin options
    if (false === get_option('pro_login_settings')) {
      $defaults = [
        'enable_email_verification' => 1,
        'redirect_after_login' => home_url(),
        'redirect_after_register' => home_url(),
        'redirect_after_profile_update' => home_url('/profile')
      ];
      add_option('pro_login_settings', $defaults);
    }

    update_option('pro_login_module_do_activation_redirect', true);
  }
}
