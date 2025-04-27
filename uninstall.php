<?php

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

// Delete the plugin options
delete_option('pro_login_settings');

// Delete user meta data (optional, safer to clean)
$users = get_users([
  'fields' => 'ID',
]);

foreach ($users as $user_id) {
  delete_user_meta($user_id, 'gender');
  delete_user_meta($user_id, 'country');
  delete_user_meta($user_id, 'phone_number');
  delete_user_meta($user_id, 'is_email_verified');
  delete_user_meta($user_id, 'email_verification_code');
}
