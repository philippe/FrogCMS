<?php

class Statistic extends Record
{
    const TABLE_NAME = 'statistic';
    
    public static function view($page_id = null)
    {
        return self::count($page_id, '');
    }
    
    public static function viewForToday($page_id = null)
    {
        $where = "created_on >= '".date('Y-m-d')."'";
        
        return self::count($page_id, $where);
    }
    
    public static function viewForYesterday($page_id = null)
    {
        $where = "created_on <= '".date('Y-m-d')."' AND created_on >= '".date('Y-m-d', strtotime('-1 day'))."'";
        
        return self::count($page_id, $where);
    }
    
    public static function unique($page_id = null)
    {
        return self::count($page_id, '', 'DISTINCT ip');
    }
    
    public static function uniqueForToday($page_id = null)
    {
        $where = "created_on >= '".date('Y-m-d')."'";
        
        return self::count($page_id, $where, 'DISTINCT ip');
    }
    
    public static function uniqueForYesterday($page_id = null)
    {
        $where = "created_on <= '".date('Y-m-d')."' AND created_on >= '".date('Y-m-d', strtotime('-1 day'))."'";
        
        return self::count($page_id, $where, 'DISTINCT ip');
    }
    
    public static function count($page_id, $where='', $distinct='*')
    {
        $where = $where != '' ? 'WHERE '.$where: '';
        if ($page_id !== null) 
            $where = $where != '' ? $where.' AND page_id = '.(int)$page_id: ' WHERE page_id = '.(int)$page_id;
            
        $sql = 'SELECT COUNT('.$distinct.') AS nb_rows FROM '.self::tableNameFromClassName('Statistic').' '.$where;
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();
        
        return (int) $stmt->fetchColumn();
    }
}
