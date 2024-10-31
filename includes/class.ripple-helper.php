<?php

class RippleHelper
{

    /**
     * This method return the list of post type objects that the user can edit by itself
     * An editable post type should answer this rules :
     * - The post type should be "public" ($post_type_object->public is true)
     * - The "attachment" post type "et_post_format" not look very interesting for Ripple
     * @return array - An array of post type object
     */
    public static function get_editable_post_type()
    {
        $editable_post_type = [];
        $all_post_types = get_post_types();
        foreach($all_post_types as $post_type_name)
        {
            $post_type_object = get_post_type_object($post_type_name);

            // Ignore "attachment" post type
            if($post_type_name == "attachment") {continue;}

            // Storing public post type
            if($post_type_object->public){
                $editable_post_type[] = $post_type_object;
            }
        }
        return $editable_post_type;
    }

    /**
     * Return the list of editable taxonomies for a givern post type name
     * @param $pt_name
     * @return array
     */
    public static function get_editable_taxonomies_for_post_type($pt_name)
    {
        $pt_taxonomies = [
            "category" => [
                "label"       => __("Activate categories (WP core)", RIPPLE_TR_DOMAIN),
            ],
            "post_tag" => [
                "label" => __("Activate tags (WP core)", RIPPLE_TR_DOMAIN)
            ]
        ];

        // Specific taxonomies for custom post type
        if(!in_array($pt_name, ["page", "post"]))
        {
            $cpt_category_tax_name = "{$pt_name}_category";
            $pt_taxonomies[$cpt_category_tax_name] = [
                "label" => "Activate custom category \"{$cpt_category_tax_name}\""
            ];

            $cpt_tag_tax_name = "{$pt_name}_tag";
            $pt_taxonomies[$cpt_tag_tax_name] = [
                "label" => "Activate custom tag \"{$cpt_tag_tax_name}\""
            ];
        }

        return $pt_taxonomies;
    }





}