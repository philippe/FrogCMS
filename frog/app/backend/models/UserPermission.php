<?php

class UserPermission extends Record
{
    const TABLE_NAME = 'user_permission';
    
    public $user_id = false;
    public $permission_id = false;
    
    public static function setPermissionsFor($user_id, $perms)
    {
        // remove all perms of this user
        $sql = 'DELETE FROM user_permission WHERE user_id='.(int)$user_id;
        self::$__CONN__->exec($sql);
        
        // add the new perms
        foreach ($perms as $perm_name => $perm_id)
        {
            $sql = 'INSERT INTO user_permission (user_id, permission_id)
                    VALUES ('.(int)$user_id.','.(int)$perm_id.')';
            self::$__CONN__->exec($sql);
        }
    }
}
