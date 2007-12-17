<?php

/**
 * class Page
 *
 * apply methodes for page, layout and snippet of a page
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 *
 * -- TAGS --
 * id()
 * title()
 * breadcrumb()
 * author()
 * slug()
 * url()
 *
 * link([label], [class])
 * date([format])
 *
 * hasContent(part_name)
 * content([part_name], [inherit])
 * breadcrumbs([between])
 *
 * children([arguments :limit :offset :order])
 * find(url)
 
 todo:
 
 <r:navigation />

 Renders a list of links specified in the urls attribute according to three states:

 normal specifies the normal state for the link
 here specifies the state of the link when the url matches the current page’s URL
 selected specifies the state of the link when the current page matches is a child of the specified url
 The between tag specifies what should be inserted in between each of the links.

 Usage:
 <r:navigation urls="[Title: url | Title: url | ...]">
   <r:normal><a href="<r:url />"><r:title /></a></r:normal>
   <r:here><strong><r:title /></strong></r:here>
   <r:selected><strong><a href="<r:url />"><r:title /></a></strong></r:selected>
   <r:between> | </r:between>
 </r:navigation>
 
 **/

class Page
{
    const STATUS_DRAFT = 1;
    const STATUS_REVIEWED = 50;
    const STATUS_PUBLISHED = 100;
    const STATUS_HIDDEN = 101;
    
    public $id;
    public $title = '';
    public $breadcrumb;
    public $author;
    public $author_id;
    public $updator;
    public $updator_id;
    public $slug = '';
    public $url = '';
    
    public $parent = false;
    public $level = false;
    public $tags = false;
    
    public function __construct($object, $parent)
    {
        $this->parent = $parent;
        
        foreach ($object as $key => $value) {
            $this->$key = $value;
        }
        
        if ($this->parent)
        {
            $this->setUrl();
        }
    }
    
    protected function setUrl()
    {
        $this->url = trim($this->parent->url .'/'. $this->slug, '/');
    }
    
    public function id() { return $this->id; }
    public function title() { return $this->title; }
    public function breadcrumb() { return $this->breadcrumb; }
    public function author() { return $this->author; }
    public function authorId() { return $this->author_id; }
    public function updator() { return $this->updator; }
    public function updatorId() { return $this->updator_id; }
    public function slug() { return $this->slug; }
    public function url() { return BASE_URL . $this->url . ($this->url != '' ? URL_SUFFIX: ''); }
    
    public function level()
    {
        if ($this->level === false)
            $this->level = empty($this->url) ? 0 : substr_count($this->url, '/')+1;
        
        return $this->level;
    }
    
    public function tags()
    {
        if ( ! $this->tags)
            $this->_loadTags();
            
        return $this->tags;
    }
    
    public function link($label=null, $options='')
    {
        if ($label == null)
            $label = $this->title();
        
        return sprintf('<a href="%s" title="%s" %s>%s</a>',
               $this->url(),
               $this->title(),
               $options,
               $label
        );
    }
    
    /**
     * http://php.net/strftime
     * exemple (can be useful):
     *  '%a, %e %b %Y'      -> Wed, 20 Dec 2006 <- (default)
     *  '%A, %e %B %Y'      -> Wednesday, 20 December 2006
     *  '%B %e, %Y, %H:%M %p' -> December 20, 2006, 08:30 pm
     */
    public function date($format='%a, %e %b %Y', $which_one='created')
    {
        if ($which_one == 'update' || $which_one == 'updated')
            return strftime($format, strtotime($this->updated_on));
        else if ($which_one == 'publish' || $which_one == 'published')
            return strftime($format, strtotime($this->published_on));
        else
            return strftime($format, strtotime($this->created_on));
    }
    
    public function breadcrumbs($separator='&gt;')
    {
        $url = '';
        $path = '';
        $paths = explode('/', '/'.$this->slug);
        $nb_path = count($paths);
        
        $out = '<div class="breadcrumb">'."\n";
        
        if ($this->parent)
            $out .= $this->parent->_inversedBreadcrumbs($separator);
        
        return $out . '<span class="breadcrumb-current">'.$this->breadcrumb().'</span></div>'."\n";
        
    }
    
    public function hasContent($part) { return isset($this->part->$part); }
    
