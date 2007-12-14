<?php

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
