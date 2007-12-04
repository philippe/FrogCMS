<?php

/**
 * class Behavior
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1.4
 */

class Behavior
{
    private static $loaded_files = array();
    
    public static function load($behavior_name, &$page, $params)
    {
        $behavior_class_name = Behavior::classNameize($behavior_name);
        $file = CORE_ROOT.'/behaviors/'.$behavior_name.'/'.$behavior_class_name.'.php';
        
        if (isset(self::$loaded_files[$file]))
            return new $behavior_class_name($page, $params);
        
        if (file_exists($file))
        {
            include $file;
            self::$loaded_files[$file] = true;
            return new $behavior_class_name($page, $params);
        }
        else
        {
            exit ("Behavior $behavior_name not found!");
        }
    }
    
    public static function loadPageHack($behavior_name)
    {
        $behavior_class_name = 'Page'.Behavior::classNameize($behavior_name);
        $file = CORE_ROOT.'/behaviors/'.$behavior_name.'/'.$behavior_class_name.'.php';
        
        if (isset(self::$loaded_files[$file]))
            return self::$loaded_files[$file];
        
        if (file_exists($file))
        {
            include $file;
            self::$loaded_files[$file] = $behavior_class_name;
            return $behavior_class_name;
        }
        else
        {
            return 'Page';
        }
    }
    
    public static function classNameize($behavior_name)
    {
        return str_replace(' ','',ucwords(str_replace('_',' ', $behavior_name)));
    }
    
} // Behavior
