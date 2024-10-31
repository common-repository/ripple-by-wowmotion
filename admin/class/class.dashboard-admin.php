<?php

require_once(dirname(__FILE__) . '/class.singleton.php');

class RippleDashboardAdmin extends Singleton
{

    const OPTION_NAME = 'ripple_admin';

    /**
     * @var AdminOptionManager
     */
    private $option_manager;

    /**
     * Start up
     */
    public function set_up()
    {
        $this->option_manager = new AdminOptionManager(self::OPTION_NAME);

        // Render the menu
        add_action("admin_menu", [$this, "render_menu"]);

        // Enqueue module script
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Register Ajax action
        $this->register_ajax_actions();

    }


    public function enqueue_scripts(){
        if ( isset($_GET['page']) && $_GET['page'] == self::OPTION_NAME)
        {
            // Style
            wp_register_style('ripple-dashboard-style', RIPPLE_DIR_URL . 'admin/css/dashboard.css');
            wp_enqueue_style('ripple-dashboard-style');

            // scripts
            wp_enqueue_script('ripple-dashboard-script', RIPPLE_DIR_URL . 'admin/js/dashboard.js', array('jquery'), RIPPLE_VERSION, true);
            wp_localize_script('ripple-dashboard-script', 'ajax_object', [
                'ajax_url' => admin_url('admin-ajax.php')
            ]);
        }
    }

    /**
     * The dashboard page uses Ajax to register data.
     * This method register every action that is required by the Ajax requests
     */
    public function register_ajax_actions()
    {
        // This action allow to
        add_action( 'wp_ajax_update_post_type_option', [$this, 'update_post_type_option']);
    }


    /**
     * This function is an Ajax action
     * It allows to store options relative to post type
     */
    public function update_post_type_option()
    {
        // Validate user rights
        if ( !current_user_can( 'manage_options' )  )
        {
            $error = [
                "error_code" => "user_cant",
                "error_title"  => __("Permission error", RIPPLE_TR_DOMAIN),
                "error_msg"  => __("You do not have the permissions to perform this action, please contact the administrator of your website to solve the issue.", RIPPLE_TR_DOMAIN)
            ];
            wp_send_json_error($error, 500);
        }

        // Checking if all required data are there
        if( isset($_POST["post_type_name"]) )
        {

            // Validating nonce
            if( !$this->has_valid_nonce('ripple-dashboard-save', 'nonce') ){
                $error = [
                    "error_code"  => "invalid_nonce",
                    "error_title" => __("Invalid nonce", RIPPLE_TR_DOMAIN),
                    "error_msg"   => __("You should refresh the page and try again", RIPPLE_TR_DOMAIN)
                ];
                wp_send_json_error($error, 500);
            }


            // Converting the value received as a string as a Boolean
            $value = ($_POST["value"] == "true") ? true : false;

            // Build the value to be store inside the WP option reserved for the dashboard
            $value_to_store = [
                "post_type" => [
                    $_POST["post_type_name"] => [
                        $_POST["attribute"] => $value
                    ]
                ]
            ];

            // Store it
            $result = $this->option_manager->store_option($value_to_store);

            // Send back the result of the request
            if ($result) {
                wp_send_json_success();
            }
            else {
                $error = [
                    "error_code" => "unknown_error",
                    "error_title"  => __("Something went wrong", RIPPLE_TR_DOMAIN),
                    "error_msg"  => __("Please try to refresh the page before trying again or contact us if the problem persists.", RIPPLE_TR_DOMAIN)
                ];
                wp_send_json_error($error, 500);
            }
        }
        else{
            $error = [
                "error_code" => "missing_data",
                "error_title"  => __("Something went wrong", RIPPLE_TR_DOMAIN),
                "error_msg"  => __("It seems that some data are missing to perform the action. Please try to refresh the page before trying again or contact us if the problem persists.", RIPPLE_TR_DOMAIN)
            ];
            wp_send_json_error($error, 500);
        }

        // Stopping execution afterward : https://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
        wp_die();
    }




    private function has_valid_nonce($action, $nonce_name)
    {
        // If the field isn't even in the $_POST, then it's invalid.
        if ( ! isset( $_POST[$nonce_name] ) ) { // Input var okay.
            return false;
        }

        $field  = wp_unslash( $_POST[$nonce_name] );
        return wp_verify_nonce( $field, $action );
    }


    /**
     * Render admin menu
     */
    public function render_menu()
    {
        // This page will be under "Settings"
        add_submenu_page(
            "ripple_admin",
            __("Ripple Dashboard", RIPPLE_TR_DOMAIN),
            __("Dashboard", RIPPLE_TR_DOMAIN),
            'manage_options',
            'ripple_admin', // Change this for 'self::OPTION_NAME' to have a separate page available for the plugin
            array( $this, 'render_page' )
        );
    }


