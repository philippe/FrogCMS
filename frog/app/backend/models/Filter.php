<?php

/**
   Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
   Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
   Class Filter

   This is a part of the Plugin API of Frog CMS. It provide a "interface" to
   add a new filter to the Frog CMS administration.

   Since  0.5
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

