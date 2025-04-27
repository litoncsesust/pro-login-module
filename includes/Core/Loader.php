<?php

namespace ProLogin\Core;

use ProLogin\Admin\Admin;
use ProLogin\Frontend\Login;
use ProLogin\Frontend\Register;
use ProLogin\Frontend\Profile;
use ProLogin\Frontend\EmailVerification;
use ProLogin\Frontend\ForgotPassword;

if (!defined('ABSPATH')) {
  exit;
}

class Loader
{

  public function run()
  {
    if (is_admin()) {
      (new Admin())->init();
    }

    (new Login())->init();
    (new Register())->init();
    (new Profile())->init();
    (new EmailVerification())->init();
    (new ForgotPassword())->init();
  }
}
