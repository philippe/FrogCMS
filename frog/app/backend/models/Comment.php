<?php

class Comment extends Record
{
    const TABLE_NAME = 'comment';
    
    public static function find($args = null)
    {
        // Collect attributes...
        $where    = isset($args['where']) ? trim($args['where']) : '';
        $order_by = isset($args['order']) ? trim($args['order']) : 'is_approved, created_on DESC';
        $offset   = isset($args['offset']) ? (int) $args['offset'] : 0;
        $limit    = isset($args['limit']) ? (int) $args['limit'] : 0;
        
        // Prepare query parts
        $where_string = empty($where) ? '' : "AND $where";
        $order_by_string = empty($order_by) ? '' : "ORDER BY $order_by";
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
        $tablename = self::tableNameFromClassName('Comment');
        $tablename_page = self::tableNameFromClassName('Page');
        
        // Prepare SQL
        $sql = "SELECT comment.*, page.title AS page_title FROM $tablename AS comment, $tablename_page AS page ".
               "WHERE comment.page_id = page.id $where_string $order_by_string $limit_string";
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();
        
        // Run!
        if ($limit == 1)
        {
            return $stmt->fetchObject('Comment');
        }
        else
        {
            $objects = array();
            while ($object = $stmt->fetchObject('Comment'))
                $objects[] = $object;
            
            return $objects;
        }
    }
    
    public static function findAll($args = null)
    {
        return self::find($args);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => 'comment.id='.(int)$id,
            'limit' => 1
        ));
    }
    
} // end Comment class
