<?php

namespace ProLogin\Frontend;

if (!defined('ABSPATH')) {
  exit;
}

class EmailVerification
{

  public function init()
  {
    add_action('init', [$this, 'verify_email']);
  }

  public function verify_email()
  {
    if (isset($_GET['verify_email']) && isset($_GET['user_id'])) {
      $user_id = intval($_GET['user_id']);
      $verification_code = sanitize_text_field($_GET['verify_email']);

      $stored_code = get_user_meta($user_id, 'email_verification_code', true);

      if ($stored_code === $verification_code) {
        update_user_meta($user_id, 'is_email_verified', true);
        delete_user_meta($user_id, 'email_verification_code');

        $_SESSION['pro_login_message'] = ['type' => 'success', 'text' => 'Email verified successfully. You can now log in.'];

        wp_redirect(home_url('/?verified=success'));
        exit;
      } else {
        $_SESSION['pro_login_message'] = ['type' => 'error', 'text' => 'Invalid verification link.'];
        wp_redirect(home_url('/'));
        exit;
      }
    }
  }
}
