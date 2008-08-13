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
 * class UserPermission
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.5
 */

class UserPermission extends Record
{
    const TABLE_NAME = 'user_permission';
    
    public $user_id = false;
    public $permission_id = false;
    
    public static function setPermissionsFor($user_id, $perms)
    {
        $tablename = self::tableNameFromClassName('UserPermission');
        
        // remove all perms of this user
        $sql = 'DELETE FROM '.$tablename.' WHERE user_id='.(int)$user_id;
        self::$__CONN__->exec($sql);
        
        // add the new perms
        foreach ($perms as $perm_name => $perm_id)
        {
            $sql = 'INSERT INTO '.$tablename.' (user_id, permission_id) VALUES ('.(int)$user_id.','.(int)$perm_id.')';
            self::$__CONN__->exec($sql);
        }
    }

} // end UserPermission class
