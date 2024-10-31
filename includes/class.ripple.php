<?php


class RippleFrontEnd
{

    public function __construct()
    {
        // Creating the instance of the breadcrumbs widgets
        $this->breadcrumbs_widgets_manager();

        // Creating the instance of the semantic related content widgets
        $this->semantic_related_content_widgets_manager();

        // Creating the instance of the hierarchical related content widgets
        $this->hierarchical_related_content_widgets_manager();

    }

    /**
     * This function intend to be the global manager for all breadcrumbs widgets
     * The widget can be displayed either automatically or with a shortcode :
     * in each case an instance of widget is needed as they have different configuration
     * TODO : make it a manager class (which will also handle the options + shortcode  function)
     */
    private function breadcrumbs_widgets_manager()
    {
        // Initializing a widget to manage the automatic display
        $widget = new RippleBreadcrumbWidget(ripple_get_breadcrumbs_options());
        add_filter('the_content', array($widget, 'automatic_display' ));

        // Initializing shortcode usage
        add_shortcode(RippleBreadcrumbWidget::get_shortcode_name(), array($this, 'do_ripple_breadcrumbs_shortcode'));
    }


    /**
     * This function intend to be the global manager for all semantic widgets
     * The widget can be displayed either automatically or with a shortcode :
     * in each case an instance of widget is needed as they have different configuration
     * TODO : make it a manager class (which will also handle the options + shortcode function)
     */
    private function semantic_related_content_widgets_manager()
    {
        // Initializing a widget to manage the automatic display
        $widget = new RippleSemanticRelatedContentWidget(ripple_get_semantic_related_content_options());
        add_filter('the_content', array($widget, 'automatic_display' ));

        // Initializing shortcode usage
        add_shortcode(RippleSemanticRelatedContentWidget::get_shortcode_name(), array($this, 'do_ripple_semantic_related_content_shortcode'));
    }

    /**
     * This function intend to be the global manager for all semantic widgets
     * The widget can be displayed either automatically or with a shortcode :
     * in each case an instance of widget is needed as they have different configuration
     * TODO : make it a manager class (which will also handle the options + shortcode  function)
     */
    private function hierarchical_related_content_widgets_manager()
    {
        // Initializing a widget to manage the automatic display
        $widget = new RippleHierarchicalRelatedContentWidget(ripple_get_hierarchical_related_content_options());
        add_filter('the_content', array($widget, 'automatic_display' ));

        // Initializing shortcode usage
        add_shortcode(RippleHierarchicalRelatedContentWidget::get_shortcode_name(), array($this, 'do_ripple_hierarchical_related_content_shortcode'));
    }



    /**
     * Executed when using the breadcrumbs shortcode
     * @param array $atts
     * @param null $content
     * @return String
     */
    public function do_ripple_breadcrumbs_shortcode($atts = [], $content = null)
    {
        $widget_options = $this->normalize_shortocde_atts(ripple_get_breadcrumbs_options(), $atts);
        $widget = new RippleBreadcrumbWidget($widget_options);
        return $widget->do_shortcode();
    }

    /**
     * Executed when using the semantic widget shortcode
     * @param array $atts
     * @param null $content
     * @return String
     */
    public function do_ripple_semantic_related_content_shortcode($atts = [], $content = null)
    {
        $widget_options = $this->normalize_shortocde_atts(ripple_get_semantic_related_content_options(), $atts);
        $widget = new RippleSemanticRelatedContentWidget($widget_options);
        return $widget->do_shortcode();
    }

    /**
     * Executed when using the hierarchical widget shortcode
     * @param array $atts
     * @param null $content
     * @return String
     */
    public function do_ripple_hierarchical_related_content_shortcode($atts = [], $content = null)
    {
        $widget_options = $this->normalize_shortocde_atts(ripple_get_hierarchical_related_content_options(), $atts);
        $widget = new RippleHierarchicalRelatedContentWidget($widget_options);
        return $widget->do_shortcode();
    }

    /**
     * Normalize attributes given when calling a shortcode and combine them with the default values
     * in order to have a complete set of attribute required for the shortcode usage
     * @param $default_atts
     * @param array $atts
     * @return array
     */
    private function normalize_shortocde_atts($default_atts, $atts = [])
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // override default attributes with user attributes
        $atts = shortcode_atts($default_atts, $atts);

        return $atts;
    }


}
