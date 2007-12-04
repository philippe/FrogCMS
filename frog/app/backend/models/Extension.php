<?php 

/**
 * class Extension 
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.5
 */

class Extension
{
    public static function findAll()
    {
        $extensions = array();
        if ($handle = opendir(CORE_ROOT.'extensions'))
        {
            while (false !== ($file = readdir($handle)))
            {
                // bug fix: ignore hidden files (strating with a .) and both (. and ..)
                if (strpos($file, '.') !== 0 && is_dir($file))
                {
                    if (file_exists($file.'/infos.php'))
                        $extensions[$file] = include $file.'/infos.php';
                }
            }
            closedir($handle);
        }
        return $extensions;
    }
}
