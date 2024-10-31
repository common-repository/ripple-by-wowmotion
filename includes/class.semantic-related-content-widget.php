<?php

require_once(dirname(__FILE__) . '/class.related-content-widget.php');
require_once(dirname(__FILE__) . '/class.semantic-related-content-searcher.php');
require_once(dirname(__FILE__) . '/class.tax-query-lexer.php');

/**
 *
 * The Ripple Semantic Related Content Widget is made to display relative contents to one other
 * or related to a specific tax query
 *
 * Definitions :
 * - The original content is the one displayed
 * - The related contents are found based on the taxonomies terms attached to each content.
 * - Taxonomies can be "category", "post_tag" or whatever custom registered taxonomies
 *
 * Usage
 * -----
 * 1- The widget can be displayed automatically right after the content of a post.
 * 2- Or one can also use the shortcode to display the widget wherever it is desired. Also the shortcode offers more customizations.
 *
 * Look and feel
 * -------------
 * Several options are available to customize the look of the widget. One can parametrize the number of related content to display,
 * customize the widget title, configure the CSS grid system,  display related content in one or several columns...
 *
 * Behaviour
 * ---------
 *
 * 1- Related contents based on original content
 *
 * Option : "search_base"
 * By default, the widget will search for contents related to the original one and basing its search on the taxonomies & terms attach to it.
 * This behavior can be bypassed with the "search_base" option (default = true)
 *
 * Accepted values :
 * - null | "origin"         : The widget will perform its search based on the terms attached to original content
 * - $content_id (integer)   : The ID of specific content - The widget will perform its search based on the contents attached to this ID
 * - $query_string (string)  : The query string can have the form "my_taxonomy=a_term"
 * - "none" (string)         : No taxonomies will be used to research related content
 *
 *
 * 2 - Related post by post_type
 *
 * Option : "post_type"
 * By default, the widget only search for relative contents which belong to the same post_type as the original content (post_types are considered as silo)
 * This behaviour can be bypassed using the option "post_type" to allow the widget to search in "any" registered post type, or even in an explicit list of post type given by the configuration.
 *
 * Accepted values :
 * - "self" | undefined - (Default) The widget will only search for content of the same post_type as the original content
 * - "*"                - The widget will not considered post-type as silo anymore. It will search for relative contents in any existing post_type
 * - A string - Composed of post_type slug separated by coma (ex : 'post,page,my_custom_post_type")
 *
 * 3 - Configuring taxonomies :
 *
 *     3.1 Taxonomies as silo TODO (or not ?)
 *     Option : "tax_as_silo"
 *     By default the widget will search for posts which have common terms of the same taxonomies (taxonomies are considered as silo)
 *     This behaviour can be bypassed using the option "tax_as_silo" to allow the widget search for related post by terms no matter which taxonomy is concerned.
 *
 *     Accepted values :
 *     - true | false : a Boolean value. Setting to false, the widget will not considered taxonomies as silo anymore. Default is true.
 *
 *     3.2 Configuring a white list of taxonomy
 *     Option : "tax_white_list"
 *     By default the widget will base it search on every taxonomies attached to the original content.
 *     This behaviour can be bypassed using the option "tax_white_list" to allow the widget to perform its search based on a specific list of taxonomies.
 *     Note : this option will be ignore if the "search_base" option is defined as a query string
 *
 *     Accepted values :
 *     - String of taxonomies names separated by coma
 *     - null | undefined: include all taxonomies
 *
 *
 */
class RippleSemanticRelatedContentWidget extends RelatedContent
{

    public function __construct($options)
    {
        parent::__construct($options);
    }

    public static function get_shortcode_name()
    {
        return 'ripple_semantic_related_content';
    }

    /**
     * The post type option can have several value :
     * - self : represent the post type of the current post
     * - any : represent every registered post type
     * - a String of post type slugs
     * - an array of post type slugs
     *
     * To be exploitable, we should transform the value of the option in a way the code can exploit it :
     * - If the option has the "self" value or if it is not defined : the option is set with the current post type slug
     * - If the option is a string of post type slug separated by coma, then we transform this as an array
     * - If the option has the "any" value, or if it is an array, we do nothing
     *
     * @return array An string or of post type slug
     */
    public function get_post_type_option()
    {
        $post_type_option = [];

        // If self value found, replacing it by the current post type slug
        if( $this->options["post_type"] == "self" || !$this->options["post_type"] ) { $post_type_option[] = get_post_type(get_the_ID()); }

        // If "*" we considered every registered post type
        elseif( $this->options["post_type"] == "*" ) {
            $post_type_option = get_post_types([
                "public" => true,
                "publicly_queryable" => true,
            ]);
        }

        // If the value is a string, replacing it by an array
        elseif(is_string($this->options["post_type"])){ $post_type_option = explode(",", $this->options["post_type"]);}

        return $post_type_option;

    }