    /**
     * Render admin page
     */
    public function render_page()
    {
        ?>
        <div id="ripple-admin-wrapper" class="wrap">
            <h1 class="ripple-admin-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <div class="ripple-admin-page-intro">
                <p><?php _e('Ripple helps you to manage some configurations of your post types.', RIPPLE_TR_DOMAIN);?></p>

                <p><?php _e('Depending on how they are configured, you may be limited using the different Ripple widgets :', RIPPLE_TR_DOMAIN);?></p>

                <ul>
                    <li><?php _e('The "Semantic related content widget" works with post types carrying taxonomies (categories, tags, custom taxonomies...)', RIPPLE_TR_DOMAIN);?></li>
                    <li><?php _e('The "Hierarchical related content widget" only works with "hierarchical" post types', RIPPLE_TR_DOMAIN);?></li>
                    <li><?php _e('The "Breadcrumbs widget" works better with "hierarchical" post types', RIPPLE_TR_DOMAIN);?></li>
                </ul>

            </div>

            <section class="ripple-admin-section">
                <h2 class="ripple-section-title">
                    <i class="fa fa-cube" aria-hidden="true"></i>
                    <?php _e('Post types configuration', RIPPLE_TR_DOMAIN) ?>
                </h2>

                <div class="ripple-section-intro">
                    <?php _e('Ripple only offer you the minimal required features to manage your post types, but you will probably feel limited about that.', RIPPLE_TR_DOMAIN);?> <br />
                    <?php _e('In that case, consider using a popular plugin as <strong>CPT UI</strong> which will offer much more features : ', RIPPLE_TR_DOMAIN);?>
                    <?php $cpt_ui_link_text = __("Visite CPT UI plugin page", RIPPLE_TR_DOMAIN); ?>
                    <a class="help-link" href="https://fr.wordpress.org/plugins/custom-post-type-ui/" target="blank" title="<?php echo $cpt_ui_link_text ?>">
                        <i class="fa fa-external-link"></i>&nbsp;<?php echo $cpt_ui_link_text ?>
                    </a>
                </div>

                <div class="ripple-section-content">

                    <div id="ripple-post-type-form-container">
                        <ul>
                            <?php
                            $editable_post_types = RippleHelper::get_editable_post_type();
                            foreach($editable_post_types as $post_type_object) {
                                ?>
                                <li><a href="<?php echo "#tabs-{$post_type_object->name}"; ?>"><strong><i class="fa fa-angle-double-left"></i>&nbsp;<?php echo $post_type_object->labels->name; ?>&nbsp;<i class="fa fa-angle-double-right"></i></strong></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                        foreach($editable_post_types as $post_type_object)
                        {
                            ?>
                            <div id="<?php echo "tabs-{$post_type_object->name}"; ?>" class="ripple-post-type">

                                <form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
                                    <ul>
                                        <li>
                                            <?php
                                            $field_name = "activate_excerpt";
                                            $current_value = $this->option_manager->option_value("post_type", $post_type_object->name, $field_name);
                                            $field_id = $this->option_manager->html_name($post_type_object->name, $field_name);
                                            $checked = checked(true, $current_value, false);
                                            ?>
                                            <span class="checkbox-container">
                                                <input id="<?php echo $field_id; ?>" type="checkbox" value="1" name="<?php echo $field_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                                            </span>
                                            <label for="<?php echo $field_id ?>"><?php _e("Activate the « Excerpt » field (can then be displayed by Ripple Widgets)", RIPPLE_TR_DOMAIN); ?></label>
                                        </li>
                                        <?php
                                        $field_id = $this->option_manager->html_name($post_type_object->name, "hierarchical");
                                        $checked = checked(true, $post_type_object->hierarchical, false);
                                        ?>
                                        <li>
                                            <span class="checkbox-container">
                                                <input id="<?php echo $field_id; ?>" type="checkbox" value="1" name="hierarchical" <?php echo $checked; ?> autocomplete="off"/>
                                            </span>
                                            <label for="<?php echo $field_id ?>">Make the post type hierarchical</label>
                                        </li>

                                        <?php
                                        $pt_taxonomies = RippleHelper::get_editable_taxonomies_for_post_type($post_type_object->name);
                                        foreach($pt_taxonomies as $tax_name => $tax_data)
                                        {
                                            $field_id = $this->option_manager->html_name($post_type_object->name, $tax_name);
                                            $checked = checked(true, is_object_in_taxonomy($post_type_object->name, $tax_name), false);
                                            ?>
                                            <li>
                                                <span class="checkbox-container">
                                                    <input id="<?php echo $field_id; ?>" type="checkbox" value="1" name="<?php echo $tax_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                                                </span>
                                                <label for="<?php echo $field_id ?>"><?php echo $tax_data["label"]; ?></label>

                                            </li>
                                            <?php
                                        }
                                        ?>

                                        <li>
                                            <?php
                                            $field_name = "activate_origin_url_field";
                                            $current_value = $this->option_manager->option_value("post_type", $post_type_object->name, $field_name);
                                            $field_id = $this->option_manager->html_name($post_type_object->name, $field_name);
                                            $checked = checked(true, $current_value, false);
                                            ?>
                                            <span class="checkbox-container">
                                                <input id="<?php echo $field_id; ?>" type="checkbox" value="1" name="<?php echo $field_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                                            </span>
                                            <label for="<?php echo $field_id ?>"><?php _e("When adding/editing a post of this type, I want to be able to specify an external URL", RIPPLE_TR_DOMAIN); ?></label>
                                        </li>


                                        <input type='hidden' name='action' value='update_post_type_option' /> <!-- required for ajax -->
                                        <input type='hidden' name='post_type_name' value='<?php echo $post_type_object->name ?>' />
                                    </ul>
                                    <?php
                                    $nonce_name = $post_type_object->name."['nonce']";
                                    wp_nonce_field( 'ripple-dashboard-save', $nonce_name);
                                    ?>
                                </form>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </section>


        </div>
        <?php

    }

}
