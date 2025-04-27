<form id="pro-login-ajax-form" class="pro-login-form">
  <input type="email" name="pro_login_email" placeholder="Email" required>
  <input type="password" name="pro_login_password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>

<script>
  jQuery(function($) {
    $('#pro-login-ajax-form').submit(function(e) {
      e.preventDefault();

      var data = {
        action: 'pro_login_ajax',
        security: '<?php echo wp_create_nonce("pro_login_nonce"); ?>',
        email: $('input[name=\"pro_login_email\"]').val(),
        password: $('input[name=\"pro_login_password\"]').val()
      };

      $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
        if (response.success) {
          window.location.href = response.data.redirect;
        } else {
          alert(response.data.message);
        }
      });
    });
  });
</script>