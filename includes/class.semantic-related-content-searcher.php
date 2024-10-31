<?php

/**
 * The class is used to perform the search of posts to display in the semantic related content widget
 * The search is performed based on taxonomies.
 *
 * The taxonomies used are based on the search_base of the post.
 * This option can either be a post_id (carrying specific taxonomies) or based on a query string
 *
 * Class RippleRelatedContentSearcher
 */
class RippleRelatedContentSearcher
{
    private $original_post;
    private $related_content_list;
    private $options;
    private $tax_query;
    private $debug;

    public function __construct(WP_Post $post, array $options)
    {
        $this->original_post        = $post;
        $this->options              = $options;
        $this->related_content_list = array();
        $this->debug                = false;

        // Initializing the tax_query that will be use to perform the search of the related content
        $this->set_tax_query();
    }

    /**
     * For a given taxonomy name, the function tell if it is a valid taxonomy
     * A taxonomy is considered as valid if :
     * - public is true
     * - publicly_queryable is true
     * - the taxonomy belong to the white list define by the user (see "tax_white_list" option)
     * @param $tax_name
     * @return bool
     */
    private function is_valid_taxonomies($tax_name)
    {
        $all_valid_registered_tax = get_taxonomies([
            'public' => true,
            'publicly_queryable' => true,
        ]);

        return in_array($tax_name,$all_valid_registered_tax) && in_array($tax_name, $this->options["tax_white_list"]);
    }

    /**
     * Check if the search is_based on a post or not
     * If it is based on a post, the search_base option shoold be an ID, otherwise, it should be a string
     */
    private function is_search_based_on_post()
    {
        return is_int($this->options["search_base"]);
    }

    /**
     * This method instantiate the "tax_query" attribute of this class
     * The tax_query is build depending on the search_base option
     * - If the search_base is a post : the tax_query is build base on the taxonomies carried by this post
     * - If the search_base is a query string : the tax_query is build based on the parsing on this string
     */
    private function set_tax_query()
    {
        $this->tax_query = [];

        // Search base can be a post, or a custom query, or "none"
        $search_base = $this->options["search_base"];

        // Ignoring the process if "none" specified.
        if($search_base != "none") {

            // The query is based on the taxonomies attached to a post (the original one or a specific post)
            if ($this->is_search_based_on_post()) {

                // Building the tax_query
                $search_base_tax = get_post_taxonomies($search_base);
                foreach ($search_base_tax as $tax_name) {
                    if ($this->is_valid_taxonomies($tax_name)) {
                        $terms = get_the_terms($search_base, $tax_name);
                        if ($terms && count($terms)) {
                            $terms_slugs = [];
                            foreach ($terms as $term) {
                                $terms_slugs[] = $term->slug;
                            }

                            $this->tax_query[] = [
                                'taxonomy' => $tax_name,
                                'field' => 'slug',
                                'terms' => $terms_slugs
                            ];
                        }
                    }
                }

                // Adding the relation for the tax_query
                if (count($this->tax_query)) {
                    $this->tax_query['relation'] = "OR";
                }

            } // The search is based on a query string
            else {
                $tax_lexer = new TaxQueryLexer();
                $this->tax_query = $tax_lexer->build_tax_query($search_base);
            }
        }
    }

    /**
     * This method calculate the relevancy of a post based on the current tax_query
     * The post will have +1 point of relevancy for each taxonomies it carries compared to the tax_query
     *
     * TODO:
     * For now the method DO NOT manage nested query, maybe one day it will...
     *
     * @param Wp_post $post
     */
    private function set_relevancy_on_post(Wp_post $post)
    {
        if($this->tax_query && count($this->tax_query)) {
            // The comparison base is given by the current tax_query
            foreach ($this->tax_query as $query_arg) {
                if (is_array($query_arg) && isset($query_arg["taxonomy"]) && isset($query_arg["terms"])) {
                    $tax_name = $query_arg["taxonomy"];
                    foreach ($query_arg["terms"] as $term) {
                        if (has_term($term, $tax_name, $post)) {
                            // The post has the term, the relevancy get +1
                            $this->related_content_list[$post->ID]->relevance += 1;

                            // Counting the relevancy by taxonomy
                            $this->related_content_list[$post->ID]->taxonomies[$tax_name] += 1;
                        }
                    }
                }
            }
        }
    }

    /**
     * Launch the treatment to retrieve the contents related to the original content depending on their relevancy
     * The relevancy is an integer incrementing each time a post as a common term for a given taxonomy
     *
     * The result is finally ordered by
     * - Post relevance
     * - Publication date
     * @return array
     */
    public function get_related_content()
    {
        // Initializing the basics arguments to search related content
        $base_args = array(
            'numberposts'      => -1,
            'post_status'      => 'publish',
            'post_type'        => $this->options["post_type"],
            'post__not_in'     => array($this->original_post->ID)
        );

        // Including the tax_query
        $base_args["tax_query"] = $this->tax_query;

        // Get the related post
        $related_posts = get_posts( $base_args );

        // Adding the related post to the list
        foreach($related_posts as $rp) {
            $this->add_related_content_to_list($rp);
            $this->set_relevancy_on_post($rp);
        }

        // Ordering the list of related content depending on their relevancy
        $this->order_related_content_list();

        $this->debug_sort();

        // Return only the max number of related posts
        return array_slice($this->related_content_list, 0, $this->options["max_related_post"]);
    }

    /**
     * Order the related post list by relevance and publication date
     */
    private function order_related_content_list(){
        // Get a list of sort columns and their data to pass to array_multisort
        $sort = array();
        if(count($this->related_content_list) > 0){
            foreach($this->related_content_list as $k => $rp){
                $sort['relevance'][$k] = $rp->relevance;
                $sort['timestamp'][$k] = $rp->timestamp;
            }

            // Sorting the result by relevance DESC and the by timestamp DESC
            array_multisort($sort['relevance'], SORT_DESC, $sort['timestamp'], SORT_DESC, $this->related_content_list);
        }
    }

    /**
     * This method add a post to the list of related content
     * At the same time some attributes are initialized to be able to make further treatment like ordering the list by relevancy
     * @param $rp
     * TODO: inside this method the WP_Post object is dynamically modified because new attribute are created. A wrapper class should be created to represent the object rather than modifying the WP_POSt object.
     */
    private function add_related_content_to_list(WP_Post $rp){
        if(!isset($this->related_content_list[$rp->ID])){
            $rp->timestamp     = strtotime($rp->post_date);

            // Initializing some counters so we are able to order the list
            $rp->relevance     = 0;
            $rp->taxonomies    = [];

            // For each type of taxonomy we initialized a counter as well
            $taxonomies = get_taxonomies();
            foreach($taxonomies as $taxonomy)
            {
                $rp->taxonomies[$taxonomy] = 0;
            }

            $this->related_content_list[$rp->ID]     = $rp;
        }
    }

    /**
     * Debug function to display the content of the sort result
     */
    private function debug_sort(){
        if($this->debug) {
            $taxonomies = get_taxonomies();
            foreach ($this->related_content_list as $k => $rp) {
                $tax_debug = "";
                foreach ($taxonomies as $tax) {
                    if ($rp->taxonomies[$tax]) {
                        $tax_debug .= " {$tax}({$rp->taxonomies[$tax]}) ";
                    }
                }
                $permalink = htmlentities(get_the_permalink($rp));
                echo "<small><a href='{$permalink}'>{$rp->ID}</a> : timestamp({$rp->timestamp}) relevance({$rp->relevance}) {$tax_debug}</small><br />";
            }
        }
    }
}