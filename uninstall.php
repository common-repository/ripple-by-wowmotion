<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/functions.php');

// Delete ripple options
$default_options = ripple_get_default_options();
foreach($default_options as $key => $value) {
    delete_option($key);
}