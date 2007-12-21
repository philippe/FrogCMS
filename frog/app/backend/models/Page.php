<?php

/**
 * class Page
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class Page extends Record
{
    const TABLE_NAME = 'page';
    
    const STATUS_DRAFT = 1;
    const STATUS_REVIEWED = 50;
    const STATUS_PUBLISHED = 100;
    const STATUS_HIDDEN = 101;
    
    public $title;
    public $slug;
    public $breadcrumb;
    public $content;
    public $parent_id;
    public $layout_id;
    public $behavior_id;
    public $status_id;
    public $comment_status;
    
    public $created_on;
    public $published_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    public $position;
    public $is_protected;
    
    public function beforeInsert()
    {
        $this->created_by_id = AuthUser::getId();
        $this->created_on = date('Y-m-d H:i:s');
        
        if ($this->status_id == Page::STATUS_PUBLISHED)
            $this->created_on = date('Y-m-d H:i:s');
        
        return true;
    }
    
    public function beforeUpdate()
    {
        $this->created_on = $this->created_on . ' ' . $this->created_on_time;
        unset($this->created_on_time);
        
        if ( ! empty($this->published_on))
        {
            $this->published_on = $this->published_on . ' ' . $this->published_on_time;
            unset($this->published_on_time);
        }
        else if ($this->status_id == Page::STATUS_PUBLISHED)
        {
            $this->published_on = date('Y-m-d H:i:s');
        }
        
        $this->updated_by_id = AuthUser::getId();
        $this->updated_on = date('Y-m-d H:i:s');
        
        return true;
    }
    
    public function getTags()
    {
        $tablename_page_tag = self::tableNameFromClassName('PageTag');
        $tablename_tag = self::tableNameFromClassName('Tag');
        
        $sql = "SELECT tag.id AS id, tag.name AS tag FROM $tablename_page_tag AS page_tag, $tablename_tag AS tag ".
               "WHERE page_tag.page_id={$this->id} AND page_tag.tag_id = tag.id";
        
        if ( ! $stmt = self::$__CONN__->prepare($sql))
            return array();
            
        $stmt->execute();
        
        // Run!
        $tags = array();
        while ($object = $stmt->fetchObject())
             $tags[$object->id] = $object->tag;
        
        return $tags;
    }
    
    public function saveTags($tags)
    {
        if (is_string($tags))
            $tags = explode(',', $tags);
        
        $tags = array_map('trim', $tags);
        
        $current_tags = $this->getTags();
        
        // no tag before! no tag now! ... nothing to do!
        if (count($tags) == 0 && count($current_tags) == 0)
            return;
        
        // delete all tags
        if (count($tags) == 0)
        {
            $tablename = self::tableNameFromClassName('Tag');
            
            // update count (-1) of those tags
            foreach($current_tags as $tag)
                self::$__CONN__->exec("UPDATE $tablename SET count = count - 1 WHERE name = '$tag'");
            
            return Record::deleteWhere('PageTag', 'page_id=?', array($this->id));
        }
        else
        {
            $old_tags = array_diff($current_tags, $tags);
            $new_tags = array_diff($tags, $current_tags);
            
            // insert all tags in the tag table and then populate the page_tag table
            foreach ($new_tags as $index => $tag_name)
            {
                if ( ! empty($tag_name))
                {
                    // try to get it from tag list, if not we add it to the list
                    if ( ! $tag = Record::findOneFrom('Tag', 'name=?', array($tag_name)))
                        $tag = new Tag(array('name' => trim($tag_name)));
                    
                    $tag->count++;
                    $tag->save();
                    
                    // create the relation between the page and the tag
                    $tag = new PageTag(array('page_id' => $this->id, 'tag_id' => $tag->id));
                    $tag->save();
                }
            }
            
            // remove all old tag
            foreach ($old_tags as $index => $tag_name)
            {
                // get the id of the tag
                $tag = Record::findOneFrom('Tag', 'name=?', array($tag_name));
                Record::deleteWhere('PageTag', 'page_id=? AND tag_id=?', array($this->id, $tag->id));
                $tag->count--;
                $tag->save();
            }
        }
    }
    
    public static function find($args = null)
    {
        
        // Collect attributes...
        $where    = isset($args['where']) ? trim($args['where']) : '';
        $order_by = isset($args['order']) ? trim($args['order']) : '';
        $offset   = isset($args['offset']) ? (int) $args['offset'] : 0;
        $limit    = isset($args['limit']) ? (int) $args['limit'] : 0;
        
        // Prepare query parts
        $where_string = empty($where) ? '' : "WHERE $where";
        $order_by_string = empty($order_by) ? '' : "ORDER BY $order_by";
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
        $tablename = self::tableNameFromClassName('Page');
        $tablename_user = self::tableNameFromClassName('User');
        
        // Prepare SQL
        $sql = "SELECT page.*, creator.name AS created_by_name, updator.name AS updated_by_name FROM $tablename AS page".
               " LEFT JOIN $tablename_user AS creator ON page.created_by_id = creator.id".
               " LEFT JOIN $tablename_user AS updator ON page.updated_by_id = updator.id".
               " $where_string $order_by_string $limit_string";
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();
        
        // Run!
        if ($limit == 1)
        {
            return $stmt->fetchObject('Page');
        }
        else
        {
            $objects = array();
            while ($object = $stmt->fetchObject('Page'))
                $objects[] = $object;
            
            return $objects;
        }
    }
    
    public static function findAll($args = null)
    {
        return self::find($args);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => 'page.id='.(int)$id,
            'limit' => 1
        ));
    }
    
    public static function childrenOf($id)
    {
        return self::find(array('where' => 'parent_id='.$id, 'order' => 'position, page.created_on DESC'));
    }
    
    public static function hasChildren($id)
    {
        return (boolean) self::countFrom('Page', 'parent_id = '.(int)$id);
    }
    
} // end Page class
