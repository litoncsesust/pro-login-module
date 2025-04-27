<?php

namespace ProLogin\Frontend;

if (!defined('ABSPATH')) {
  exit;
}

class Login
{

  public function init()
  {
    add_shortcode('pro_login_form', [$this, 'render_login_form']);
    add_action('init', [$this, 'process_login']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    add_action('wp_ajax_nopriv_pro_login_ajax', [$this, 'handle_ajax_login']);
    //add_action('wp_ajax_pro_login_ajax', [$this, 'handle_ajax_login']);

    add_action('template_redirect', function () {
      if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $verified = get_user_meta($user_id, 'is_email_verified', true);

        if (!$verified && !is_page('verify-email')) { // Replace with your page slug
          wp_redirect(home_url('/verify-email/'));
          exit;
        }
      }
    });
  }

  public function handle_ajax_login()
  {
    check_ajax_referer('pro_login_nonce', 'security');

    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);

    $user = wp_authenticate($email, $password);

    if (is_wp_error($user)) {
      wp_send_json_error(['message' => 'Login failed. Please check your credentials.']);
    }

    $settings = get_option('pro_login_settings', []);
    if (!empty($settings['enable_email_verification']) && !get_user_meta($user->ID, 'is_email_verified', true)) {
      wp_send_json_error(['message' => 'Please verify your email before logging in.']);
    }

    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);

    $redirect_url = !empty($settings['redirect_after_login']) ? esc_url($settings['redirect_after_login']) : home_url();
    wp_send_json_success(['redirect' => $redirect_url]);
  }


  public function enqueue_assets()
  {
    wp_enqueue_style('pro-login-style', PRO_LOGIN_MODULE_URL . 'assets/css/style.css', [], PRO_LOGIN_MODULE_VERSION);
  }

  public function render_login_form()
  {
    if (is_user_logged_in()) {
      return '<p>You are already logged in.</p>';
    }

    ob_start();
    if (isset($_SESSION['pro_login_message'])) {
      $message = $_SESSION['pro_login_message'];
      echo '<div class="pro-login-message ' . esc_attr($message['type']) . '">' . esc_html($message['text']) . '</div>';
      unset($_SESSION['pro_login_message']);
    }
    include PRO_LOGIN_MODULE_PATH . 'templates/login-form.php';
    return ob_get_clean();
  }

  public function process_login()
  {
    if (isset($_POST['pro_login_submit'])) {
      $email = sanitize_email($_POST['pro_login_email']);
      $password = sanitize_text_field($_POST['pro_login_password']);

      $user = wp_authenticate($email, $password);

      if (is_wp_error($user)) {
        $_SESSION['pro_login_message'] = ['type' => 'error', 'text' => 'Login failed. Please check your credentials.'];
        wp_redirect(wp_get_referer());
        exit;
      }

      $settings = get_option('pro_login_settings', []);
      $verify_enabled = !empty($settings['enable_email_verification']);

      if ($verify_enabled && !get_user_meta($user->ID, 'is_email_verified', true)) {
        $_SESSION['pro_login_message'] = ['type' => 'error', 'text' => 'Please verify your email before logging in.'];
        wp_redirect(wp_get_referer());
        exit;
      }

      wp_set_current_user($user->ID);
      wp_set_auth_cookie($user->ID);

      $_SESSION['pro_login_message'] = ['type' => 'success', 'text' => 'Login successful!'];

      $redirect_url = !empty($settings['redirect_after_login']) ? esc_url($settings['redirect_after_login']) : home_url();
      wp_redirect($redirect_url);
      exit;
    }
  }
}
