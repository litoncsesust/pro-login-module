<?php

namespace ProLogin\Frontend;

if (!defined('ABSPATH')) {
  exit;
}

class Register
{

  public function init()
  {
    add_shortcode('pro_register_form', [$this, 'render_register_form']);
    add_action('init', [$this, 'process_registration']);
  }

  public function render_register_form()
  {
    if (is_user_logged_in()) {
      return '<p>You are already registered and logged in.</p>';
    }

    ob_start();
    if (isset($_SESSION['pro_login_message'])) {
      $message = $_SESSION['pro_login_message'];
      echo '<div class="pro-login-message ' . esc_attr($message['type']) . '">' . esc_html($message['text']) . '</div>';
      unset($_SESSION['pro_login_message']);
    }
    include PRO_LOGIN_MODULE_PATH . 'templates/register-form.php';
    return ob_get_clean();
  }

  public function process_registration()
  {
    if (isset($_POST['pro_register_submit'])) {
      $email = sanitize_email($_POST['pro_register_email']);
      $password = sanitize_text_field($_POST['pro_register_password']);
      $confirm_password = sanitize_text_field($_POST['pro_register_confirm_password']);
      $name = sanitize_text_field($_POST['pro_register_name']);
      $gender = sanitize_text_field($_POST['pro_register_gender']);
      $country = sanitize_text_field($_POST['pro_register_country']);
      $phone = sanitize_text_field($_POST['pro_register_phone']);

      if (email_exists($email)) {
        $_SESSION['pro_login_message'] = ['type' => 'error', 'text' => 'Email already exists.'];
        wp_redirect(wp_get_referer());
        exit;
      }

      if ($password !== $confirm_password) {
        $_SESSION['pro_login_message'] = ['type' => 'error', 'text' => 'Passwords do not match.'];
        wp_redirect(wp_get_referer());
        exit;
      }

      $user_id = wp_create_user($email, $password, $email);
      wp_update_user(['ID' => $user_id, 'display_name' => $name]);

      update_user_meta($user_id, 'gender', $gender);
      update_user_meta($user_id, 'country', $country);
      update_user_meta($user_id, 'phone_number', $phone);

      // Email Verification
      $settings = get_option('pro_login_settings', []);
      if (!empty($settings['enable_email_verification'])) {
        update_user_meta($user_id, 'is_email_verified', false);
        $this->send_verification_email($user_id);
      } else {
        update_user_meta($user_id, 'is_email_verified', true);
      }

      $_SESSION['pro_login_message'] = ['type' => 'success', 'text' => 'Registration successful. Please verify your email.'];
      $redirect_url = !empty($settings['redirect_after_register']) ? esc_url($settings['redirect_after_register']) : home_url();
      wp_redirect($redirect_url);
      exit;
    }
  }

  private function send_verification_email($user_id)
  {
    $user = get_userdata($user_id);
    $verification_code = md5(time() . $user_id);
    update_user_meta($user_id, 'email_verification_code', $verification_code);

    $verification_link = add_query_arg([
      'verify_email' => $verification_code,
      'user_id' => $user_id
    ], home_url());

    wp_mail($user->user_email, 'Verify Your Email', 'Please verify your email by clicking this link: ' . $verification_link);
  }
}
