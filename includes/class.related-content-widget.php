<?php
/**
 * This abstract class is dedicated to manage both hierarchical and semantic related content widgets.
 * Class RelatedContent
 */
abstract class RelatedContent
{

    public $options;

    public function __construct($options)
    {
        $this->options = $options;

        if( !$this->options["activated"] ){ return; }

        $this->enqueue_script();
    }

    private function enqueue_script()
    {
        add_action( 'wp_enqueue_scripts', [$this, 'related_content_scripts']);
    }

    /**
     * Add the JS/CSS script relatives to the widget to the page
     */
    public function related_content_scripts()
    {
        $main_style_id = 'ripple-related-content-main';

        wp_register_style($main_style_id, plugin_dir_url(__DIR__) . 'public/css/related-content/main.css');
        wp_enqueue_style($main_style_id);

        // Common CSS
        wp_register_style('ripple-related-content-common-grid', plugin_dir_url(__DIR__) . 'public/css/related-content/grid/common-grid.css');
        wp_enqueue_style('ripple-related-content-common-grid');

        // Grid system CSS depending on chosen method
        switch ($this->options['grid_system']) {
            case "classic":
                wp_register_style('ripple-related-content-classic-grid', plugin_dir_url(__DIR__) . 'public/css/related-content/grid/classic-grid.css');
                wp_enqueue_style('ripple-related-content-classic-grid');
                break;

            case "flex":
                wp_register_style('ripple-related-content-flex-grid', plugin_dir_url(__DIR__) . 'public/css/related-content/grid/flex-grid.css');
                wp_enqueue_style('ripple-related-content-flex-grid');
                break;
        }

        // Check if the "custom_css" option is activated. If so, we include the CSS set up by the user
        if (isset($this->options['custom_css']) && $this->options['custom_css']) {
            if (isset($this->options['related_post_css']) && $this->options['related_post_css'] != "") {
                $custom_style = $this->options['related_post_css'];
                wp_add_inline_style($main_style_id, $custom_style);
            }
        }
    }

    /**
     * Wrap HTML inside ripple tags made to receive a "related content" thread
     * @var $thread_title
     * @var $thread_content
     * @return String
     */
    public function wrap_thread_content($thread_title, $thread_content)
    {
        $grid_class = ($this->options['grid_system'] == "flex") ? "ripple-flex-grid" : "ripple-classic-grid";

        return "<div class='ripple-rp-wrapper {$grid_class}'>
            <div class='ripple-rp-header'>
                <header>{$thread_title}</header>
            </div>
            {$thread_content}
        </div>";
    }

    /**
     * Concat the related content HTML string to another string and return it
     * @var $content
     * @return String
     */
    public function automatic_display($content)
    {
        // Related content widget only works for single page / post
        if( in_the_loop() && ( is_page() || is_single() ) )
        {
            if (!$this->options['activated']) {
                return $content;
            }
            if (!$this->options['automatic_display']) {
                return $content;
            }

            $related_content = $this->get_related_posts_html();

            if ($related_content) {
                $content .= $related_content;
            }
        }
        return $content;
    }

    /**
     * Function called when the shortcode is used to display the widget
     * @param array $atts
     * @param null $content
     * @return string
     */
    public function do_shortcode($atts = [], $content = null)
    {
        // Related content widget only works for single page / post
        if(is_page() || is_single())
        {
            if(!$this->options["activated"]){ return ''; }
            return $this->get_related_posts_html();
        }
        return $content;

    }

    /**
     * @param $method_name
     * @return bool
     */
    private function excerpt_generator_method_is_active($method_name){
        if(isset($this->options["excerpt_generators"])){
            return (isset($this->options["excerpt_generators"][$method_name]) && $this->options["excerpt_generators"][$method_name]);
        }
        return false;
    }

