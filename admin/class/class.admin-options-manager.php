<?php


/**
 * This class intend to simplify the manipulation of the options for one admin page :
 * - retrieve default value of an option
 * - retrieve the actual value of an option
 * - Generate the HTML name of the form inputs
 */
// TODO this class can evolve to provide a method to generate the option HTML field
// TODO : this class can evolve so it can be used by the widget itsefl on the front end side. It implies that this class is able to manage default option and
class AdminOptionManager {

    private $option_slug;

    /**
     * The constuctor only ask for the slug of the option.
     * This slug will be used to create a new entry in the Wp "options" table
     * @var $option_name
     */
    public function __construct($option_slug)
    {
        // The admin page id is used to set an option entry in the WP option table
        $this->option_slug = $option_slug;

        // Create the option if it doesn't exist yet on the WP side
        $this->create_option();
    }

    /**
     * Create the option in the Wp option table if it does not exist
     */
    private function create_option()
    {
        $current_option = $this->get_options();
        if(!$current_option || empty($current_option)){
            update_option($this->option_slug, $this->get_default_option());
        }
    }

    /**
     * Retrieve the default value to store in the option
     */
    public function get_default_option()
    {
        $ripple_default_options = unserialize(RIPPLE_DEFAULT_OPTIONS);
        return isset($ripple_default_options[$this->option_slug]) ? $ripple_default_options[$this->option_slug] : [];

    }

    /**
     * Return the actual registered option
     * @return mixed
     */
    public function get_options(){
        $options = get_option( $this->option_slug );
        if($options){
            return $options;
        }
        return false;
    }

    /**
     * This function allow to store values under the "option_slug" option of the Wp table
     * Type of value :
     * - The value to store can be either a scalar value (a String a serialize object)
     * - Or it can be an array. In such a case, the method apply a merge with the value already stored and the new value.
     * @param mixed $to_store - The new value to store
     * @return bool
     */
    public function store_option($to_store)
    {
        $option = is_array($to_store) ? array_replace_recursive($this->get_options(), $to_store) : $to_store;
        return update_option($this->option_slug, $option);
    }

    /**
     * This method allow to build an input html name/id based on several parameters
     * The option named return will look like :
     *   ->   "the_option_slug['param1]['param2']['param3]"
     * If no parameters given, the method simply the option_slug as the HTML name
     * @return String
     */
    public function html_name()
    {
        $html_name = $this->option_slug;
        if (func_num_args() > 0) {
            for ($i = 0; $i < func_num_args(); $i++) {
                $args = func_get_arg($i);
                $html_name .= "[{$args}]";
            }
        }
        return $html_name;
    }


    /**
     * This method allow to retrieve the value of an option defined in the global container of options.
     * The global container of all options is an array that may have several levels of depth
     * THe method accept has many arguments needed, where each arguments represent a level of the options tree
     * Ex :
     * If $options = [ "first_level" : [ "second_level" : [ "value1" = 1, "value2" = 2 ]] ]
     * Then you can retrieve "value2" options by calling  <?php $this->option_manager->option_value("first_level", "second_level", "value2"); ?>
     */
    public function option_value()
    {
        // The method need at least one arguments
        if (func_num_args() > 0) {
            // Get the options container where to find the specific options
            $tmp_options = $this->get_options();
            // Browsing the options tree arguments by arguments
            for ($i = 0; $i < func_num_args(); $i++) {
                $args = func_get_arg($i);
                if (is_array($tmp_options) && array_key_exists($args, $tmp_options)) {
                    $tmp_options = $tmp_options[func_get_arg($i)];
                } else {
                    $tmp_options = false;
                }
            }
            return $tmp_options;
        }
        return false;
    }

}