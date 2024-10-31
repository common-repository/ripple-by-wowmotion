<?php

require_once(dirname(__FILE__) . '/class.singleton.php');

class RippleBreadcrumbsAdmin extends Singleton
{

    const OPTION_NAME = 'ripple_breadcrumbs';

    /**
     * Allow to manipulate options for the admin page with ease
     */
    private $option_manager;

    /**
     * Start up
     */
    public function set_up()
    {
        // Rendering menu
        add_action( 'admin_menu', array( $this, 'render_menu' ) );

        // Registering action
        add_action( 'admin_init', array( $this, 'register_settings' ) );

        $this->option_manager = new AdminOptionManager(self::OPTION_NAME);
    }

    /**
     * Render admin menu
     */
    public function render_menu()
    {
        // This page will be under "Settings"
        add_submenu_page(
            "ripple_admin",
            __("Ripple breadcrumbs", RIPPLE_TR_DOMAIN),
            __("Breadcrumbs", RIPPLE_TR_DOMAIN),
            'manage_options',
            self::OPTION_NAME,
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
                <p>
                    <?php _e('Ripple ease the way to generate and display a breadcrumb inside your pages.', RIPPLE_TR_DOMAIN);?> <br />
                    <?php _e('The breadcrumbs will help your visitors for a better navigation and will improve your SEO performances.', RIPPLE_TR_DOMAIN);?>
                </p>
                <br />
                <?php _e('Activate this widget and you will be able to use it in two different ways :', RIPPLE_TR_DOMAIN);?>
                <ul>
                    <li><?php _e('By activating configuring the automatic display', RIPPLE_TR_DOMAIN);?></li>
                    <li>
                        <?php
                        _e('By using the shortcode', RIPPLE_TR_DOMAIN);

                        $help_title = 'Breadcrumbs widget shortcode';
                        $help_content = RippleAdmin::load_partial(RIPPLE_DIR_PATH . '/admin/partials/_help_breadcrumbs_widget_shortcode.html.php');
                        ?>
                        <a href="#" class="help-link swal-help-link" data-title="<?php echo $help_title; ?>" data-content="<?php echo htmlspecialchars($help_content) ?>">
                            <i class="fa fa-question-circle"></i>
                            <?php _e('Learn how to use the shortcode', RIPPLE_TR_DOMAIN);?>
                        </a>
                    </li>
                </ul>
            </div>

            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( self::OPTION_NAME );
                ?>

                <section class="ripple-admin-section">
                    <h2 class="ripple-section-title">
                        <i class="fa fa-cube" aria-hidden="true"></i>
                        <?php _e('General settings', RIPPLE_TR_DOMAIN) ?>
                    </h2>

                    <div class="activation-option">
                        <?php
                        $checked   = checked(1, $this->option_manager->option_value("activated"), false);
                        $html_name = $this->option_manager->html_name("activated");
                        ?>
                        <div class="pretty plain toggle">
                            <input type="checkbox" value="1" class="ripple-activate-widget" data-toggle="breadcrumbs-toggle-form" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                            <label><i class="fa fa-toggle-off danger"></i></label>
                            <label><i class="fa fa-toggle-on success"></i></label>
                        </div>
                    </div>
                    <div class="ripple-section-intro">
                        <strong><?php _e('Notice for shortcode', RIPPLE_TR_DOMAIN); ?></strong> <br />
                        <?php _e('Be aware that when using the shortcode, if you do not parametrize it, the default value considered will be the set of options below', RIPPLE_TR_DOMAIN); ?>
                    </div>
                    <div id="breadcrumbs-toggle-form" class="toggle-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <?php
                                    $checked   = checked(1, $this->option_manager->option_value("automatic_display"), false);
                                    $html_name = $this->option_manager->html_name("automatic_display");
                                    ?>
                                    <div class="pretty plain toggle">
                                        <input type="checkbox" value="1" id="automatic_display" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                                        <label><i class="fa fa-toggle-off danger"></i></label>
                                        <label><i class="fa fa-toggle-on success"></i></label>
                                    </div>
                                </th>
                                <td>
                                    <label for="display_home_link"><?php _e("Automatic display", RIPPLE_TR_DOMAIN); ?></label>
                                    <br />
                                    <em><?php _e("If enabled, Ripple will automatically display the breadcrumbs on each page before its content. If disabled you can display the breadcrumbs using the shortcode.", RIPPLE_TR_DOMAIN); ?></em>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="separator"><?php _e("Breadcrumbs separator", RIPPLE_TR_DOMAIN); ?></label>
                                </th>
                                <td>
                                    <?php
                                    $html_name = $this->option_manager->html_name("separator");
                                    $value     = $this->option_manager->option_value("separator");
                                    ?>
                                    <input type="text" id="separator" name="<?php echo $html_name; ?>" value="<?php echo $value; ?>" autocomplete="off" size="50" />
                                    <br />
                                    <label for="separator"><?php _e("Will appear between each link of the breadcrumb. The value can be text or HTML.", RIPPLE_TR_DOMAIN); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php
                                    $checked   = checked(1, $this->option_manager->option_value("display_home_link"), false);
                                    $html_name = $this->option_manager->html_name("display_home_link");
                                    ?>
                                    <div class="pretty plain toggle">
                                        <input type="checkbox" value="1" id="display_home_link" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                                        <label><i class="fa fa-toggle-off danger"></i></label>
                                        <label><i class="fa fa-toggle-on success"></i></label>
                                    </div>
                                </th>
                                <td>
                                    <label for="display_home_link"><?php _e("Display home link", RIPPLE_TR_DOMAIN); ?></label>
                                    <br />
                                    <em><?php _e("If enabled, Ripple will automatically add the homepage link as the first level element of the breadcrumbs.", RIPPLE_TR_DOMAIN); ?></em>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="widget_title_tag"><?php _e("Homepage link text", RIPPLE_TR_DOMAIN); ?></label>
                                </th>
                                <td>
                                    <?php
                                    $html_name     = $this->option_manager->html_name("home_link_text");
                                    $value         = $this->option_manager->option_value("home_link_text");
                                    ?>
                                    <input type="text" id="separator" name="<?php echo $html_name; ?>" value="<?php echo $value; ?>" autocomplete="off" />
                                    <br />
                                    <label for="separator"><?php _e("Text used to display the homepage link", RIPPLE_TR_DOMAIN); ?></label>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <section class="ripple-admin-action">
                        <button class="ripple-button" type="submit"><?php _e("Save settings", RIPPLE_TR_DOMAIN); ?></button>
                    </section>

                </section>


                <?php
                // For Javascript
                $js_data = array('module_name' => self::OPTION_NAME);
                ?>
                <input type="hidden" id="js-data" value="<?php echo htmlspecialchars(json_encode($js_data));?>" />
            </form>

        </div>
        <?php

    }

    /**
     * Register the settings
     */
    public function register_settings()
    {
        // Register global settings for all options
        register_setting(
            self::OPTION_NAME,
            self::OPTION_NAME,
            array( $this, 'sanitize' )
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param  array $input Contains all settings fields as array keys
     * @return array
     */
    public function sanitize( $input )
    {

        $new_input = array();

        $new_input['activated']         = isset($input['activated']); // Boolean settings
        $new_input['automatic_display'] = isset($input['automatic_display']); // Boolean settings

        // Separator can be text or HTML
        if( isset( $input['separator'] ) ) {
            $new_input['separator'] = esc_textarea($input['separator']);
        }

        $new_input['display_home_link'] = isset($input['display_home_link']); // Boolean settings

        if( isset( $input['home_link_text'] ) ) {
            $new_input['home_link_text'] = sanitize_text_field($input['home_link_text']);
        }


        return $new_input;
    }
}
