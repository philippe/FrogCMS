<?php

class Comment
{
    function name($class='')
    {
        if ($this->author_link != '')
        {
            return sprintf(
                '<a class="%s" href="%s" title="%s">%s</a>',
                $class,
                $this->author_link,
                $this->author_name,
                $this->author_name
            );
        }
        else
        {
            return $this->author_name;
        }
    } // name

    function email() { return $this->author_email; }
    function link() { return $this->author_link; }
    function body() { return $this->body; }

    function date($format='%a, %e %b %Y')
    {
        return strftime($format, strtotime($this->created_on));
    } // date
    
} // Comment
