<?php

namespace ProLogin\Frontend;

if (!defined('ABSPATH')) {
  exit;
}

class ForgotPassword
{

  public function init()
  {
    add_shortcode('pro_forgot_password_form', [$this, 'render_form']);
    add_action('init', [$this, 'handle_forgot_password']);
  }

  public function render_form()
  {
    ob_start();
    if (isset($_SESSION['pro_login_message'])) {
      $message = $_SESSION['pro_login_message'];
      echo '<div class="pro-login-message ' . esc_attr($message['type']) . '">' . esc_html($message['text']) . '</div>';
      unset($_SESSION['pro_login_message']);
    }
    include PRO_LOGIN_MODULE_PATH . 'templates/forgot-password-form.php';
    return ob_get_clean();
  }

  public function handle_forgot_password()
  {
    if (isset($_POST['pro_forgot_password_submit'])) {
      $email = sanitize_email($_POST['pro_forgot_password_email']);

      if (!email_exists($email)) {
        $_SESSION['pro_login_message'] = ['type' => 'error', 'text' => __('No account found with that email.', 'pro-login-module')];
        wp_redirect(wp_get_referer());
        exit;
      }

      $user = get_user_by('email', $email);
      $reset_key = get_password_reset_key($user);

      $reset_link = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login), 'login');

      // Send reset email
      $subject = __('Password Reset Request', 'pro-login-module');
      $message = sprintf(__('Click here to reset your password: %s', 'pro-login-module'), $reset_link);

      wp_mail($user->user_email, $subject, $message);

      $_SESSION['pro_login_message'] = ['type' => 'success', 'text' => __('Password reset link has been sent to your email.', 'pro-login-module')];
      wp_redirect(wp_get_referer());
      exit;
    }
  }
}
