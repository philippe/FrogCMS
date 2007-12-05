<?php

class PageArchive extends Page
{
    protected function setUrl()
    {
        $this->url = trim($this->parent->url . date('/Y/m/d/', strtotime($this->created_on)). $this->slug, '/');
    }
    
    public function title() { return isset($this->time) ? strftime($this->title, $this->time): $this->title; }
    
    public function breadcrumb() { return isset($this->time) ? strftime($this->breadcrumb, $this->time): $this->breadcrumb; }
}