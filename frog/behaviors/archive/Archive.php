<?php

class Archive
{
    public function __construct(&$page, $params)
    {
        $this->page =& $page;
        $this->params = $params;
        
        switch(count($params))
        {
            case 0: break;
            case 1:
                if (strlen((int) $params[0]) == 4)
                    $this->_archiveBy('year', $params);
            break;
            
            case 2:
                $this->_archiveBy('month', $params);
            break;
            
            case 3:
                $this->_archiveBy('day', $params);
            break;
            
            case 4:
                $this->_displayPage($params[3]);
            break;
        }
    }
    
    private function _archiveBy($interval, $params)
    {
        $this->interval = $interval;
        
        global $__FROG_CONN__;
        
        $page = $this->page->children(array(
            'where' => "behavior_id = 'archive_{$interval}_index'",
            'limit' => 1
        ), array(), true);
        
        if ($page)
        {
            $this->page = $page;
            $month = isset($params[1]) ? $params[1]: 1;
            $day = isset($params[2]) ? $params[2]: 1;
            $time = mktime(0, 0, 0, $month, $day, $params[0]);
            // need to replace %B, %Y, ... here in the title
            $this->page->title = strftime($this->page->title, $time);
            $this->page->breadcrumb = strftime($this->page->breadcrumb, $time);
        }
        else
        {
            page_not_found();
        }
    }
    
    private function _displayPage($slug)
    {
        if ( ! $this->page = find_page_by_slug($slug, $this->page))
            page_not_found();
    }
    
    function get()
    {
        $date = join('-', $this->params);
        
        $pages = $this->page->parent->children(array(
            'where' => "page.created_on LIKE '{$date}%'",
            'order' => 'page.created_on DESC'
        ));
        return $pages;
    }
    
    function archivesByMonth($year='all')
    {
        global $__FROG_CONN__;
        
        $out = array();
        
        if ($year == 'all') $year = '';
        
        $sql = "SELECT DISTINCT(DATE_FORMAT(created_on, '%Y/%m')) FROM ".TABLE_PREFIX."page WHERE parent_id=? AND status_id != ".Page::STATUS_HIDDEN." ORDER BY created_on DESC";
        
        $stmt = $__FROG_CONN__->prepare($sql);
        
        $stmt->execute(array($this->page->id));
        
        while ($date = $stmt->fetchColumn())
        {
            $out[] = $date;
        }
        
        return $out;
    }

}