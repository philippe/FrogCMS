<?php

class Setting extends Record
{
    const TABLE_NAME = 'setting';
    
    public $name;
    public $value;
    
    public static $settings = array();
    public static $is_loaded = false;
    
    public static function load()
    {
        if (! self::$is_loaded)
        {
            $settings = Record::findAllFrom('Setting');
            foreach($settings as $setting)
                self::$settings[$setting->name] = $setting->value;
            
            self::$is_loaded = true;
        }
    }
    
    public static function get($name)
    {
        return isset(self::$settings[$name]) ? self::$settings[$name]: false;
    }
    
    public static function saveFromData($data)
    {
        $tablename = self::tableNameFromClassName('Setting');
        foreach ($data as $name => $value)
        {
            $sql = 'UPDATE '.$tablename.' SET value='.self::$__CONN__->quote($value)
                 . ' WHERE name='.self::$__CONN__->quote($name);
            self::$__CONN__->exec($sql);
        }
    }
}
