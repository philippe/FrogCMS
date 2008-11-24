<?php

/*
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @package frog
 * @subpackage models
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault, 2008
 */

/**
 * Class Behavior
 *
 * This is a part of the Plugin API of Frog CMS. It provide a "interface" to
 * add and remove behavior "page type" to Frog CMS.
 *
 * @since Frog version 0.5
 */
class Behavior
{
    private static $behaviors = array();
    
    /**
       Add a new behavior to Frog CMS

       param:
         behavior_id string  The Behavior plugin folder name
         file        string  The file where the Behavior class is
     */
    public static function add($behavior_id, $file)
    {
        self::$behaviors[$behavior_id] = $file;
    }
    
    /**
       Remove a behavior to Frog CMS

       param:
         behavior_id string  The Behavior plugin folder name
     */
    public static function remove($behavior_id)
    {
        if (isset(self::$behaviors[$behavior_id]))
            unset(self::$behaviors[$behavior_id]);
    }
    
    /**
       Find all active Behaviors id

       return array
     */
    public static function findAll()
    {
        return array_keys(self::$behaviors);
    }

} // end Behavior class
