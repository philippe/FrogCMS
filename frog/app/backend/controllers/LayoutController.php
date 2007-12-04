<?php

/**
 * class LayoutsController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class LayoutController extends Controller
{
    
    function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        else if ( ! AuthUser::hasPermission('administrator') && ! AuthUser::hasPermission('developer'))
        {
            Flash::set('error', __('You don\'t have permissions to access requested page!'));
            redirect(get_url());
        }
        
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('layout/sidebar'));
    }


    function index()
    {
        $this->display('layout/index', array(
            'layouts' => Record::findAllFrom('Layout')
        ));
    } // index


    function add()
    {
        // check if trying to save
        if (get_request_method() == 'POST')
        {
            return $this->_add();
        }
        
        // check if user have already enter something
        $layout = Flash::get('post_data');
        
        if (empty($layout))
        {
            $layout = new Layout;
        }

        $this->display('layout/edit', array(
            'action'  => 'add',
            'layout' => $layout
        ));
    } // add


    function _add()
    {
        $data = $_POST['layout'];
        Flash::set('post_data', (object) $data);
        
        $layout = new Layout($data);
        
        if ($layout->save($data))
        {
            Flash::set('success', __('Layout has been added!'));
        } else
        {
            Flash::set('error', __('Layout has not been added. Name must be unique!'));
            redirect(get_url('layout/add'));
        }

        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
        {
            redirect(get_url('layout'));
        }
        else
        {
            redirect(get_url('layout/edit/'.$layout->id));
        }
    } // _add


    function edit($id=null)
    {
        if (is_null($id)) redirect(get_url('layout/add'));
    
        $layout = Layout::findById($id);

        if ( ! $layout)
        {
            Flash::set('error', __('Layout not found!'));
            redirect(get_url('layout'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
        {
            return $this->_edit($id);
        }
        
        // display things...
        $this->display('layout/edit', array(
            'action'  => 'edit',
            'layout' => $layout
        ));
    } // edit


    function _edit($id)
    {
        $layout = Record::findByIdFrom('Layout', $id);
        $layout->setFromData($_POST['layout']);

        if ($layout->save())
        {
            Flash::set('success', __('Layout has been saved!'));
        }
        else
        {
            Flash::set('error', __('Layout has not been saved. Name must be unique!'));
            redirect(get_url('layout/edit/'.$id));
        }

        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
        {
            redirect(get_url('layout'));
        }
        else
        {
            redirect(get_url('layout/edit/'.$id));
        }
    } // _edit


    function delete($id)
    {
        // if no id set redirect to index layouts
        if (is_null($id))
            redirect(getUrl('layout'));

        // find the user to delete
        if ($layout = Record::findByIdFrom('Layout', $id))
        {
            if ($layout->isUsed())
            {
                Flash::set('error', __('Layout <b>:name</b> is used! It CAN NOT be deleted!', array(':name'=>$layout->name)));
            }
            else if ($layout->delete())
            {
                Flash::set('success', __('Layout <b>:name</b> has been deleted!', array(':name'=>$layout->name)));
            }
            else
            {
                Flash::set('error', __('Layout <b>:name</b> has not been deleted!', array(':name'=>$layout->name)));
            }
        }
        else
        {
            Flash::set('error', __('Layout not found!'));
        }

        redirect(get_url('layout'));
    } // delete

} // end LayoutController class
