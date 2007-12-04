<?php

if (!defined('I18N_PATH'))      define('I18N_PATH',   APP_PATH.DIRECTORY_SEPARATOR.'i18n');
define('DEFAULT_LOCALE', 'en');

/**
 * I18n : Internationalisation function and class
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @copyright 2007 Philippe Archambault
 * @package Frog
 * @version 0.1
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */


/**
 * this function is the must permisive as possible, you cand chose your own pattern for vars in 
 * the string, it could be ':var_name', '#var_name', '{varname}', '%varname', '%varname%', 'VARNAME' ...
 *
 *
 * return = array('hello world!' => 'bonjour le monde!',
 *                'user ":user" is logged in' => 'l\'utilisateur ":user" est connecté',
 *                'Posted by %user% on %month% %day% %year% at %time%' => 'Publié par %user% le %day% %month% %year% à %time%'
 *               );
 *
 * __('hello world!'); // bonjour le monde!
 * __('user ":user" is logged in', array(':user' => $user)); // l'utilisateur "demo" est connecté
 * __('Posted by %user% on %month% %day% %year% at %time%', array(
 *      '%user%' => $user, 
 *      '%month%' => __($month), 
 *      '%day%' => $day, 
 *      '%year%' => $year, 
 *      '%time%' => $time)); // Publié par demo le 3 janvier 2006 à 19:30
 */
function __($string, $args=null, $catalog='message')
{
    if (I18n::getLocale() != DEFAULT_LOCALE) {
        $string = I18n::getText($string, $catalog);
    }
    
    if ($args === null) return $string;
    
    return strtr($string, $args);
}

class I18n 
{
    private static $locale = DEFAULT_LOCALE;
    private static $catalogs = array();
    
    public static function setLocale($locale)
    {
        self::$locale = $locale;
    }
    
    public static function getLocale()
    {
        return self::$locale;
    }
    
    public static function getText($string, $catalog='message')
    {
        if ( ! isset(self::$catalogs[$catalog])) {
            self::loadCatalog($catalog);
        }
            
        $i18n =& self::$catalogs[$catalog];
        return isset($i18n[$string]) ? $i18n[$string] : $string;
    }
    
    public static function loadCatalog($catalog)
    {
        $catalog_file = I18N_PATH.DIRECTORY_SEPARATOR.self::$locale.'-'.$catalog.'.php';
        
        // assign returned value of catalog file
        if (file_exists($catalog_file)) {
            // will return a array (source => traduction)
            self::$catalogs[$catalog] = include $catalog_file;
        } else {
            self::$catalogs[$catalog] = array();
        }
    }

} // end I18n class
