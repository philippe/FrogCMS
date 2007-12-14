<?php

/**
 * class Setting
 *
 * Setting implementation for frontend
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.8.7
 */

class Setting
{
    public static $settings = array();
    public static $is_loaded = false;
    
    public static function init()
    {
        global $__FROG_CONN__;
        
        if (! self::$is_loaded)
        {
            $stmt = $__FROG_CONN__->prepare('SELECT * FROM '.TABLE_PREFIX.'setting');
            $stmt->execute();
            $settings = $stmt->fetchAll();
            
            foreach($settings as $setting)
                self::$settings[$setting['name']] = $setting['value'];
            
            self::$is_loaded = true;
        }
    }
    
    /**
     * Get the value of a setting
     *
     * @param name  string  The setting name
     * @return string the value of the setting name
     */
    public static function get($name)
    {
        return isset(self::$settings[$name]) ? self::$settings[$name]: false;
    }

} // end Setting class

/**
 * class Plugin
 *
 * Provide a Plugin API to make frog more flexible
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.9
 */

class Plugin
{
    static $plugins = array();

    static function init()
    {
        self::$plugins = unserialize(Setting::get('plugins'));
        foreach (self::$plugins as $plugin => $tmp)
        {
            $file = CORE_ROOT.'/plugins/'.$plugin.'/index.php';
            if (file_exists($file))
                include $file;
        }
    }
    
    static function setInfos($infos) {}
    static function addController($plugin_name, $label) {}

} // end Plugin Class

/**
 * class Behavior
 *
 * This is a part of the Plugin API of Frog CMS. It provide a "interface" to
 * add and remove behavior "page type" to Frog CMS.
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.9
 */

class Behavior
{
    private static $loaded_files = array();
    private static $behaviors = array();
    
    /**
     * Add a new behavior to Frog CMS
     *
     * @param behavior_id string  The Behavior plugin folder name
     * @param file      string  The file where the Behavior class is
     */
    public static function add($behavior_id, $file)
    {
        self::$behaviors[$behavior_id] = $file;
    }
    
    /**
     * Remove a behavior to Frog CMS
     *
     * @param behavior_id string  The Behavior plugin folder name
     */
    public static function remove($behavior_id)
    {
        if (isset(self::$behaviors[$behavior_id]))
            unset(self::$behaviors[$behavior_id]);
    }
    
    /**
     * Load a behavior and return it
     *
     * @param behavior_id string  The Behavior plugin folder name
     * @param page        object  Will be pass to the behavior
     * @param params      array   Params that fallow the page with this behavior (passed to the behavior too)
     *
     * @return object
     */
    public static function load($behavior_id, &$page, $params)
    {
        if ( ! empty(self::$behaviors[$behavior_id]))
        {
            $file = CORE_ROOT.'/plugins/'.self::$behaviors[$behavior_id];
            
            if (isset(self::$loaded_files[$file]))
                return new $behavior_id($page, $params);
            
            if (file_exists($file))
            {
                include $file;
                self::$loaded_files[$file] = true;
                return new $behavior_id($page, $params);
            }
            else
            {
                exit ("Behavior $behavior_id not found!");
            }
        }
    }
    
    /**
     * Load a behavior and return it
     *
     * @param behavior_id string  The Behavior plugin folder name
     *
     * @return string   class name of the page
     */
    public static function loadPageHack($behavior_id)
    {
        $behavior_page_class = 'Page'.str_replace(' ','',ucwords(str_replace('_',' ', $behavior_id)));

        if (class_exists($behavior_page_class))
            return $behavior_page_class;
        else
            return 'Page';
    }
    
} // end Behavior class


/**
 * class Filter
 *
 * Only use for administration, this class will drop all request
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.9
 */

class Filter
{
    public static function add($filter_id, $file) {}
    public static function remove($filter_id) {}
}

/**
 * class Observer
 *
 * Only use for administration, this class will drop all request
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.9
 */

final class Observer
{
    static protected $events = array();
    
    public static function observe($event_name, $callback)
    {
        if ( ! isset(self::$events[$event_name]))
            self::$events[$event_name] = array();
        
        self::$events[$event_name][$callback] = $callback;
    }
    
    public static function stopObserving($event_name, $callback)
    {  
        if (isset(self::$events[$event_name][$callback]))
            unset(self::$events[$event_name][$callback]);
    }
    
    public static function clearObservers($event_name)
    {
        self::$events[$event_name] = array();
    }
    
    public static function getObserverList($event_name)
    {
        return (isset(self::$events[$event_name])) ? self::$events[$event_name] : array();
    }
    
    /**
     * If your event does not need to process the return values from any observers use this instead of getObserverList()
     */
    public static function notify($event_name)
    {
        $args = array_slice(func_get_args(), 1); // removing event name from the arguments
        
        foreach(self::getObserverList($event_name) as $callback)
            call_user_func_array($callback, $args);
    }

} // end Observer Class