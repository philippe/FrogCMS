<?php

/**
 * Template class
 *
 * @version 0.2
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Template
{

    public $template;         // String of template file
    private $_vars = array(); // Array of template variables

    /**
     * Assign the template path
     *
     * @param string $template Template path (absolute path or path relative to the templates dir)
     * @return void
     */
    public function __construct($template_path)
    {
        $this->template = $template_path;
    }

    /**
     * Assign specific variable to the template
     *
     * @param mixed $name Variable name
     * @param mixed $value Variable value
     * @return void
     */
    public function assign($name, $value=null)
    {
        if (is_array($name)) {
            foreach($name as $n => $v) {
                $this->_vars[$n] = $v;
            }
        } else {
            $this->_vars[$name] = $value;
        }
    } // assign

    /**
     * Display template and return output as string
     *
     * @return string content of compiled template
     */
    public function fetch()
    {
        ob_start();
        if ($this->_includeTemplate()) {
            return ob_get_clean();
        }
        ob_end_clean();
    } // fetch

    /**
     * Display template
     *
     * @return boolean
     */
    public function display()
    {
        return $this->_includeTemplate();
    } // display

    /**
     * Include specific template
     *
     * @return boolean
     */
    private function _includeTemplate()
    {
        if (file_exists($this->template)) {
            extract($this->_vars, EXTR_SKIP);
            include $this->template;
            return true;
        }
        return false;
    } // _includeTemplate

} // End Template class
