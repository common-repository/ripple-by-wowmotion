<?php
/**
Plugin Name: Ripple
Description: Generate more traffic and time spent on your website by inviting users to read similar contents
Version: 1.5.5
Author: Christophe Laborier
Author URI:
License: GPL2
 */

/*************************************
 * PLUGIN GLOBAL RESOURCES
 ************************************/

require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/functions.php');

require_once(dirname(__FILE__) . '/includes/class.ripple-helper.php'); // Helper function
require_once(dirname(__FILE__) . '/includes/class.ripple-middleware.php'); // Wordpress pre-configuration depending on Ripple settings

require_once(dirname(__FILE__) . '/admin/class/class.migration.php'); // Used to migrate the DB

require_once(dirname(__FILE__) . '/includes/class.ripple.php'); // Front end manager
require_once(dirname(__FILE__) . '/admin/admin.php'); // Admin Manager

// Widgets class
require_once(dirname(__FILE__) . '/includes/class.semantic-related-content-widget.php');
require_once(dirname(__FILE__) . '/includes/class.hierarchical-related-content-widget.php');
require_once(dirname(__FILE__) . '/includes/class.breadcrumbs-widget.php');


// Activate the Ripple Middleware to configure Worpdress depending on Ripple options
// This activation is required both for front and admin page
RippleMiddleware::configure_wordpress();



if(!is_admin()) {
    // Initialize Ripple for the front pages
    new RippleFrontEnd();
}
else{
    // Initialize the Ripple admin panels
    new RippleAdmin();

    register_activation_hook  (__FILE__, "RippleAdmin::ripple_on_activate" );
    register_deactivation_hook(__FILE__, "RippleAdmin::'ripple_on_deactivate");
}
