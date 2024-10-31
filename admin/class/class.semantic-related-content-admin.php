<?php

require_once(dirname(__FILE__) . '/class.singleton.php');

class RippleSemanticRelatedContentAdmin extends Singleton
{

    const OPTION_NAME = 'ripple_semantic_related_content';

    /**
     * Allow to manipulate options for the admin page with ease
     * @var AdminOptionManager
     */
    private $option_manager;

    /**
     * Start up
     */
    public function set_up()
    {
        // Rendering submenu
        add_action("admin_menu", [$this, "render_menu"]);

        // Registering settings
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
            __("Ripple semantic related content", RIPPLE_TR_DOMAIN),
            __("Semantic related content", RIPPLE_TR_DOMAIN),
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
                <?php _e('Ripple displays related content below each of your post to keep your visitor reading on your awesome website !', RIPPLE_TR_DOMAIN);?>
                <br /><br />
                <?php _e('The relevance of related content is calculated by an intelligent algorithm based on your taxonomy.', RIPPLE_TR_DOMAIN);?>
                <br />
                <?php _e('The more you organize your content posts with taxonomies (categories, post_tags or whatever custom taxonomies you use)... the more Ripple will rock !', RIPPLE_TR_DOMAIN);?>
                <br /><br /><br />
                <?php _e('Activate this widget and you will be able to use it in two different ways :', RIPPLE_TR_DOMAIN);?>
                <ul>
                    <li><?php _e('By activating configuring the automatic display', RIPPLE_TR_DOMAIN);?></li>
                    <li>
                        <?php
                        _e('By using the shortcode', RIPPLE_TR_DOMAIN);

                        $help_title = 'Semantic widget shortcode';
                        $help_content = RippleAdmin::load_partial(RIPPLE_DIR_PATH . '/admin/partials/_help_semantic_widget_shortcode.html.php');
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
                            <input type="checkbox" value="1" class="ripple-activate-widget" data-toggle="ripple-rc-general-settings-form" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                            <label><i class="fa fa-toggle-off danger"></i></label>
                            <label><i class="fa fa-toggle-on success"></i></label>
                        </div>
                    </div>

                    <div id="ripple-rc-general-settings-form" class="toggle-form hidden">

                        <div class="ripple-section-intro">
                            <?php _e('Feel free to customize the different elements of the related content box to bring more awesomeness to your theme !', RIPPLE_TR_DOMAIN); ?>
                            <br /><br />
                            <strong><?php _e('Notice for shortcode', RIPPLE_TR_DOMAIN); ?></strong> <br />
                            <?php _e('Be aware that when using the shortcode, if you do not parametrize it, the default value considered will be the set of options below', RIPPLE_TR_DOMAIN); ?>
                        </div>

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
                                    <label for="automatic_display"><?php _e("Automatic display", RIPPLE_TR_DOMAIN); ?></label>
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
                                <th scope="row"><label for="widget_title"><?php _e('Widget title', RIPPLE_TR_DOMAIN); ?></label></th>
                                <td>
                                    <?php
                                    $html_name = $this->option_manager->html_name("widget_title");
                                    $value     = $this->option_manager->option_value("widget_title");
                                    ?>
                                    <input type="text" id="widget_title" name="<?php echo $html_name; ?>" value="<?php echo $value; ?>" autocomplete="off" />

                                    <br />
                                    <label for="widget_title"><?php _e('Personalize the way you invite user to read more post (e.g. "You might like also read")', RIPPLE_TR_DOMAIN) ?></label>
                                    <br />
                                    <em><?php _e('This title will be displayed right above the list of related contents.', RIPPLE_TR_DOMAIN) ?></em>
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
                            <tr>
                                <th scope="row"><label for="column_number"><?php _e('Display within column(s)', RIPPLE_TR_DOMAIN) ?></label></th>
                                <td>
                                    <?php
                                    $html_name = $this->option_manager->html_name("column_number");
                                    $value     = $this->option_manager->option_value("column_number");
                                    ?>
                                    <select id="column_number" name="<?php echo $html_name; ?>" autocomplete="off" >
                                        <option value="1" <?php selected( $value, 1 ); ?>><?php _e("1 column", RIPPLE_TR_DOMAIN) ?></option>
                                        <option value="2" <?php selected( $value, 2 ); ?>><?php _e("2 columns", RIPPLE_TR_DOMAIN) ?></option>
                                        <option value="3" <?php selected( $value, 3 ); ?>><?php _e("3 columns", RIPPLE_TR_DOMAIN) ?></option>
                                        <option value="4" <?php selected( $value, 4 ); ?>><?php _e("4 columns", RIPPLE_TR_DOMAIN) ?></option>
                                    </select>
                                    <br />
                                    <label for="column_number"><?php _e("The related contents will be displayed within column(s), depending on the number you specify.", RIPPLE_TR_DOMAIN) ?></label>
                                    <br />
                                    <em><?php _e("For small devices, Ripple will force the display in one column.", RIPPLE_TR_DOMAIN) ?></em><?php
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="max_related_post"><?php _e('Max. related post', RIPPLE_TR_DOMAIN) ?></label></th>
                                <td>
                                    <?php
                                    $html_name = $this->option_manager->html_name("max_related_post");
                                    $value     = $this->option_manager->option_value("max_related_post");
                                    ?>
                                    <input type="number" id="max_related_post" name="<?php echo $html_name; ?>" value="<?php echo $value; ?>" autocomplete="off" min="1" />
                                    <br />
                                    <label for="max_related_post"><?php _e("Define the maximum number of related contents you want to propose below the post content. (min. 1)", RIPPLE_TR_DOMAIN) ?></label><?php
                                    ?>
                                </td>
                            </tr>

                            <?php include(RIPPLE_DIR_PATH."/admin/partials/_subform_excerpt.html.php"); ?>

                        </table>

                        <section class="ripple-admin-action">
                            <button class="ripple-button" type="submit"><?php _e("Save settings", RIPPLE_TR_DOMAIN); ?></button>
                        </section>

                        <h2 class="ripple-section-title">
                            <i class="fa fa-paint-brush" aria-hidden="true"></i>
                            <?php _e('Advanced theme configurations', RIPPLE_TR_DOMAIN) ?>
                        </h2>
                        <div class="ripple-section-intro">
                            <?php _e('These section give you hands on some advanced configuration allowing you to customize the display of the related post section.', RIPPLE_TR_DOMAIN); ?>
                        </div>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="classic_grid_system"><?php _e("Grid system", RIPPLE_TR_DOMAIN); ?></label>
                                </th>
                                <td>
                                    <?php
                                    $html_name     = $this->option_manager->html_name("grid_system");
                                    $current_value = $this->option_manager->option_value("grid_system");
                                    ?>
                                    <input type="radio" name="<?php echo $html_name; ?>" id="classic_grid_system"  value="classic" <?php echo checked( "classic",  $current_value, false ); ?> autocomplete="off" />
                                    <label for="classic_grid_system" ><?php _e("Classic grid (recommended)", RIPPLE_TR_DOMAIN) ?></label>
                                    <br />
                                    <input type="radio" name="<?php echo $html_name; ?>" id="flex_grid_system"  value="flex" <?php echo checked( "flex",  $current_value, false ); ?> autocomplete="off" />
                                    <label for="flex_grid_system" ><?php _e("Flex grid (experimental)", RIPPLE_TR_DOMAIN) ?></label>
                                    <br />
                                    <em><?php _e("Depending on the grid system chosen, old browsers may fail while rendering the related content list view.", RIPPLE_TR_DOMAIN) ?></em>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <?php
                                    $checked   = checked(1, $this->option_manager->option_value("custom_css"), false);
                                    $html_name = $this->option_manager->html_name("custom_css");
                                    ?>
                                    <div class="pretty plain toggle">
                                        <input type="checkbox" value="1" id="custom_css" name="<?php echo $html_name; ?>" <?php echo $checked; ?> autocomplete="off"/>
                                        <label><i class="fa fa-toggle-off danger"></i></label>
                                        <label><i class="fa fa-toggle-on success"></i></label>
                                    </div>
                                </th>
                                <td>
                                    <label for="custom_css"><?php _e("Override the default Ripple theme by editing your own CSS (advanced user)", RIPPLE_TR_DOMAIN); ?></label>
                                    <br />
                                    <?php
                                    $current_value = $this->option_manager->option_value("related_post_css");
                                    $default_css = htmlspecialchars(ripple_get_default_options()[self::OPTION_NAME]['related_post_css']);
                                    $html_name = $this->option_manager->html_name("related_post_css");
                                    printf(
                                        '<textarea id="related_post_css" name="'.$html_name.'" rows="15" cols="70" autocomplete="off">%s</textarea>',
                                        $current_value ? esc_html( $current_value ) : ''
                                    );
                                    ?>
                                    <br />
                                    <em><?php _e('Ripple comes with a basic theme presentation, but your theme can interfere with it.', RIPPLE_TR_DOMAIN); ?> <br /></em>
                                    <em><?php _e('If something wrong happens, you can use this field to fix the problem on your own.', RIPPLE_TR_DOMAIN); ?></em>
                                    <br /><br />
                                    <?php
                                    $confirm_text = __("By resetting the CSS value, you will loose your previous change. Continue anyway ?", RIPPLE_TR_DOMAIN);
                                    ?>
                                    <button id="restore_default_css" class="button-secondary" data-default-css="<?php echo $default_css; ?>" data-confirm-text="<?php echo $confirm_text; ?>"><?php _e("Restore CSS default value", RIPPLE_TR_DOMAIN) ?></button>
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

        // Option 'Activated' : activate / deactivate the widget
        $new_input['activated'] = isset($input['activated']); // Boolean settings

        // Option "automatic_display" : let the plugin manage the display of the widget automatically or not
        $new_input['automatic_display'] = isset($input['automatic_display']); // Boolean settings

        // Option 'max_related_posts' : allow to define the maximum number of related posts to display
        if( isset( $input['max_related_post'] ) ) {
            $new_input['max_related_post'] = absint($input['max_related_post']);
        }

        // Option 'related_posts_title' : allow to set up the title of the related posts section
        if( isset( $input['widget_title'] ) ) {
            $new_input['widget_title'] = sanitize_text_field($input['widget_title']);
        }

        // "clickable_item" :
        $new_input['clickable_item'] = isset($input['clickable_item']); // Boolean settings

        // "rel" option can have an empty value
        $new_input['rel'] = sanitize_text_field($input['rel']);

        // Option 'widget_title_tag' : allow to define the HTML tag used to display the related post widget title
        if( isset( $input['widget_title_tag'] ) ) {
            $new_input['widget_title_tag'] = sanitize_text_field($input['widget_title_tag']);
        }

        // Option 'item_title_tag' : allow to define the HTML tag used to wrap each of the related post title displayed in the widget
        if( isset( $input['item_title_tag'] ) ) {
            $new_input['item_title_tag'] = sanitize_text_field($input['item_title_tag']);
        }

        // Option 'display_thumbnail' : allow to choose to display post thumbnail or not
        $new_input['display_thumbnail'] = isset($input['display_thumbnail']); // Boolean settings

        // Option 'display_excerpt' : allow to choose to display post excerpt or not
        $new_input['display_excerpt'] = isset($input['display_excerpt']); // Boolean settings

        // Options 'excerpt_generator' : allow to choose which method to use to show the excerpt of a related content item
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

        // Option 'column_number' : define the number of columns used to display the related contents
        if( isset( $input['column_number'] ) ){
            $new_input['column_number'] = absint($input['column_number']);
        }

        // Option 'grid_system' : allow to choose the CSS method to use for displaying grid
        if( isset( $input['grid_system'] ) ) {
            $new_input['grid_system'] = sanitize_text_field($input['grid_system']);
        }

        // Option 'custom_css' : allow to activate the editing of the custom CSS field
        $new_input['custom_css'] = isset($input['custom_css']); // Boolean settings

        // Option 'related_post_css' : allow to define the CSS to display related posts
        if( isset( $input['related_post_css'] ) ) {
            // TODO : find a way to sanitize textarea ?
            $new_input['related_post_css'] = $input['related_post_css'];
        }

        return $new_input;
    }
}
