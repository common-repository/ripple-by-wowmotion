<?php
require_once(RIPPLE_DIR_PATH . '/admin/class/class.admin-options-manager.php');


class RippleAdminInitializer extends Singleton
{
    const RIPPLE_PT_EXTERNAL_URL = "ripple_post_type_external_url";
    const RIPPLE_PT_EXCERPT_FIELD = "ripple_post_type_excerpt_field";

    /**
     * Start up
     */
    public function set_up()
    {
        // Configuring post type based on user dashboard configurations
        add_action('init', [$this, 'configure_post_type_support']); // Post type support
        add_action('add_meta_boxes', [$this, 'configure_ripple_meta_box']); // Ripple meta box

        // Configuring the ripple custom field registration
        add_action('save_post', [$this, 'save_ripple_meta_box_postdata']);
    }


    public function configure_post_type_support()
    {
        $option_manager = new AdminOptionManager(RippleDashboardAdmin::OPTION_NAME);

        $editable_post_type = RippleHelper::get_editable_post_type();
        foreach ($editable_post_type as $ept)
        {
            if ($option_manager->option_value("post_type", $ept->name, "activate_excerpt")) {
                add_post_type_support( $ept->name, "excerpt" );
            }
        }
    }

    /**
     * Used to display a custom meta box on post types
     */
    public function configure_ripple_meta_box()
    {
        $option_manager = new AdminOptionManager(RippleDashboardAdmin::OPTION_NAME);

        $editable_post_type = RippleHelper::get_editable_post_type();
        foreach ($editable_post_type as $ept)
        {
            if ($option_manager->option_value("post_type", $ept->name, "activate_origin_url_field")) {
                add_meta_box(
                    'ripple_meta_box',           // Unique ID
                    'Ripple fields',  // Box title
                    [$this, 'build_ripple_meta_box_content'],  // Content callback, must be of type callable
                    $ept->name,                   // Screen:  Post type
                    "advanced",
                    "high"
                );
            }
        }
    }

    public static function build_ripple_meta_box_content()
    {
        $post = get_post();
        $value = get_post_meta($post->ID, self::RIPPLE_PT_EXTERNAL_URL, true);
        ?>
        <label for="<?php echo self::RIPPLE_PT_EXTERNAL_URL; ?>"><?php _e("External URL", RIPPLE_TR_DOMAIN); ?></label>
        <input id="<?php echo self::RIPPLE_PT_EXTERNAL_URL; ?>" type="url" name="<?php echo self::RIPPLE_PT_EXTERNAL_URL; ?>" value="<?php echo $value; ?>"  /> <br />
        <em>Use this field if the current content describe an external page on the Web. When spreading the content Ripple will used this URL if filled (if not, it will use the Wordpress URL)</em>
        <?php
    }


    function save_ripple_meta_box_postdata($post_id)
    {
        if (array_key_exists(self::RIPPLE_PT_EXTERNAL_URL, $_POST)) {
            update_post_meta(
                $post_id,
                self::RIPPLE_PT_EXTERNAL_URL,
                $_POST[self::RIPPLE_PT_EXTERNAL_URL]
            );
        }
    }

}