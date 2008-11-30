<?php

/*
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The Comment plugin provides an interface to enable adding and moderating page comments.
 *
 * @package frog
 * @subpackage plugin.comment
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Bebliuc George <bebliuc.george@gmail.com>
 * @version 1.1.0
 * @since Frog version 0.9.3
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault & Bebliuc George, 2008
 */

/**
 * class CommentController
 *
 * @package frog
 * @subpackage plugin.comment
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since Frog version 0.6
 */
class CommentController extends PluginController
{
    function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));
        
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/comment/views/sidebar'));
    }
    
    function index($page = 0)
    {
        $this->display('comment/views/index', array(
            'comments' => Comment::findAll(),
            'page' => $page
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
        
        redirect(get_url('plugin/comment/moderation'));
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
   
    function settings() {
    	
    	error_reporting(E_ALL);
    	
		global $__FROG_CONN__;
    	$sql = "SELECT * FROM ".TABLE_PREFIX."setting WHERE name = 'auto_approve_comment'";
		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute();
		$auto_approve = $stmt->fetchObject();
        
        $sql = "SELECT * FROM ".TABLE_PREFIX."setting WHERE name = 'use_captcha'";
		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute();
		$captcha = $stmt->fetchObject();
		
		$sql = "SELECT * FROM ".TABLE_PREFIX."setting WHERE name = 'rowspage'";
		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute();
		$rowspage = $stmt->fetchObject();
		
            $this->display('comment/views/settings', array(
				'approve' => $auto_approve->value,
				'captcha' => $captcha->value,
				'rowspage' => $rowspage->value
				));
		
    }
    
	function save() {
		error_reporting(E_ALL);
		$approve = mysql_escape_string($_POST['autoapprove']);
        $captcha = mysql_escape_string($_POST['captcha']);
        $rowspage = mysql_escape_string($_POST['rowspage']);
        
        global $__FROG_CONN__;
        $sql = "UPDATE " . TABLE_PREFIX . "setting SET value='$approve' WHERE name = 'auto_approve_comment'"; 
        $PDO = $__FROG_CONN__->prepare($sql);
        $approve_var = $PDO->execute() !== false;
        
        $sql = "UPDATE " . TABLE_PREFIX . "setting SET value='$captcha' WHERE name = 'use_captcha'"; 
        $PDO = $__FROG_CONN__->prepare($sql);
        $captcha_var = $PDO->execute() !== false;
        
        $sql = "UPDATE " . TABLE_PREFIX . "setting SET value='$rowspage' WHERE name = 'rowspage'"; 
        $PDO = $__FROG_CONN__->prepare($sql);
        $rowspage_var = $PDO->execute() !== false;
        
        if ($captcha_var){
                Flash::set('success', __('The settings have been updated. :)'));
            }
        else{
                Flash::set('error', 'An error has occured. Seems MySQL hates you. :(');
            }
           redirect(get_url('plugin/comment/settings'));   
	}
	
	function documentation() {
    	$this->display('comment/views/documentation'); 
    }
    function moderation($page = 0) {
    	 $this->display('comment/views/moderation', array(
            'comments' => Comment::findAll(),
            'page' => $page
        ));
    }
} // end CommentController class
