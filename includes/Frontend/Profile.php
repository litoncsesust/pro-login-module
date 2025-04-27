<?php

namespace ProLogin\Frontend;

if (!defined('ABSPATH')) {
  exit;
}

class Profile
{

  public function init()
  {
    add_shortcode('pro_profile_form', [$this, 'render_profile_form']);
    add_action('init', [$this, 'process_profile_update']);
  }

  public function render_profile_form()
  {
    if (!is_user_logged_in()) {
      return '<p>You must be logged in to update your profile.</p>';
    }

    $current_user = wp_get_current_user();

    ob_start();
    if (isset($_SESSION['pro_login_message'])) {
      $message = $_SESSION['pro_login_message'];
      echo '<div class="pro-login-message ' . esc_attr($message['type']) . '">' . esc_html($message['text']) . '</div>';
      unset($_SESSION['pro_login_message']);
    }
    include PRO_LOGIN_MODULE_PATH . 'templates/profile-form.php';
    return ob_get_clean();
  }

  public function process_profile_update()
  {
    if (isset($_POST['pro_profile_submit']) && is_user_logged_in()) {
      $user_id = get_current_user_id();
      $name = sanitize_text_field($_POST['pro_profile_name']);
      $gender = sanitize_text_field($_POST['pro_profile_gender']);
      $country = sanitize_text_field($_POST['pro_profile_country']);
      $phone = sanitize_text_field($_POST['pro_profile_phone']);

      wp_update_user(['ID' => $user_id, 'display_name' => $name]);
      update_user_meta($user_id, 'gender', $gender);
      update_user_meta($user_id, 'country', $country);
      update_user_meta($user_id, 'phone_number', $phone);

      $_SESSION['pro_login_message'] = ['type' => 'success', 'text' => 'Profile updated successfully.'];

      $settings = get_option('pro_login_settings', []);
      $redirect_url = !empty($settings['redirect_after_profile_update']) ? esc_url($settings['redirect_after_profile_update']) : wp_get_referer();
      wp_redirect($redirect_url);
      exit;
    }
  }
}
