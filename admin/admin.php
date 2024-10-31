<?php

require_once(RIPPLE_DIR_PATH . '/admin/class/class.admin-options-manager.php');

require_once(RIPPLE_DIR_PATH . '/admin/class/class.admin-initializer.php');
require_once(RIPPLE_DIR_PATH . '/admin/class/class.dashboard-admin.php');
require_once(RIPPLE_DIR_PATH . '/admin/class/class.breadcrumbs-admin.php');
require_once(RIPPLE_DIR_PATH . '/admin/class/class.semantic-related-content-admin.php');
require_once(RIPPLE_DIR_PATH . '/admin/class/class.hierarchical-related-content-admin.php');
require_once(RIPPLE_DIR_PATH . '/admin/class/class.hierarchical-related-content-admin.php');
/**
 * The RippleAdmin is intended to initialize every admin module of the plugin
 */
class RippleAdmin
{
    /**
     * Start up
     * - Registering script needed for Ripple module to work
     * - Creating menu
     */
    public function __construct()
    {

        // Ripple admin script management
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Settings menu creation
        add_action( 'admin_menu', array( $this, 'render_menu' ) );

        // Initializing Admin interfaces
        $ripple_dashboard = RippleAdminInitializer::getInstance();
        $ripple_dashboard->set_up();

        // Initializing sub-modules
        $this->initialize_ripple_modules();
    }

    /**
     * Function called when activating the plugin
     */
    public static function ripple_on_activate()
    {
        // Running migration before defining default
        $migration = RippleMigration::getInstance();
        $migration->migrate();
    }

    /**
     * Function called when deactivating the plugin
     */
    public static function ripple_on_deactivate(){}

    /**
     * Global admin style and scripts (used by every plugin module/widget)
     */
    public function enqueue_scripts()
    {
        // Style that may be used to custom element in Wordpress admin page (not ripple plugin pages)
        wp_register_style('ripple-wordpress-admin-style', RIPPLE_DIR_URL . 'admin/css/wordpress-admin.css');
        wp_enqueue_style('ripple-wordpress-admin-style');

        // Load the scripts on only the plugin admin page
        if ($this->is_plugin_page())
        {
            /**
             * Vendors
             */
            // jQuery-ui components
            wp_enqueue_script('jquery-ui-tabs');
            wp_register_style('jquery-ui-tabs', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', [], RIPPLE_VERSION);
            wp_enqueue_style('jquery-ui-tabs');

            // Fontawesome
            wp_register_style('fontawesome', 'https://use.fontawesome.com/releases/v5.7.2/css/all.css');
            wp_enqueue_style('fontawesome');

            // Pretty checkbox
            // TODO : migrate to latest version
            wp_register_style('pretty-checkbox', "https://cdnjs.cloudflare.com/ajax/libs/pretty-checkbox/2.2.1/pretty.min.css");
            wp_enqueue_style('pretty-checkbox');

            // Sweetalert
            wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2', array(), RIPPLE_VERSION, true);

            /**
             * Ripple
             */
            wp_register_style('ripple-admin-style', RIPPLE_DIR_URL . 'admin/css/ripple-admin.css');
            wp_enqueue_style('ripple-admin-style');

            wp_enqueue_script('ripple-admin-script', RIPPLE_DIR_URL . 'admin/js/admin.js', array('jquery'), RIPPLE_VERSION, true);

        }
    }

    /**
     * The function return true if the current page belong to the plugin (false otherwise)
     * @return bool
     */
    private function is_plugin_page()
    {
        // Get the sub-modules identifier which are useful to detect if the current admin page belong to the plugin or not
        $plugin_page_slugs = [
            RippleDashboardAdmin::OPTION_NAME,
            RippleBreadcrumbsAdmin::OPTION_NAME,
            RippleSemanticRelatedContentAdmin::OPTION_NAME,
            RippleHierarchicalRelatedContentAdmin::OPTION_NAME,
        ];

        // Load the scripts on only the plugin admin page
        return (isset($_GET['page']) && (in_array($_GET['page'], $plugin_page_slugs))) ;

    }

    /**
     * Add dashboard page page
     */
    public function render_menu()
    {
        // Creating a first level menu entry
        add_menu_page( 
            __("Ripple admin settings", RIPPLE_TR_DOMAIN),
            __("Ripple", RIPPLE_TR_DOMAIN),
            "manage_options",
            "ripple_admin" // This ID should be the same as the first page of the admin menu
        );
    }

    /**
     * Initializing ripple modules
     */
    public function initialize_ripple_modules()
    {
        $ripple_dashboard = RippleDashboardAdmin::getInstance();
        $ripple_dashboard->set_up();

        $ripple_related_content = RippleSemanticRelatedContentAdmin::getInstance();
        $ripple_related_content->set_up();

        $ripple_hierarchical_related_content = RippleHierarchicalRelatedContentAdmin::getInstance();
        $ripple_hierarchical_related_content->set_up();

        $ripple_breadcrumbs = RippleBreadcrumbsAdmin::getInstance();
        $ripple_breadcrumbs->set_up();

    }

    /**
     * This static method allow to interpret a PHP file dynamically, and return the generated content
     * @param $file_path
     * @return String - The Dynamic file content
     * TODO : move this to RippleHelper class
     */
    public static function load_partial($file_path)
    {
        ob_start(); // turn on output buffering
        include($file_path);
        $help_content = ob_get_contents(); // get the contents of the output buffer
        ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering
        return $help_content;
    }

}

