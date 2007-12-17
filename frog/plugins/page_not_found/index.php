<?php

Plugin::setInfos(array(
    'id'          => 'page_not_found',
    'title'       => 'Page not found', 
    'description' => 'Provides Page not found page types.', 
    'version'     => '1.0', 
    'website'     => 'http://www.madebyfrog.com/')
);

Behavior::add('page_not_found', '');
Observer::observe('page_not_found', 'behavior_page_not_found');

function behavior_page_not_found()
{
    global $__FROG_CONN__;
    
    $sql = 'SELECT * FROM '.TABLE_PREFIX."page WHERE behavior_id='page_not_found'";
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();
    
    if ($page = $stmt->fetchObject())
    {
        $page = find_page_by_uri($page->slug);
        
        // if we fund it, display it!
        if (is_object($page))
        {
            header("HTTP/1.0 404 Not Found");
            header("Status: 404 Not Found");
              
            $page->_executeLayout();
            exit(); // need to exit here otherwise the true error page will be sended
        }
    }
}