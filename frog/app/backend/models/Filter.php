<?php

/**
 * class Filter
 *
 * This is a part of the Plugin API of Frog CMS. It provide a "interface" to
 * add a new filter to the Frog CMS administration.
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.5
 */

class Filter
{
    static $filters = array();
    private static $filters_loaded = array();
    
    /**
     * Add a new filter to Frog CMS
     *
     * @param filter_id string  The Filter plugin folder name
     * @param file      string  The file where the Filter class is
     */
    public static function add($filter_id, $file)
    {
        self::$filters[$filter_id] = $file;
    }
    
    /**
     * Remove a filter to Frog CMS
     *
     * @param filter_id string  The Filter plugin folder name
     */
    public static function remove($filter_id)
    {
        if (isset(self::$filters[$filter_id]))
            unset(self::$filters[$filter_id]);
    }
    
    /**
     * Find all active filters id
     *
     * @return array
     */
    public static function findAll()
    {
        return array_keys(self::$filters);
    }
    
    /**
     * Get a instance of a filter
     *
     * @param filter_id string  The Filter plugin folder name
     *
     * @return mixed   if founded an object, else false
     */
    public static function get($filter_id)
    {
        if ( ! isset(self::$filters_loaded[$filter_id]))
        {
            if (isset(self::$filters[$filter_id]))
            {
                $file = CORE_ROOT.'/plugins/'.self::$filters[$filter_id];
                if (file_exists($file))
                {
                    include $file;
                    
                    $filter_class = Inflector::camelize($filter_id);
                    self::$filters_loaded[$filter_id] = new $filter_class();
                    return self::$filters_loaded[$filter_id];
                }
            }
            else return false;
        }
        else return self::$filters_loaded[$filter_id];
    }
    
} // end Filter class

