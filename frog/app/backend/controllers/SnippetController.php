<?php

/**
 * class SnippetsController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class SnippetController extends Controller
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
        $this->assignToLayout('sidebar', new View('snippet/sidebar'));
    }


    function index()
    {
        $this->display('snippet/index', array(
            'snippets' => Record::findAllFrom('Snippet')
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
        $snippet = Flash::get('post_data');
        
        if (empty($snippet))
        {
            $snippet = new Snippet;
        }

        $this->display('snippet/edit', array(
            'action'  => 'add',
            'filters' => Filter::findAll(),
            'snippet' => $snippet
        ));
    } // add


    function _add()
    {
        $data = $_POST['snippet'];
        Flash::set('post_data', (object) $data);
        
        $snippet = new Snippet($data);
        
        if ($snippet->save())
        {
            Flash::set('success', __('Snippet has been added!'));
        }
        else
        {
            Flash::set('error', __('Snippet has not been added. Name must be unique!'));
            redirect(get_url('snippet', 'add'));
        }

        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
        {
            redirect(get_url('snippet'));
        }
        else
        {
            redirect(get_url('snippet/edit/'.$snippet->id));
        }
    } // _add


    function edit($id=null)
    {
        if (is_null($id))
            redirect(get_url('snippets/add'));
        
        $snippet = Snippet::findById($id);
        
        if ( ! $snippet)
        {
            Flash::set('error', __('Snippet not found!'));
            redirect(get_url('snippet'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
        {
            return $this->_edit($id);
        }
        
        $this->display('snippet/edit', array(
            'action'  => 'edit',
            'filters' => Filter::findAll(),
            'snippet' => $snippet
        ));
    } // edit


    function _edit($id)
    {
        $data =$_POST['snippet'];
        
        $data['id'] = $id;
        
        $snippet = new Snippet($data);

        if ($snippet->save())
        {
            Flash::set('success', __('Snippet :name has been saved!', array(':name'=>$snippet->name)));
        }
        else
        {
            Flash::set('error', __('Snippet :name has not been saved. Name must be unique!', array(':name'=>$snippet->name)));
            redirect(get_url('snippet/edit/'.$id));
        }

        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
        {
            redirect(get_url('snippet'));
        }
        else
        {
            redirect(get_url('snippet/edit/'.$id));
        }
    } // _edit


    function delete($id=null)
    {
        // if no id set redirect to index snippets
        if (is_null($id))
            redirect(getUrl('snippet'));

        // find the user to delete
        if ($snippet = Record::findByIdFrom('Snippet', $id))
        {
            if ($snippet->delete())
            {
                Flash::set('success', __('Snippet :name has been deleted!', array(':name'=>$snippet->name)));
            }
            else
            {
                Flash::set('error', __('Snippet :name has not been deleted!', array(':name'=>$snippet->name)));
            }
        }
        else
        {
            Flash::set('error', __('Snippet not found!'));
        }

        redirect(get_url('snippet'));
    } // delete

} // end SnippetController class
