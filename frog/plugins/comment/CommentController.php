<?php

/**
   Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
   Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * class CommentController
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.6
 */

class CommentController extends PluginController
{
    function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));
        
        $this->setLayout('backend');
    }
    
    function index()
    {
        $this->display('comment/views/index', array(
            'comments' => Comment::findAll()
        ));
    }
    
    function edit($id=null)
    {
        if (is_null($id))
            redirect(get_url('plugin/comment'));
        
        if ( ! $comment = Comment::findById($id))
        {
            Flash::set('error', __('comment not found!'));
            redirect(get_url('plugin/comment'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_edit($id);
        
        // display things...
        $this->display('comment/views/edit', array(
            'action'  => 'edit',
            'comment' => $comment
        ));
    }
    
    function _edit($id)
    {
        $comment = Record::findByIdFrom('comment', $id);
        $comment->setFromData($_POST['comment']);
        
        if ( ! $comment->save())
        {
            Flash::set('error', __('Comment has not been saved!'));
            redirect(get_url('plugin/comment/edit/'.$id));
        }
        else Flash::set('success', __('Comment has been saved!'));
        
        redirect(get_url('plugin/comment'));
    }
    
    function delete($id)
    {
        // find the user to delete
        if ($comment = Record::findByIdFrom('Comment', $id))
        {
            if ($comment->delete())
                Flash::set('success', __('Comment has been deleted!'));
            else
                Flash::set('error', __('Comment has not been deleted!'));
        }
        else Flash::set('error', __('Comment not found!'));
        
        redirect(get_url('plugin/comment'));
    }
    
    function approve($id)
    {
        // find the user to approve
        if ($comment = Record::findByIdFrom('Comment', $id))
        {
            $comment->is_approved = 1;
            if ($comment->save())
                Flash::set('success', __('Comment has been approved!'));
        }
        else Flash::set('error', __('Comment not found!'));
        
        redirect(get_url('plugin/comment'));
    }
    
    function unapprove($id)
    {
        // find the user to unapprove
        if ($comment = Record::findByIdFrom('Comment', $id))
        {
            $comment->is_approved = 0;
            if ($comment->save())
                Flash::set('success', __('Comment has been unapproved!'));
        }
        else Flash::set('error', __('Comment not found!'));
        
        redirect(get_url('plugin/comment'));
    }

} // end CommentController class
