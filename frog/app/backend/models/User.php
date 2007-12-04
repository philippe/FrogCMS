<?php 

/**
 * class User 
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class User extends Record
{
    const TABLE_NAME = 'user';
    
    public $name = '';
    public $email = '';
    public $username = '';
    
    public $created_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    
    public function getPermissions()
    {
        if ( !isset($this->id))
        {
            return array();
        }
        
        $perms = array();
        $sql = 'SELECT name FROM '.self::tableNameFromClassName('Permission').' AS permission, '. self::tableNameFromClassName('UserPermission')
             . ' WHERE permission_id = permission.id AND user_id='.$this->id;
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();
         
        while ($perm = $stmt->fetchObject())
        {
            $perms[] = $perm->name;
        }
        
        return $perms;
    }
    
    public static function findBy($column, $value)
    {
        return Record::findOneFrom('User', $column.' = ?', array($value));
    }
    
    public function beforeInsert()
    {
        $this->created_by_id = AuthUser::getId();
        $this->created_on = date('Y-m-d H:i:s');
        return true;
    }
    
    public function beforeUpdated()
    {
        $this->updated_by_id = AuthUser::getId();
        $this->updated_on = date('Y-m-d H:i:s');
        return true;
    }
    
    public static function find($args = null)
    {
        
        // Collect attributes...
        $where    = isset($args['where']) ? trim($args['where']) : '';
        $order_by = isset($args['order']) ? trim($args['order']) : '';
        $offset   = isset($args['offset']) ? (int) $args['offset'] : 0;
        $limit    = isset($args['limit']) ? (int) $args['limit'] : 0;

        // Prepare query parts
        $where_string = empty($where) ? '' : "WHERE $where";
        $order_by_string = empty($order_by) ? '' : "ORDER BY $order_by";
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

        $tablename = self::tableNameFromClassName('User');

        // Prepare SQL
        $sql = "SELECT $tablename.*, creator.name AS created_by_name, updator.name AS updated_by_name FROM $tablename".
               " LEFT JOIN $tablename AS creator ON $tablename.created_by_id = creator.id".
               " LEFT JOIN $tablename AS updator ON $tablename.updated_by_id = updator.id".
               " $where_string $order_by_string $limit_string";

        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();

        // Run!
        if ($limit == 1)
        {
            return $stmt->fetchObject('User');
        }
        else
        {
            $objects = array();
            while ($object = $stmt->fetchObject('User'))
            {
                $objects[] = $object;
            }
            return $objects;
        }
    
    } // find
    
    public static function findAll($args = null)
    {
        return self::find($args);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => self::tableNameFromClassName('User').'.id='.(int)$id,
            'limit' => 1
        ));
    }
    
} // end User class
