<?php

require_once(dirname(__FILE__) . '/class.related-content-widget.php');


/**
 * Class used to managed the hierarchical related content widget
 * The widget aim is to search for related content based on the content tree (children page)
 */
class RippleHierarchicalRelatedContentWidget extends RelatedContent
{
    public function __construct($options)
    {
        parent::__construct($options);
    }

    public static function get_shortcode_name()
    {
        return 'ripple_hierarchical_related_content';
    }

    /**
     * This method is used to build the HTML oh the hierarchical related content widget
     */
    public function get_related_posts_html()
    {
        if(!$this->options["activated"]) {return '';}

        // Displaying the widget only on "page" content
        $current_post_type = get_post_type_object(get_post_type());
        if ($current_post_type->hierarchical) {

            // Forcing some other options
            $this->options["display_thumbnail"] = true;

            // Some initializations
            $row_start = '<div class="ripple-rp-row">'; $row_end = '</div>';

            // Searching every direct children pages for the current page
            $args = array(
                'child_of' => get_the_ID(),
                'parent' => get_the_ID(),
                'post_type'    => $current_post_type->name,
                'hierarchical' => 0,
                'sort_column' => 'menu_order',
                'sort_order' => 'asc'
            );
            $child_pages = get_pages( $args );
            if (count($child_pages)) {
                $thread_content  = '<div class="ripple-rp-list">';
                foreach ($child_pages as $child_page) {
                    $thread_content .= $row_start."<div class='ripple-rp-col col-1'>".$this->get_related_post_html($child_page)."</div>".$row_end;
                }
                $thread_content  .= '</div>';

                $related_page_title = ripple_create_html_tag(
                    $this->options['widget_title_tag'],
                    $this->options['widget_title'],
                    array(
                        "class" => "ripple-widget-title"
                    )
                );

                return $this->wrap_thread_content($related_page_title, $thread_content);
            }
        }
        return '';
    }


}