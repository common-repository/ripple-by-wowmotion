<?php

// Version of the plugin
define('RIPPLE_VERSION'  , '0.1.0');

// Used as the translation domain name (second parameter of _e() function)
define('RIPPLE_TR_DOMAIN', 'wp_ripple');

// Used for file inclusion
define( 'RIPPLE_DIR_PATH', dirname(__FILE__));
define( 'RIPPLE_DIR_URL', plugin_dir_url( __FILE__ ) );


/**
 * Default value for Ripple option
 */

$defaultCssFilePath = RIPPLE_DIR_PATH . '/admin/css/default-custom.css';

define('RIPPLE_DEFAULT_OPTIONS', serialize(
    array(
        "ripple_options" => [
            "version" => RIPPLE_VERSION,
        ],
        "ripple_admin" => [ // Dashboard options
            "post_type" => []
        ],
        "ripple_breadcrumbs" => [
            "activated"         => true,
            "automatic_display" => false,
            "separator"         => "&gt;",
            "display_home_link" => true,
            "home_link_text"    => "Homepage"
        ],
        "ripple_semantic_related_content" => array(
            "activated"          => true,
            "automatic_display"  => false,
            "widget_title"       => "You might also read",
            "widget_title_tag"   => "span",
            "item_title_tag"     => "span",
            "rel"                => "",
            "search_base"        => "origin",
            "post_type"          => "self",
            "tax_white_list"     => null,
            "include_terms"      => true,
            "max_related_post"   => 6,
            "display_thumbnail"  => true,
            "display_excerpt"    => true,
            "clickable_item"     => true,
            "excerpt_generators" => [
                "more_content"    => false,
                "excerpt_field"   => true,
                "ripple"          => true
            ],
            "excerpt_length"     => 150,
            "column_number"      => 1,
            "grid_system"        => "classic",
            "custom_css"         => false,
            "related_post_css"   => fread  ( fopen($defaultCssFilePath, 'r'), filesize ($defaultCssFilePath) )
        ),
        "ripple_hierarchical_related_content" => [
            "activated"          => true,
            "automatic_display"  => false,
            "widget_title"       => "What is next ?",
            "widget_title_tag"   => "span",
            "item_title_tag"     => "span",
            "clickable_item"     => true,
            "rel"                => "",
            "display_thumbnail"  => true,
            "display_excerpt"    => true,
            "excerpt_generators" => [
                "more_content"    => false,
                "excerpt_field"   => true,
                "ripple"          => true
            ],
            "excerpt_length"     => 150,
            "grid_system"        => "classic",
        ]
    )
));