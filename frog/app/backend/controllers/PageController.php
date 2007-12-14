<?php

/**
 * class PagesController
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class PageController extends Controller
{
    public function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));
    }
    
    public function index()
    {
        $this->setLayout('backend');
        $this->display('page/index', array(
            'root' => Record::findByIdFrom('Page', 1),
            'content_children' => $this->children(1, 0, true)
        ));
    }
    
    public function add($parent_id=1)
    {
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_add();
        
        $data = Flash::get('post_data');
        $page = new Page($data);
        $page->parent_id = $parent_id;
        $page->status_id = Setting::get('default_status_id');
        
        $page_parts = Flash::get('post_parts_data');
        
        if (empty($page_parts))
        {
            // check if we have a big sister ...
            $big_sister = Record::findOneFrom('Page', 'parent_id=? ORDER BY id DESC', array($parent_id));
            if ($big_sister)
            {
                // get all is part and create the same for the new little sister
                $big_sister_parts = Record::findAllFrom('PagePart', 'page_id=? ORDER BY id', array($big_sister->id));
                $page_parts = array();
                foreach ($big_sister_parts as $parts)
                {
                    $page_parts[] = new PagePart(array(
                        'name' => $parts->name,
                        'filter_id' => Setting::get('default_filter_id')
                    ));
                }
            }
            else
                $page_parts = array(new PagePart(array('filter_id' => Setting::get('default_filter_id'))));
        }
        
        // display things ...
        $this->setLayout('backend');
        $this->display('page/edit', array(
            'action'     => 'add',
            'page'       => $page,
            'tags'       => array(),
            'filters'    => Filter::findAll(),
            'behaviors'  => Behavior::findAll(),
            'page_parts' => $page_parts,
            'layouts'    => Record::findAllFrom('Layout'))
        );
    }
    
    private function _add()
    {
        $data = $_POST['page'];
        Flash::set('post_data', (object) $data);
        
        if (empty($data['title']))
        {
            Flash::set('error', __('You have to specify a title!'));
            redirect(get_url('page/add'));
        }
        
        $page = new Page($data);
        
        // save page data
        if ($page->save())
        {
            // get data from user
            $data_parts = $_POST['part'];
            Flash::set('post_parts_data', (object) $data_parts);
            
            foreach ($data_parts as $data)
            {
                $data['page_id'] = $page->id;
                $data['name'] = trim($data['name']);
                $page_part = new PagePart($data);
                $page_part->save();
            }
            
            // save tags
            $page->saveTags($_POST['page_tag']['tags']);
            
            Flash::set('success', __('Page has been saved!'));
        }
        else
        {
            Flash::set('error', __('Page has not been saved!'));
            redirect(get_url('page/add'));
        }
        
        // save and quit or save and continue editing ?
        if (isset($_POST['commit']))
            redirect(get_url('page'));
        else
            redirect(get_url('page/edit/'.$page->id));
    }
    
    public function addPart()
    {
        header('Content-Type: text/html; charset: utf-8');
        
        $data = isset($_POST['part']) ? $_POST['part']: array();
        $data['name'] = isset($data['name']) ? trim($data['name']): '';
        $data['index'] = isset($data['index']) ? $data['index']: 1;
        
        echo $this->_getPartView($data['index'], $data['name']);
    }
    
    public function edit($id=null)
    {
        if (is_null($id))
            redirect(get_url('page'));
        
        $page = Page::findById($id);
        
        if ( ! $page)
        {
            Flash::set('error', __('Page not found!'));
            redirect(get_url('page'));
        }
        
        // check for protected page and editor user
        if ( ! AuthUser::hasPermission('administrator') && ! AuthUser::hasPermission('developer') && $page->is_protected)
        {
            Flash::set('error', __('You don\'t have permissions to access requested page!'));
            redirect(get_url('page'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_edit($id);
        
        // find all page_part of this pages
        $page_parts = PagePart::findByPageId($id);
        
        if (empty($page_parts))
            $page_parts = array(new PagePart);
        
        // display things ...
        $this->setLayout('backend');
        $this->display('page/edit', array(
            'action'     => 'edit',
            'page'       => $page,
            'tags'       => $page->getTags(),
            'filters'    => Filter::findAll(),
            'behaviors'  => Behavior::findAll(),
            'page_parts' => $page_parts,
            'layouts'    => Record::findAllFrom('Layout'))
        );
    }
    
    private function _edit($id)
    {
        $data = $_POST['page'];
        
        $page = Record::findByIdFrom('Page', $id);
        
        // need to do this because the use of a checkbox
        $data['is_protected'] = !empty($data['is_protected']) ? 1: 0;
        
        $page->setFromData($data);
        
        if ($page->save())
        {
            // get data for parts of this page
            $data_parts = $_POST['part'];
            
            $old_parts = PagePart::findByPageId($id);
            
            // check if all old page part are passed in POST
            // if not ... we need to delete it!
            foreach ($old_parts as $old_part)
            {
                $not_in = true;
                foreach ($data_parts as $part_id => $data)
                {
                    $data['name'] = trim($data['name']);
                    if ($old_part->name == $data['name'])
                    {
                        $not_in = false;
                        
                        $part = new PagePart($data);
                        $part->page_id = $id;
                        $part->save();
                        
                        unset($data_parts[$part_id]);
                        
                        break;
                    }
                }
                
                if ($not_in)
                    $old_part->delete();
            }
            
            // add the new ones
            foreach ($data_parts as $part_id => $data)
            {
                $data['name'] = trim($data['name']);
                $part = new PagePart($data);
                $part->page_id = $id;
                $part->save();
            }
            
            // save tags
            $page->saveTags($_POST['page_tag']['tags']);
            
            Flash::set('success', __('Page has been saved!'));
        }
        else
        {
            Flash::set('error', __('Page has not been saved!'));
            redirect(get_url('page/edit/'.$id));
        }
        
        // save and quit or save and continue editing ?
        if (isset($_POST['commit']))
            redirect(get_url('page'));
        else
            redirect(get_url('page/edit/'.$id));
    }
    
    public function delete($id)
    {
        // security (dont delete the root page)
        if ($id > 1)
        {
            // find the user to delete
            if ($page = Record::findByIdFrom('Page', $id))
            {
                // check for permission to delete this page
                if ( ! AuthUser::hasPermission('administrator') && ! AuthUser::hasPermission('developer') && $page->is_protected)
                {
                    Flash::set('error', __('You don\'t have permissions to access requested page!'));
                    redirect(get_url('page'));
                }
                
                // need to delete all page_parts too !!
                PagePart::deleteByPageId($id);
                
                if ($page->delete())
                    Flash::set('success', __('Page :title as been deleted!', array(':title'=>$page->title)));
                else
                    Flash::set('error', __('Page :title as not been deleted!', array(':title'=>$page->title)));
            }
            else Flash::set('error', __('Page not found!'));
        }
        else Flash::set('error', __('Action disabled!'));
        
        redirect(get_url('page'));
    }
    
    function children($parent_id, $level, $return=false)
    {
        $expanded_rows = isset($_COOKIE['expanded_rows']) ? explode(',', $_COOKIE['expanded_rows']): array();
        
        // get all children of the page (parent_id)
        $childrens = Page::childrenOf($parent_id);
        
        foreach ($childrens as $index => $child)
        {
            $childrens[$index]->has_children = Page::hasChildren($child->id);
            $childrens[$index]->is_expanded = in_array($child->id, $expanded_rows);
            
            if ($childrens[$index]->is_expanded)
                $childrens[$index]->children_rows = $this->children($child->id, $level+1, true);
        }
        
        $content = new View('page/children', array(
            'childrens' => $childrens,
            'level'    => $level+1,
        ));
        
        if ($return)
            return $content;
        
        echo $content;
    }
    
    /**
     * Ajax action to reorder (page->position) a page
     *
     * all the child of the new page->parent_id have to be updated
     * and all nested tree has to be rebuild
     */
    function reorder($parent_id)
    {
        parse_str($_POST['data']);
        
        $new_brother = false;
        
        foreach ($pages as $position => $page_id)
        {
            $page = Record::findByIdFrom('Page', $page_id);
            $page->position = (int) $position;
            $page->parent_id = (int) $parent_id;
            $page->save();
            
        }
    }
    
    // Private methods -------------------------------------------------------
    
    function _getPartView($index=1, $name='', $filter_id='', $content='')
    {
        $page_part = new PagePart(array(
            'name' => $name, 
            'filter_id' => $filter_id, 
            'content' => $content)
        );
        
        return $this->render('page/part_edit', array(
            'index'     => $index,
            'page_part' => $page_part
        ));
    }

} // end PageController class
