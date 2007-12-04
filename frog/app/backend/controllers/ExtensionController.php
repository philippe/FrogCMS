<?php 

/**
 * class ExtensionController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.5
 */

class ExtensionController extends Controller
{

    function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        
        $this->setLayout('backend');
    } // __construct
    
    function index()
    {
        if ( ! AuthUser::hasPermission('administrator'))
        {
            Flash::set('error', __('You don\'t have permissions to access requested page!'));
            redirect(get_url());
        }
        
        $this->display('user/index', array(
            'users' => User::findAll()
        ));
    } // index
    
    function enable($extension)
    {
        if (AuthUser::hasPermission('administrator')) {
            // ...
        }
    }
    
    function disable($extension)
    {
        if (AuthUser::hasPermission('administrator')) {
            // ...
        }
    }
    
    function dispatch()
    {
        $args = func_get_args();
        $extension_class = Inflector::camelize($args[0]);
        $extension_method = $args[1];
        $params = array_slice($args, 2);
        
        $extension = new $extension_class();
        
        if (substr($extension_method, 0, 1) == '_' || ! method_exists($extension, $extension_method))
        {
            throw new Exception("Action '{$extension_method}' is not valid!");
        }
        call_user_func_array(array($extension, $extension_method), $params);
    }
    
} // end ExtensionController class
