<?php

/**
 * class CommentController
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.6
 */

class CommentController extends Controller
{
    function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));
        
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('comment/sidebar'));
    }
    
    function index()
    {
        $this->display('comment/index', array(
            'comments' => Comment::findAll()
        ));
    }
    
    function edit($id=null)
    {
        if (is_null($id))
            redirect(get_url('comment'));
        
        if ( ! $comment = comment::findById($id))
        {
            Flash::set('error', __('comment not found!'));
            redirect(get_url('comment'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_edit($id);
        
        // display things...
        $this->display('comment/edit', array(
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
            redirect(get_url('comment/edit/'.$id));
        }
        else Flash::set('success', __('Comment has been saved!'));
        
        redirect(get_url('comment'));
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
        
        redirect(get_url('comment'));
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
        
        redirect(get_url('comment'));
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
        
        redirect(get_url('comment'));
    }

} // end CommentController class