    /**
     * Generate the HTML of a related post to be displayed in the ripple related post section
     * @param $post - The post
     * @return String
     */
    public function get_related_post_html($post)
    {
        // Making the current post the main post (do not forget to reset the Wordpress query thanks to wp_reset_postdata)
        setup_postdata($post);

        // The permalink
        $permalink_attr = [ "href" => "", "target" => ""];
        $origin_url = get_post_meta($post->ID, "ripple_post_type_external_url");
        if($origin_url){
            $permalink_attr["href"] = $origin_url[0];
            $permalink_attr["target"] = "_blank";
        }
        else{
            $permalink_attr["href"] = get_the_permalink($post);
        }


        // Build the excerpt if the option tells to
        $excerpt = "";
        $start_truncated = $end_truncated = false;


        if($this->options['display_excerpt'])
        {
            /**
             * Get the excerpt field if it exists or build an excerpt from the post content
             */
            // Get the excerpt based on the "<!-- more -->" tag
            if($this->excerpt_generator_method_is_active("more_content") && get_the_content( '', TRUE )){
                $excerpt = get_the_content( '', TRUE );
                $excerpt = html_entity_decode(strip_tags($excerpt));
            }
            // Get the excerpt based on the "excerpt" field
            elseif($this->excerpt_generator_method_is_active("excerpt_field") && has_excerpt($post->ID)) {
                $excerpt = get_post_field('post_excerpt', $post->ID);
            }
            // There is no excerpt defined, ripple will defined one automatically
            elseif($this->excerpt_generator_method_is_active("ripple"))
            {
                /**
                 * Building an excerpt based on the post_content :
                 * Extract the outer html of the first element described by $start_tag and $end_tag from the post_content
                 * by calculating the position of the excerpt inside the post_content :
                 * $start => The start position of the first $start_tag found
                 * $end   => The end position of the $end_tag associated to $start_tag
                 */
                $start_tag = '<p>'; $end_tag = '</p>';
                $post_content = get_post_field('post_content', $post->ID);
                if(strlen($post_content)){
                    $start   = strpos($post_content, $start_tag);
                    $end     = strpos($post_content, $end_tag, $start);
                    // The HTMl contains the searched tag
                    if($start && $end) {
                        // Getting the outer HTML of the searched tag
                        $end += strlen($end_tag);
                        $excerpt = substr($post_content, $start, $end - $start);

                        // Removing the HTML inside the excerpt
                        $excerpt = html_entity_decode(strip_tags($excerpt));

                        $start_truncated = ($start > 0);
                        $end_truncated   = ($end != strlen($post_content) - strlen($end_tag));

                    }
                }
            }

            // Additional treatment on the build excerpt
            if($excerpt){

                $excerpt = apply_filters('the_excerpt', $excerpt);

                if(strlen($excerpt) > $this->options['excerpt_length']){
                    substr($excerpt , 0, $this->options['excerpt_length']);
                    $end_truncated = true;
                }

                // Truncating excerpt to the mex length defined by the user
                $excerpt = substr($excerpt, 0, $this->options['excerpt_length']);

                // Adding text indicator to show if the post_content has been truncated to build the excerpt
                if($start_truncated || $end_truncated) {
                    // If the beginning of the content has been truncated, adding a text indicator at the beginning of the excerpt
                    if ($start_truncated > 0) { $excerpt = "... " . $excerpt; }
                    // If the end of the content has been truncated, adding a text indicator at the end of the excerpt
                    if ($end_truncated) { $excerpt = $excerpt . " ..."; }
                }

                // Finalizing the excerpt HTML
                $excerpt = '<div class="ripple-rp-excerpt">' . $excerpt . '</div>';
            }
        }

        // Building the thumbnail HTML
        $thumbnail = "";
        if($this->options['display_thumbnail']) {
            // Get the featured image of the current post
            $thumbnail = get_the_post_thumbnail($post, array(800, 800));

            // If no featured image found, adding a default one
            if ( !$thumbnail) { $thumbnail = '<img src="'.RIPPLE_DIR_URL.'public/images/default-thumbnail.jpg" />'; }

            // Finalizing the HTML
            if($this->options['clickable_item']){
                $thumbnail = '<a href="' . $permalink_attr["href"] . '" target="'.$permalink_attr["target"].'" rel="'.$this->options['rel'].'">' . $thumbnail . '</a>';
            }
        }

        // Building the "title" HTML and wrapping in the user defined HTML tag
        $rp_title  = get_the_title($post);
        if($this->options['clickable_item']){
            $rp_title  = '<a href="'.$permalink_attr["href"].'" target="'.$permalink_attr["target"].'" rel="'.$this->options['rel'].'">'.get_the_title($post).'</a>';
        }
        $rp_title = ripple_create_html_tag(
            $this->options['item_title_tag'],
            $rp_title,
            ["class" => "ripple-rp-title"]
        );


        // Building the "content block" HTML
        $rp_content   = "<div class='ripple-rp-content'>";
        $rp_content  .=     $rp_title;
        $rp_content  .=     $excerpt;
        $rp_content  .= "</div>";

        // Building the "thumbnail block" HTML
        $rp_thumbnail = "";
        if($this->options['display_thumbnail'] && $thumbnail) {
            $rp_thumbnail = "<div class='ripple-rp-thumbnail'>".$thumbnail."</div>";
        }

        // Important : resetting main post data (because of the use of setup_postdata at the beginning og this method)
        wp_reset_postdata();

        return '<article class="ripple-rp">'.$rp_thumbnail.$rp_content.'</article>';
    }


}