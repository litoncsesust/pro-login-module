<?php
$current_user = wp_get_current_user();
?>
<form method="post" class="pro-login-form">
  <input type="text" name="pro_profile_name" value="<?php echo esc_attr($current_user->display_name); ?>" placeholder="Name" required>
  <input type="text" name="pro_profile_gender" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'gender', true)); ?>" placeholder="Gender" required>
  <input type="text" name="pro_profile_country" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'country', true)); ?>" placeholder="Country" required>
  <input type="text" name="pro_profile_phone" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone_number', true)); ?>" placeholder="Phone Number" required>
  <button type="submit" name="pro_profile_submit">Update Profile</button>
</form>