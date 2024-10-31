<?php

require_once(dirname(__FILE__) . '/class.singleton.php');

class RippleHierarchicalRelatedContentAdmin extends Singleton
{

    const OPTION_NAME = 'ripple_hierarchical_related_content';

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

        // Registering options
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
            __("Ripple hierarchical related content", RIPPLE_TR_DOMAIN),
            __("Hierarchical related content", RIPPLE_TR_DOMAIN),
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
                <?php _e('Ripple displays related content below each of your page to keep your visitor reading on your awesome website !', RIPPLE_TR_DOMAIN);?>
                <br /><br />
                <?php _e('The hierarchical related content is found by searching for children content.', RIPPLE_TR_DOMAIN);?>
                <br /><br /><br />
                <?php _e('Activate this widget and you will be able to use it in two different ways :', RIPPLE_TR_DOMAIN);?>
                <ul>
                    <li><?php _e('By activating configuring the automatic display', RIPPLE_TR_DOMAIN);?></li>
                    <li>
                        <?php
                        _e('By using the shortcode', RIPPLE_TR_DOMAIN);

                        $help_title = 'Hierarchical widget shortcode';
                        $help_content = RippleAdmin::load_partial(RIPPLE_DIR_PATH . '/admin/partials/_help_hierarchical_widget_shortcode.html.php');
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
                            <input type="checkbox" value="1" class="ripple-activate-widget" data-toggle="ripple-hrc-toggle-form" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                            <label><i class="fa fa-toggle-off danger"></i></label>
                            <label><i class="fa fa-toggle-on success"></i></label>
                        </div>
                    </div>
                    <div class="ripple-section-intro">
                        <?php _e('Activate this widget to display hierarchical related content right after the content of your pages.', RIPPLE_TR_DOMAIN); ?>
                    </div>
                    <div id="ripple-hrc-toggle-form" class="toggle-form hidden">
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
                                    <em><?php _e("If enabled, Ripple will automatically display the widget on each post right after the post content. If disabled you can display the widget using the shortcode.", RIPPLE_TR_DOMAIN); ?></em>
                                </td>
                            </tr>
                        </table>


                        <h3>
                            <i class="fas fa-cog"></i>
                            <?php _e("Widget global settings", RIPPLE_TR_DOMAIN); ?>
                        </h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="widget_title"><?php _e("Widget title", RIPPLE_TR_DOMAIN); ?></label>
                                </th>
                                <td>
                                    <?php
                                    $html_name = $this->option_manager->html_name("widget_title");
                                    $value     = $this->option_manager->option_value("widget_title");
                                    ?>
                                    <input type="text" id="widget_title" name="<?php echo $html_name; ?>" value="<?php echo $value; ?>" autocomplete="off" />
                                    <br />
                                    <label for="widget_title"><?php _e("Will appear just before the related content list", RIPPLE_TR_DOMAIN); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="widget_title_tag"><?php _e("Widget title HTML tag", RIPPLE_TR_DOMAIN); ?></label>
                                </th>
                                <td>
                                    <?php
                                    $html_name     = $this->option_manager->html_name("widget_title_tag");
                                    $value         = $this->option_manager->option_value("widget_title_tag");
                                    $available_tag = array("h2", "h3", "h4", "h5", "strong", "span");
                                    ?>
                                    <select id="widget_title_tag" name="<?php echo $html_name; ?>">
                                        <option value="">--</option>
                                        <?php
                                        foreach($available_tag as $tag){
                                            $selected = selected( $value, $tag );
                                            echo "<option value='{$tag}' {$selected}>{$tag}</option>";
                                        }
                                        ?>
                                    </select>
                                    <br />
                                    <label for="widget_title_tag"><?php _e("Choose the more appropriate tag so Ripple can be integrated seamlessly in your theme", RIPPLE_TR_DOMAIN); ?></label>
                                </td>
                            </tr>
                        </table>
                        <h3>
                            <i class="fas fa-cog"></i>
                            <?php _e("Related content options", RIPPLE_TR_DOMAIN); ?>
                        </h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="item_title_tag"><?php _e("Item title HTML tag", RIPPLE_TR_DOMAIN); ?></label>
                                </th>
                                <td>
                                    <?php
                                    $html_name     = $this->option_manager->html_name("item_title_tag");
                                    $value         = $this->option_manager->option_value("item_title_tag");
                                    $available_tag = array("h2", "h3", "h4", "h5", "strong", "span");
                                    ?>
                                    <select id="item_title_tag" name="<?php echo $html_name; ?>">
                                        <?php
                                        foreach($available_tag as $tag){
                                            $selected = selected( $value, $tag );
                                            echo "<option value='{$tag}' {$selected}>{$tag}</option>";
                                        }
                                        ?>
                                    </select>
                                    <br />
                                    <label for="item_title_tag"><?php _e("This tag is used to wrap every post title of each related content item displayed in the widget", RIPPLE_TR_DOMAIN); ?></label>
                                </td>
                            </tr>

                            <?php include(RIPPLE_DIR_PATH."/admin/partials/_subform_excerpt.html.php"); ?>

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

        // Option 'Activated' : activate / deactivate the widget
        $new_input['activated'] = isset($input['activated']); // Boolean settings

        // Option "automatic_display" : let the plugin manage the display of the widget automatically or not
        $new_input['automatic_display'] = isset($input['automatic_display']); // Boolean settings

        if( isset( $input['widget_title'] ) ) {
            $new_input['widget_title'] = sanitize_text_field($input['widget_title']);
        }

        if( isset( $input['widget_title_tag'] ) ) {
            $new_input['widget_title_tag'] = sanitize_text_field($input['widget_title_tag']);
        }

        // "clickable_item" :
        $new_input['clickable_item'] = isset($input['clickable_item']); // Boolean settings

        // "rel" option can have an empty value
        $new_input['rel'] = sanitize_text_field($input['rel']);

        if( isset( $input['item_title_tag'] ) ) {
            $new_input['item_title_tag'] = sanitize_text_field($input['item_title_tag']);
        }

        // Option 'display_thumbnail' : allow to choose to display post thumbnail or not
        $new_input['display_thumbnail'] = isset($input['display_thumbnail']); // Boolean settings

        // Option 'display_excerpt' : allow to choose to display post excerpt or not
        $new_input['display_excerpt'] = isset($input['display_excerpt']); // Boolean settings

        // Options 'excerpt_generators' : allow to choose which method to use to generate an excerpt for related content item
        if( isset( $input['excerpt_generators'] ) ) {
            $new_input['excerpt_generators'] = [];
            foreach($input['excerpt_generators'] as $key => $value){
                $new_input['excerpt_generators'][$key] = sanitize_text_field($value);
            }
        }

        // Option 'excerpt_length' : maximum length of the excerpt
        if( isset( $input['excerpt_length'] ) ){
            $new_input['excerpt_length'] = absint($input['excerpt_length']);
        }


        /**
         * FORCING SOME HIDDEN OPTIONS
         */
        $new_input['grid_system'] = "classic";


        return $new_input;
    }
}
