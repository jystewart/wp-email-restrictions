<?php 
/*
Plugin Name: WP Email Restrictions
Plugin URI: http://www.ketlai.co.uk
Description: Whitelist domain names from which signups are permitted
Author: James Stewart
Version: 0.1
Author URI: http://jystewart.net/process/
*/

function wp_email_restrictions_restrict($user_login, &$user_email, &$errors) {
  $user_email = urldecode($user_email);
  list($username, $domain) = split('@', $user_email); 
  $whitelist = get_option('email_restrictions_domain_list');
  if (empty($whitelist)) {
    $whitelist = array('dhl.com');
    update_option('email_restrictions_domain_list', $whitelist, '', 'no');
  }
  if (array_search(strtolower($domain), $whitelist) === FALSE) {
    $errors->add('invalid_email', __('<strong>ERROR</strong>: Only email addresses from approved domains allowed'),
      array('form-field' => 'email'));
    $user_email = '';
  }
}

function wp_email_restrictions_page() {
  if (isset($_POST['update_options'])) {
    $option_input = preg_replace("/\r\n/", "\n", trim($_POST['options_domains']));
    $options = array('domains' => explode("\n", $option_input));
    update_option('email_restrictions_domain_list', $options['domains']);
    echo '<div class="updated"><p>' . __('Options saved') . '</p></div>';
  } else {
    $options = array('domains' => get_option('email_restrictions_domain_list'));
  }
  
  include 'admin_page.tpl.php';
}

function wp_email_restrictions_menu() {
  global $user_level;
  get_currentuserinfo();
  if ($user_level < 10) return;

  if (function_exists('add_options_page')) {
    add_options_page(__('Email Restrictions'), __('Email Restrictions'), 1, __FILE__, 'wp_email_restrictions_page');
  }
}

function wp_email_restrictions_activate() {
  add_option('email_restrictions_domain_list', array('example.com'), '', 'no');
}
  
register_activation_hook(__FILE__, 'wp_email_restrictions_activate');
add_action('admin_menu', 'wp_email_restrictions_menu'); 
add_action('register_post', 'wp_email_restrictions_restrict', 10, 3);

if (! defined('PHP_VERSION_ID')) {
  $version = explode('.', PHP_VERSION);
  define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

if (PHP_VERSION_ID < 50207) {
  define('PHP_MAJOR_VERSION', $version[0]);
  define('PHP_MINOR_VERSION',   $version[1]);
  define('PHP_RELEASE_VERSION', $version[2]);
}

if (PHP_MAJOR_VERSION < 5) {
  function countdown_to_version_warning() {
    echo "<div id='email-restrictions-warning' class='updated fade'>";
    echo "<p><strong>" . 
      __('WP Email Restrictions To is only tested on PHP5.2 and above. You are running PHP4 so the plugin may not work correctly') . 
      "</strong></p>";
    echo "</div>";
  }
  add_action('admin_notices', 'countdown_to_version_warning');
}