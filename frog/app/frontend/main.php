<?php

define('FRAMEWORK_STARTING_MICROTIME', get_microtime());

require APP_PATH . '/frontend/classes/Plugin.php'; // Setting, Plugin, Behavior and Filter classes
require APP_PATH . '/frontend/classes/Page.php';

if ( ! defined('HELPER_PATH')) define('HELPER_PATH', CORE_ROOT.'/helpers');
if ( ! defined('URL_SUFFIX')) define('URL_SUFFIX', '');

// Intialize Setting and Plugin
Setting::init();
Plugin::init();

/**
 * Load all functions from the helper file
 *
 * syntax:
 * use_helper('Cookie');
 * use_helper('Number', 'Javascript', 'Cookie', ...);
 *
 * @param  string helpers in CamelCase
 * @return void
 */
function use_helper()
{
    static $_helpers = array();
    
    $helpers = func_get_args();
    
    foreach ($helpers as $helper)
    {
        if (in_array($helper, $_helpers)) continue;
        
        $helper_file = HELPER_PATH.DIRECTORY_SEPARATOR.$helper.'.php';
        
        if ( ! file_exists($helper_file))
        {
            throw new Exception("Helper file '{$helper}' not found!");
        }
        
        include $helper_file;
        $_helpers[] = $helper;
    }
}

/**
 * Explode an URI and make a array of params
 */
function explode_uri($uri)
{
    return preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
}

function find_page_by_uri($uri)
{
    global $__FROG_CONN__;
    
    $uri = trim($uri, '/');
    
    $has_behavior = false;
    
    // adding the home root
    $urls = array_merge(array(''), explode_uri($uri));
    $url = '';
 
    $page = new stdClass;
    $page->id = 0;
    
    $parent = false;
    
    foreach ($urls as $page_slug)
    {
        $url = ltrim($url . '/' . $page_slug, '/');
        
        if ($page = find_page_by_slug($page_slug, $parent))
        {
            // check for behavior
            if ($page->behavior_id != '')
            {
                // add a instance of the behavior with the name of the behavior 
                $params = explode_uri(substr($uri, strlen($url)));
                $page->{$page->behavior_id} = Behavior::load($page->behavior_id, $page, $params);
                
                return $page;
            }
        }
        else
        {
            break;
        }
        
        $parent = $page;
        
    } // foreach
    
    return ( ! $page && $has_behavior) ? $parent: $page;
} // find_page_by_slug

function find_page_by_slug($slug, &$parent)
{
    global $__FROG_CONN__;
    
    $page_class = 'Page';
    
    $parent_id = $parent ? $parent->id: 0;
    
    $sql = 'SELECT page.*, creator.name AS created_by_name, updator.name AS updated_by_name '
         . 'FROM '.TABLE_PREFIX.'page AS page '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS creator ON creator.id = page.created_by_id '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
         . 'WHERE slug = ? AND parent_id = ? AND (status_id='.Page::STATUS_REVIEWED.' OR status_id='.Page::STATUS_PUBLISHED.' OR status_id='.Page::STATUS_HIDDEN.')';
    
    $stmt = $__FROG_CONN__->prepare($sql);
    
    $stmt->execute(array($slug, $parent_id));
    
    if ($page = $stmt->fetchObject())
    {
        // hook to be able to redefine the page class with behavior
        if ( ! empty($parent->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($parent->behavior_id);
        }
        
        // create the object page
        $page = new $page_class($page, $parent);
        
        // assign all is parts
        $page->part = get_parts($page->id);
        
        return $page;
    }
    else return false;
}

function get_parts($page_id)
{
    global $__FROG_CONN__;
    
    $objPart = new stdClass;
    
    $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE page_id=?';
    
    if ($stmt = $__FROG_CONN__->prepare($sql))
    {
        $stmt->execute(array($page_id));
        
        while ($part = $stmt->fetchObject())
            $objPart->{$part->name} = $part;
    }
    
    return $objPart;
}

function url_match($url)
{
    $url = trim($url, '/');
    
    if (CURRENT_URI == $url)
        return true;
    
    return false;
}
  
function url_start_with($url)
{
    $url = trim($url, '/');
    
    if (CURRENT_URI == $url)
        return true;
    
    if (strpos(CURRENT_URI, $url) === 0)
        return true;
    
    return false;
}

function execution_time()
{
    return sprintf("%01.4f", get_microtime() - FRAMEWORK_STARTING_MICROTIME);
}

function get_microtime()
{
    $time = explode(' ', microtime());
    return doubleval($time[0]) + $time[1];
}

function convert_size($num)
{
    if ($num >= 1073741824) $num = round($num / 1073741824 * 100) / 100 .' gb';
    else if ($num >= 1048576) $num = round($num / 1048576 * 100) / 100 .' mb';
    else if ($num >= 1024) $num = round($num / 1024 * 100) / 100 .' kb';
    else $num .= ' b';
    return $num;
}

function memory_usage()
{
    return convert_size(memory_get_usage());
}

function page_not_found()
{
    Observer::notify('page_not_found');
    
    include FROG_ROOT . '/404.php';
    exit;
}

function main()
{
    // get the uri string from the query
    $uri = $_SERVER['QUERY_STRING'];
    
    // real integration of GET
    if (strpos($uri, '?') !== false)
    {
        list($uri, $get_var) = explode('?', $uri);
        $exploded_get = explode('&', $get_var);
        
        if (count($exploded_get))
        {
            foreach ($exploded_get as $get)
            {
                list($key, $value) = explode('=', $get);
                $_GET[$key] = $value;
            }
        }
    }
    
    // remove suffix page if founded
    $uri = preg_replace('/^(.*)('.URL_SUFFIX.')$/i', "$1", $uri);
    
    define ('CURRENT_URI', trim($uri, '/'));
    
    // this is where 80% of the things is done 
    $page = find_page_by_uri($uri);
    
    // if we fund it, display it!
    if (is_object($page))
    {
        Observer::notify('page_found', $page);
        
        // check if we need to save a comment
        if (isset($_POST['comment']))
            $page->_saveComment($_POST['comment']);
        
        $page->_executeLayout();
    }
    else page_not_found();
    
} // main

// ok come on! let's go! (movie: Hacker's)
main();
