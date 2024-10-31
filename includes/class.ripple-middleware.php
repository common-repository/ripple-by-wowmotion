<?php

require_once(RIPPLE_DIR_PATH . '/includes/class.ripple-helper.php');
require_once(RIPPLE_DIR_PATH . '/admin/class/class.admin-options-manager.php');

/**
 * Class RippleMiddleware
 * This class intend to impact Wordpress depending on Ripple configurations
 * In some ways Ripple is able to change the Wordpress set up like creating new taxonomies.
 */
class RippleMiddleware
{

    public static function configure_wordpress()
    {
        add_action("init", [self::class, "configure_taxonomies"]);
    }

    /**
     *
     */
    public static function configure_taxonomies()
    {

        $option_manager = new AdminOptionManager(RippleDashboardAdmin::OPTION_NAME);

        $options = $option_manager->get_options();
        $editable_post_type = RippleHelper::get_editable_post_type();
        foreach($editable_post_type as $ept)
        {
            $supports = [];

            if(isset($options["post_type"][$ept->name]))
            {
                $post_type_option = $options["post_type"][$ept->name];

                // Make it hierarchical
                if(isset($post_type_option["hierarchical"])){
                    // Make the post type hierarchical
                    $ept->hierarchical = $post_type_option["hierarchical"];
                    // Add the support for "parent" metabox
                    $supports[] = "page-attributes";
                }
                // Finally register the post type
                $ept->supports = $supports;
                register_post_type($ept->name, $ept);

                // **************
                // * Taxonomies *
                // **************

                // Get every editable taxonomies depending on the current post type
                $pt_taxonomies = RippleHelper::get_editable_taxonomies_for_post_type($ept->name);

                // Treating every taxonomies
                // TODO : for each post type Ripple give the possibility to register/unregister the taxonomy. However, we may want a three states option here.
                // TODO : 1- First option to let Ripple register the taxonomy
                // TODO : 2- Second option to let Ripple unregister the taxonomy
                // TODO : 3- Third option to tell Ripple do nothing about it ( it will let the hand to Wordpress core or other plugin )
                foreach($pt_taxonomies as $tax_name => $tax_data)
                {
                    // Test if the taxonomy should be register for the post type
                    if(isset($post_type_option[$tax_name]) && $post_type_option[$tax_name])
                    {
                        // Create the taxonomy if it doesn't exist
                        if(!taxonomy_exists($tax_name))
                        {
                            $hierarchical = strpos($tax_name, "category") ? true : false;
                            register_taxonomy($tax_name, $ept->name, [
                                'hierarchical' => $hierarchical,
                                'label'        => __( ucfirst(str_replace("_", " ", $tax_name)) ),
                            ]);
                        }
                        // Register the taxonomy for the post type
                        register_taxonomy_for_object_type($tax_name, $ept->name);
                    }
                    else
                    {
                        // Unregister the taxonomies if uncheck
                        unregister_taxonomy_for_object_type($tax_name, $ept->name);
                    }
                }
            }
        }
    }
}