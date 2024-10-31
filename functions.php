<?php

require_once(RIPPLE_DIR_PATH . '/admin/class/class.admin-options-manager.php');

/**
 * Generic function that return the options depending on an ID
 * @var String $id
 * @return array
 */
function ripple_get_option_by_id($id)
{
    $option_name = "{$id}_option";
    global $$option_name;
    if($$option_name) { return $$option_name; }
    else{
        $$option_name = get_option($id);
    }

    if(!$$option_name){$$option_name = [];}

    return $$option_name;
}


/**
 * Return the options associated to the "breadcrumbs" ripple module
 * @return ArrayObject|mixed
 */
function ripple_get_breadcrumbs_options() {
    $slug = "ripple_breadcrumbs";
    $option_manager = new AdminOptionManager($slug);
    $registered_options = ripple_get_option_by_id($slug);

    return array_merge($option_manager->get_default_option(),$registered_options);
}


/**
 * Return the options associated to the "related_content" ripple module
 * @return ArrayObject|mixed
 */
function ripple_get_semantic_related_content_options() {
    $slug = "ripple_semantic_related_content";
    $option_manager = new AdminOptionManager($slug);
    $registered_options = ripple_get_option_by_id($slug);

    return array_merge($option_manager->get_default_option(),$registered_options);
}

/**
 * Return the options associated to the "related_content" ripple module
 * @return ArrayObject|mixed
 */
function ripple_get_hierarchical_related_content_options() {
    $slug = "ripple_hierarchical_related_content";
    $option_manager = new AdminOptionManager($slug);
    $registered_options = ripple_get_option_by_id($slug);

    return array_merge($option_manager->get_default_option(),$registered_options);

}

/**
 * Return an array of all default options defined for each module of ripple
 * @return mixed
 */
function ripple_get_default_options(){
    return unserialize(RIPPLE_DEFAULT_OPTIONS);
}

/**
 * Create an HTML string depending on a tag
 * @param $tag
 * @param $content
 * @param $option
 * @return string
 */
function ripple_create_html_tag($tag, $content, $option=[]){
    if(empty($tag))            { $tag = 'span'; }
    if(empty($option["class"])){ $option["class"] = ""; }
    return "<{$tag} class='{$option["class"]}'>{$content}</{$tag}>";
}