    /**
     * Get the "search_base" option value
     * @return mixed - A post ID or a string (representing a query)
     */
    public function get_search_base_option()
    {
        if(!$this->options["search_base"] || $this->options["search_base"] == "origin") {$this->options["search_base"] = get_the_ID(); }

        return  $this->options["search_base"];
    }

    /**
     * This method return the list of all allowed taxonomies to perform the search of content
     *
     * If the "tax_white_list" option isn't specified, the method search for every "valid" taxonomies registered
     * Otherwise, the method return all existing taxonomies of the specified list
     *
     *
     * Note : the "tax_white_list" option allow to specify taxonomies the widget should use to base its search about related content post
     * The option can be a string representing one or several taxonomies slug separated by coma
     *
     * @return array - An array of taxonomies name
     *
     */
    private function get_tax_white_list()
    {
        $taxonomies = [];
        if(!$this->options["tax_white_list"]) {
            $taxonomies = get_taxonomies([
                'public' => true,
                'publicly_queryable' => true,
            ]);
        }
        else{
            foreach(explode(",", $this->options["tax_white_list"]) as $tax_name){
                $tax_name = trim($tax_name);
                if(taxonomy_exists($tax_name)) {
                    $taxonomies[] = trim($tax_name);
                }
            }
        }

        return $taxonomies;
    }

    /**
     * Function called to build the related content HTML.
     * The related content is displayed only under certain conditions :
     * - The active page is the single post page
     * - Only the related content attached to the current post
     */
    public function get_related_posts_html()
    {

        $related_posts = $this->get_related_posts(get_the_ID());

        // Building the HTML list of related posts displayed within columns depending of the admin configuration
        if (count($related_posts)) {

            // Calculation the number of row depending on the number of column chosen
            $total_col = $this->options['column_number'];
            $total_row = ceil(count($related_posts) / $total_col);

            // Some initializations
            $col_class = "ripple-rp-col col-".$total_col;
            $row_start = '<div class="ripple-rp-row">'; $row_end = '</div>';

            // Detecting the chosen CSS grid system
            $is_flex_grid = $this->options['grid_system'] == "flex" ? true : false;

            // Starting the HTML building
            $related_posts_html  = '<div class="ripple-rp-list">';

            // For flex grid system : only one row containing every elements
            if($is_flex_grid){ $related_posts_html .= $row_start; }

            // Building the related list content
            for($current_row = 0 ; $current_row < $total_row ; $current_row++){
                // Starting row for classic grid system
                $related_posts_html .= $is_flex_grid ? '' : $row_start ;

                for($current_col = 0 ; $current_col < $total_col ; $current_col++){
                    $rp_id = $current_row * $total_col + $current_col;
                    if($rp_id >= count($related_posts)){ break;} // Exiting the loop if the related post list have been entirely browsed
                    $related_posts_html .= '<div class="'.$col_class.'">'.$this->get_related_post_html($related_posts[$rp_id]).'</div>';
                }

                // Ending row for classic grid system
                $related_posts_html .= !$is_flex_grid ? $row_end : '';
            }

            // End of the unique row for flex grid system
            if($is_flex_grid){ $related_posts_html .= $row_end; }

            $related_posts_html .= "</div>";

            $widget_title = ripple_create_html_tag(
                $this->options['widget_title_tag'],
                $this->options['widget_title'],
                array(
                    "class" => "ripple-widget-title"
                )
            );

            // Building related posts full container
            return parent::wrap_thread_content($widget_title, $related_posts_html);

        }
        return '';
    }


    /**
     * Retrieve the posts related to a post
     * @param $ref_post_id
     * @return array
     */
    private function get_related_posts($ref_post_id) {
        $related_content_manager = new RippleRelatedContentSearcher(get_post($ref_post_id), [
            "max_related_post" => $this->options["max_related_post"],
            "search_base"      => $this->get_search_base_option(),
            "tax_white_list"   => $this->get_tax_white_list(),
            "post_type"        => $this->get_post_type_option()
        ]);
        return $related_content_manager->get_related_content();
    }

}

