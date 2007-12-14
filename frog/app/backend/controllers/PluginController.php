<?php

/**
 * class PluginController
 *
 * Plugin controller to dispatch to all plugins controllers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.9
 */

class PluginController extends Controller
{
    public $plugin;
    
    function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));
    }
    
    public function render($view, $vars=array())
    {
        if ($this->layout)
        {
            $this->layout_vars['content_for_layout'] = new View('../../../plugins/'.$view, $vars);
            return new View('../layouts/'.$this->layout, $this->layout_vars);
        }
        else return new View('../../../plugins/'.$view, $vars);
    }
    
    public function execute($action, $params)
    {
        if (isset(Plugin::$controllers[$action]))
        {
            $plugin = Plugin::$controllers[$action];
            if (file_exists($plugin->file))
            {
                include $plugin->file;
                
                $plugin_controller = new $plugin->class_name;
                
                $action = count($params) ? array_shift($params): 'index';
                
                call_user_func_array(
                    array($plugin_controller, $action),
                    $params
                );
            }
            else throw new Exception("Plugin controller file '{$plugin->file}' was not found!");
        }
        else throw new Exception("Action '{$action}' is not valid!");
    }
    
} // end PluginController class