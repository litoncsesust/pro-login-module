<?php

namespace ProLogin\Admin;

if (!defined('ABSPATH')) {
  exit;
}

class Admin
{

  private $option_name = 'pro_login_settings';

  public function init()
  {
    add_action('admin_menu', [$this, 'add_settings_page']);
    add_action('admin_init', [$this, 'register_settings']);
    update_option('pro_login_module_do_activation_redirect', true);
  }

  public function redirect_after_activation()
  {
    if (get_option('pro_login_module_do_activation_redirect', false)) {
      delete_option('pro_login_module_do_activation_redirect');
      if (!isset($_GET['activate-multi'])) {
        wp_safe_redirect(admin_url('options-general.php?page=pro-login-module'));
        exit;
      }
    }
  }


  public function add_settings_page()
  {
    add_options_page(
      'Pro Login Module Settings',
      'Pro Login Module',
      'manage_options',
      'pro-login-module',
      [$this, 'render_settings_page']
    );
  }

  public function register_settings()
  {
    register_setting($this->option_name, $this->option_name, [$this, 'sanitize']);

    // General Settings Section
    add_settings_section(
      'pro_login_general_section',
      'General Settings',
      null,
      'pro-login-general'
    );

    add_settings_field(
      'enable_email_verification',
      'Enable Email Verification',
      [$this, 'checkbox_field'],
      'pro-login-general',
      'pro_login_general_section',
      [
        'label_for' => 'enable_email_verification',
        'option_name' => $this->option_name,
        'field' => 'enable_email_verification'
      ]
    );

    // Redirection Settings Section
    add_settings_section(
      'pro_login_redirection_section',
      'Redirection Settings',
      null,
      'pro-login-redirection'
    );

    $fields = [
      'redirect_after_login' => 'Redirect After Login URL',
      'redirect_after_register' => 'Redirect After Register URL',
      'redirect_after_profile_update' => 'Redirect After Profile Update URL'
    ];

    foreach ($fields as $field => $label) {
      add_settings_field(
        $field,
        $label,
        [$this, 'text_field'],
        'pro-login-redirection',
        'pro_login_redirection_section',
        [
          'label_for' => $field,
          'option_name' => $this->option_name,
          'field' => $field
        ]
      );
    }
  }

  public function sanitize($input)
  {
    $sanitized = [];
    $sanitized['enable_email_verification'] = isset($input['enable_email_verification']) ? 1 : 0;
    $sanitized['redirect_after_login'] = isset($input['redirect_after_login']) ? esc_url_raw($input['redirect_after_login']) : '';
    $sanitized['redirect_after_register'] = isset($input['redirect_after_register']) ? esc_url_raw($input['redirect_after_register']) : '';
    $sanitized['redirect_after_profile_update'] = isset($input['redirect_after_profile_update']) ? esc_url_raw($input['redirect_after_profile_update']) : '';

    return $sanitized;
  }

  public function render_settings_page()
  {
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
?>
    <div class="wrap">
      <h1>Pro Login Module Settings</h1>

      <h2 class="nav-tab-wrapper">
        <a href="?page=pro-login-module&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
        <a href="?page=pro-login-module&tab=redirection" class="nav-tab <?php echo $active_tab == 'redirection' ? 'nav-tab-active' : ''; ?>">Redirection</a>
      </h2>

      <form method="post" action="options.php">
        <?php
        settings_fields($this->option_name);

        if ($active_tab == 'general') {
          do_settings_sections('pro-login-general');
        } elseif ($active_tab == 'redirection') {
          do_settings_sections('pro-login-redirection');
        }

        submit_button();
        ?>
      </form>
    </div>
<?php
  }

  public function checkbox_field($args)
  {
    $options = get_option($args['option_name']);
    $checked = isset($options[$args['field']]) ? checked(1, $options[$args['field']], false) : '';
    echo '<input type="checkbox" id="' . esc_attr($args['field']) . '" name="' . esc_attr($args['option_name']) . '[' . esc_attr($args['field']) . ']" value="1" ' . $checked . '>';
  }

  public function text_field($args)
  {
    $options = get_option($args['option_name']);
    $value = isset($options[$args['field']]) ? esc_attr($options[$args['field']]) : '';
    echo '<input type="text" id="' . esc_attr($args['field']) . '" name="' . esc_attr($args['option_name']) . '[' . esc_attr($args['field']) . ']" value="' . $value . '" class="regular-text">';
  }
}
