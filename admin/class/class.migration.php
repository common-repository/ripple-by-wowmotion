<?php 

require_once(dirname(__FILE__) . '/class.singleton.php');

/**
 * The migration class is intended
 * - Initialize plugin option with default value
 * - To migrate the options from a version to another (if the storage structure change)
 */
class RippleMigration extends Singleton
{
    public function migrate()
    {
        // Then applying default options
        static::set_default_options();
    }

    /**
     * Get the current registered options and add missing keys from default value stored in config file
     */
    static private function set_default_options()
    {

        $default_options = ripple_get_default_options();

        /**
         * The plugin intend to store one option per key found at the root of the default options
         */
        foreach($default_options as $key => $default){
            // Get current plugin options
            $registered_options = get_option($key);
            if(is_array($registered_options)) {
                // Check removed key from default option
                $diff = self::array_diff_key_recursive($registered_options, $default);

                // Merging default options and the options which are already registered (to keep user settings)
                $merged_options = array_replace_recursive($default, $registered_options);

                // Removing outdated keys from the registered options
                $merged_options = self::array_diff_key_recursive($merged_options, $diff);

                // Updating the current registered options
                update_option($key, $merged_options);
            }
            else{
                // No registered option found for the plugin, we register the default value
                add_option($key, $default);
            }
        }
    }


    static private function array_diff_key_recursive (array $arr1, array $arr2) {
        $diff = array_diff_key($arr1, $arr2);
        $intersect = array_intersect_key($arr1, $arr2);
        foreach ($intersect as $k => $v) {
            if (is_array($arr1[$k]) && is_array($arr2[$k])) {
                $d = self::array_diff_key_recursive($arr1[$k], $arr2[$k]);
                if ($d) {
                    $diff[$k] = $d;
                }
            }
        }
        return $diff;
    }

}