<?php

class RippleBreadcrumbWidget
{

    private $options;

    public function __construct($options)
    {
        $this->options = $options;

        // If the widget is not activated, doing nothing more
        if( !$this->options["activated"] ){ return; }

        add_action( 'wp_enqueue_scripts', array($this, 'ripple_breadcrumbs_scripts') );

    }


    public static function get_shortcode_name()
    {
        return 'ripple_breadcrumbs';
    }

    /**
     * @return String|void
     */
    public function do_shortcode()
    {
        // Display the breadcrumbs only when the widget is activated
        if( !$this->options["activated"] ){ return false; }

        return $this->build_breadcrumbs_html();
    }


    /**
     * Called when initializing the widget to display the breadcrumbs right before the page content
     * Should be called only within the worpdress filter "the_content"
     * @param $content - The page content
     * @return string
     */
    public function automatic_display($content)
    {
        if (!$this->options['activated']) { return $content; }

        if( !$this->options["automatic_display"] ) {return $content;}

        if(in_the_loop()){

            // Display the breadcrumb only within the main query
            if( !is_main_query()){ return $content;}

            // Do not display the breadcrumbs on homepage
            if ( is_front_page() ) { return $content; }

            return $this->build_breadcrumbs_html().$content;
        }

        return $content;
    }

    public function ripple_breadcrumbs_scripts()
    {
        wp_register_style('ripple-breadcrumbs-style', plugin_dir_url(__DIR__) . 'public/css/ripple-breadcrumbs.css');
        wp_enqueue_style('ripple-breadcrumbs-style');
    }

    /**
     * Build the HTML to display a breadcrumbs
     *
     * Several cases has to be taken in account depending on which type of content the method deals with :
     * - homepage is_home()
     * - pages is_page()
     * - posts is_posts()
     * - category is_category()
     * - custom post type
     * - archive
     * - tag
     *
     * @var $content
     * @return String - The content or modified content (with breadcrumbs)
     */
    private function build_breadcrumbs_html()
    {
        $breadcrumbs = "";
        $item_pos = 1; // Item_pos will be automatically incremented by the get_item method

        // Retrieve the current post (can be a page, a post, an archive etc. ...)
        $current_post = get_post();

        // Initializing some stuffs based on the registered options
        $display_home_link = $this->options["display_home_link"];

        // Build home link
        if($display_home_link){
            $breadcrumbs .= $this->get_item("home", get_home_url(), $this->options["home_link_text"], $item_pos);
        }

        // Breadcrumbs for hierarchical content

        if(is_post_type_hierarchical( get_post_type() ))
        {
            // If child page, get parents
            $ancestors = get_post_ancestors($current_post->ID);

            if (count($ancestors)) {
                // Get parents in the right order
                $ancestors = array_reverse($ancestors);

                // Build the breadcrumb
                foreach ($ancestors as $ancestor_id) {
                    $breadcrumbs .= $this->get_item($ancestor_id,  get_permalink($ancestor_id), get_the_title($ancestor_id), $item_pos);
                }
            }
        }
        // Last but not least, the current page
        $breadcrumbs .= $this->get_item(get_the_ID(), "", get_the_title(), $item_pos, true);
        return $this->wrap_breadcrumbs_content($breadcrumbs);
    }

    /**
     * Generate the HTML relative to an item of the breadcrumbs (a link wrapped inside an <li> element)
     * @var $content_id - Numeric ID or String (used in HTML class)
     * @var $permalink - The URL of the content
     * @var $link_text - The text to display
     * @var $position - The position of the element in the breadcrumbs
     * @var $is_current - Determine if we are dealing with the element of the breadcrumbs corresponding to the current page
     * @return String
     */
    private function get_item($content_id, $permalink="", $link_text, &$position, $is_current = false)
    {
        $seo_attr = "itemscope itemtype='http://schema.org/Thing' itemprop='item'";
        if($is_current){
            $item = "<span {$seo_attr} class='ripple-breadcrumbs-current-item' itemprop='name'><span itemprop=\"name\">{$link_text}</span></span>";
            return $this->wrap_item("current", $item, $position, false);
        }
        $link = "<a {$seo_attr} class='bread-parent' href='{$permalink}'><span itemprop=\"name\">{$link_text}</span></a>";
        $wrapped_item = $this->wrap_item($content_id, $link, $position);
        $position ++;
        return $wrapped_item;
    }

    /**
     * Wrap the item given as a parameter inside a <li> tag
     * @var $content_id String - Will be used to add an additional css class on the li class
     * @var $item String - The HTML representing the item
     * @var $position
     * @var $with_separator
     * @return String - The generated HTML
     */
    private function wrap_item($content_id, $item, $position, $with_separator = true)
    {
        $separator = $with_separator ? htmlspecialchars_decode($this->options['separator']) : "";
        $seo_attr = " itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem' ";
        $html =
            "<li {$seo_attr} class='ripple-breadcrumb-item-{$content_id} ripple-breadcrumb-item'>
                {$item}<meta itemprop='position' content='{$position}' />
            </li>{$separator}";
        return $html;
    }

    /**
     * Used to wrap the breadcrumbs content inside a HTML container
     * @breadcrumbs - The content of the breadcrumbs
     */
    private function wrap_breadcrumbs_content($breadcrumbs)
    {
        $seo_attr = "itemscope itemtype='http://schema.org/BreadcrumbList'";
        return "<ol {$seo_attr} class='ripple-breadcrumbs' >{$breadcrumbs}</ol>";
    }

}