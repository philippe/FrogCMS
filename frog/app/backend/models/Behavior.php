<?php

/**
 * class Behavior
 *
 * This is a part of the Plugin API of Frog CMS. It provide a "interface" to
 * add and remove behavior "page type" to Frog CMS.
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.5
 */

class Behavior
{
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
     * Find all active Behaviors id
     *
     * @return array
     */
    public static function findAll()
    {
        return array_keys(self::$behaviors);
    }

} // end Behavior class