    public function content($part='body', $inherit=false)
    {
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part))
        {
            ob_start();
            eval('?>'.$this->part->$part->content_html);
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        }
        else if ($inherit && $this->parent)
        {
            return $this->parent->content($part, true);
        }
    }
    
    public function children($args=null, $value=array(), $include_hidden=false)
    {
        global $__FROG_CONN__;
        
        $page_class = 'Page';
        
        // Collect attributes...
        $where   = isset($args['where']) ? $args['where']: '';
        $order   = isset($args['order']) ? $args['order']: 'page.position, page.id';
        $offset  = isset($args['offset']) ? $args['offset']: 0;
        $limit   = isset($args['limit']) ? $args['limit']: 0;
        
        // auto offset generated with the page param
        if ($offset == 0 && isset($_GET['page']))
            $offset = ((int)$_GET['page'] - 1) * $limit;
        
        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND ".$where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
        // Prepare SQL
        $sql = 'SELECT page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id '
             . 'FROM '.TABLE_PREFIX.'page AS page '
             . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
             . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
             . 'WHERE parent_id = '.$this->id.' AND (status_id='.Page::STATUS_REVIEWED.' OR status_id='.Page::STATUS_PUBLISHED.($include_hidden ? ' OR status_id='.Page::STATUS_HIDDEN: '').') '
             . "$where_string ORDER BY $order $limit_string";
        
        $pages = array();
        
        // hack to be able to redefine the page class with behavior
        if ( ! empty($this->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($this->behavior_id);
        }
        
        // Run!
        if ($stmt = $__FROG_CONN__->prepare($sql))
        {
            $stmt->execute($value);
            
            while ($object = $stmt->fetchObject())
            {
                $page = new $page_class($object, $this);
                
                // assignParts
                $page->part = get_parts($page->id);
                $pages[] = $page;
            }
        }
        
        if ($limit == 1)
            return isset($pages[0]) ? $pages[0]: false;
        
        return $pages;
    }
    
    public function childrenCount($args=null, $value=array(), $include_hidden=false)
    {
        global $__FROG_CONN__;
        
        // Collect attributes...
        $where   = isset($args['where']) ? $args['where']: '';
        $order   = isset($args['order']) ? $args['order']: 'position, id';
        $limit   = isset($args['limit']) ? $args['limit']: 0;
        $offset  = 0;
        
        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND ".$where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
        // Prepare SQL
        $sql = 'SELECT COUNT(*) AS nb_rows FROM '.TABLE_PREFIX.'page '
             . 'WHERE parent_id = '.$this->id.' AND (status_id='.Page::STATUS_REVIEWED.' OR status_id='.Page::STATUS_PUBLISHED.($include_hidden ? ' OR status_id='.Page::STATUS_HIDDEN: '').') '
             . "$where_string ORDER BY $order $limit_string";
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute($values);
        
        return (int) $stmt->fetchColumn();
    }
    
    public function find($uri) { return find_page_by_uri($uri); }
    
    public function parent($level=null)
    {
        if ($level === null)
            return $this->parent;
        
        if ($level > $this->level)
            return false;
        else if ($this->level == $level)
            return $this;
        else
            return $this->parent($level);
    }
    
    public function comments()
    {
        global $__FROG_CONN__;
        
        $comments = array();
        $sql = 'SELECT * FROM '.TABLE_PREFIX.'comment WHERE is_approved=1 AND page_id=?';
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($this->id));
        
        while ($comment = $stmt->fetchObject('Comment')) {
            $comments[] = $comment;
        }
        return $comments;
    }
    
    public function commentsCount()
    {
        global $__FROG_CONN__;
        
        $sql = 'SELECT COUNT(id) AS num FROM '.TABLE_PREFIX.'comment WHERE is_approved=1 AND page_id=?';
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($this->id));
        $obj = $stmt->fetchObject();
        
        return (int) $obj->num;
    }
    
    public function includeSnippet($name)
    {
        global $__FROG_CONN__;
        
        $sql = 'SELECT content_html FROM '.TABLE_PREFIX.'snippet WHERE name LIKE ?';
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($name));
        
        if ($snippet = $stmt->fetchObject())
        {
            eval('?>'.$snippet->content_html);
        }
    }
    
    public function executionTime()
    {
        return execution_time();
    }
    
    // Private --------------------------------------------------------------
    
    private function _inversedBreadcrumbs($separator)
    {
        $out = '<a href="'.$this->url().'" title="'.$this->breadcrumb.'">'.$this->breadcrumb.'</a> <span class="breadcrumb-separator">'.$separator.'</span> '."\n";
    
        if ($this->parent)
            return $this->parent->_inversedBreadcrumbs($separator) . $out;
        
        return $out;
    }
     
    public function _executeLayout()
    {
        global $__FROG_CONN__;
        
        $sql = 'SELECT content_type, content FROM '.TABLE_PREFIX.'layout WHERE id = ?';
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($this->_getLayoutId()));
        
        if ($layout = $stmt->fetchObject())
        {
            // if content-type not set, we set html as default
            if ($layout->content_type == '')
                $layout->content_type = 'text/html';
            
            // set content-type and charset of the page
            header('Content-Type: '.$layout->content_type.'; charset=UTF-8');
            
            // execute the layout code
            eval('?>'.$layout->content);
        }
    }
    
    /**
     * find the layoutId of the page where the layout is set
     */
    private function _getLayoutId()
    {
        if ($this->layout_id)
            return $this->layout_id;
        else if ($this->parent)
            return $this->parent->_getLayoutId();
        else
            exit ('You need to set a layout!');
    }
    
    private function _loadTags()
    {
        global $__FROG_CONN__;
        $this->tags = array();
        
        $sql = "SELECT tag.id AS id, tag.name AS tag FROM ".TABLE_PREFIX."page_tag AS page_tag, ".TABLE_PREFIX."tag AS tag ".
               "WHERE page_tag.page_id={$this->id} AND page_tag.tag_id = tag.id";
        
        if ( ! $stmt = $__FROG_CONN__->prepare($sql))
            return;
            
        $stmt->execute();
        
        // Run!
        while ($object = $stmt->fetchObject())
             $this->tags[$object->id] = $object->tag;
    }
    
    public function _saveComment($data)
    {
        global $__FROG_CONN__;
        
        if ($this->comment_status != 'open') return;
        
        $data = $_POST['comment'];
        
        if (is_null($data)) return;
        if ( ! isset($data['author_name']) || trim($data['author_name']) == '') return;
        if ( ! isset($data['author_email']) || trim($data['author_email']) == '') return;
        if ( ! isset($data['body']) || trim($data['body']) == '') return;
        
        use_helper('Kses');
        
        $allowed_tags = array(
            'a' => array(
                'href' => array(),
                'title' => array()
                ),
            'abbr' => array(
                'title' => array()
                ),
            'acronym' => array(
                'title' => array()
                ),
            'b' => array(),
            'blockquote' => array(
                'cite' => array()
                ),
            'br' => array(),
            'code' => array(),
            'em' => array(),
            'i' => array(),
            'p' => array(),
            'strike' => array(),
            'strong' => array()
        );
        
        // get the setting for comments moderations
        $sql = 'SELECT value FROM '.TABLE_PREFIX.'setting WHERE name=\'auto_approve_comment\'';
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute();
        $auto_approve_comment = (int) $stmt->fetchColumn();
        
        $sql = 'INSERT INTO '.TABLE_PREFIX.'comment (page_id, author_name, author_email, author_link, body, is_approved, created_on) VALUES ('.
                    '\''.$this->id.'\', '.
                    $__FROG_CONN__->quote(strip_tags($data['author_name'])).', '.
                    $__FROG_CONN__->quote(strip_tags($data['author_email'])).', '.
                    $__FROG_CONN__->quote(strip_tags($data['author_link'])).', '.
                    $__FROG_CONN__->quote(kses($data['body'], $allowed_tags)).', '.
                    $__FROG_CONN__->quote($auto_approve_comment).', '.
                    $__FROG_CONN__->quote(date('Y-m-d H:i:s')).')';
        
        $__FROG_CONN__->exec($sql);
    }
    
} // end Page class

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
        else return $this->author_name;
    }
    
    function email() { return $this->author_email; }
    function link() { return $this->author_link; }
    function body() { return $this->body; }
    
    function date($format='%a, %e %b %Y')
    {
        return strftime($format, strtotime($this->created_on));
    }
    
} // end Comment class