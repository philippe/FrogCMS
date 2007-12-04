<?php

class PageArchive extends Page
{
    protected function setUrl()
    {
        $this->url = trim($this->parent->url . date('/Y/m/d/', strtotime($this->created_on)). $this->slug, '/');
    }
}