<?php 

class TranslateController extends Controller {
    
    public function __construct() {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));
        
        $this->assignToLayout('sidebar', new View('translate/sidebar'));
    }

    function index() {
        $this->setLayout('backend');
        $this->display('translate/index');
    }

    function core() {
        $complete = array();
        $basedir = FROG_ROOT.'';
        $dirs = $this->listdir($basedir);

        foreach ($dirs as $id => $path) {
            $tmp = array();
            $strings = array();
            $fsize = filesize($path);
            
            if ($fsize > 0) {
                $fh = fopen($path, 'r');
                $data = fread($fh, $fsize);
                fclose($fh);

                if (strpos($data, '__(\'')) {
                    $data = substr($data, strpos($data, '__(\'')+4);
                    $tmp = explode('__(\'', $data);

                    foreach ($tmp as $string) {
                        $endpos = strpos($string, '\'');
                        while (substr($string, $endpos-1, 1) == "\\") {
                            $endpos = $endpos + strpos(substr($string, $endpos+1, strpos($string, '\'')), '\'') + 1;
                        }
                        $strings[] = substr($string, 0, $endpos);
                    }

                    if (sizeof($strings) > 0)
                        $complete = array_merge($complete, $strings);
                }
            }
        }

        $this->display('translate/core', array('complete' => $complete));
    }
    
    function plugins() {
        $files = array();
        $basedir = FROG_ROOT.'/frog/plugins';
        $dirs = $this->listdir($basedir, true);

        foreach ($dirs as $id => $path) {
            $tmp = array();
            $strings = array();
            $fsize = filesize($path);
            
            if ($fsize > 0) {
                $fh = fopen($path, 'r');
                $data = fread($fh, $fsize);
                fclose($fh);

                if (strpos($data, '__(\'')) {
                    $data = substr($data, strpos($data, '__(\'')+4);
                    $tmp = explode('__(\'', $data);

                    foreach ($tmp as $string) {
                        $endpos = strpos($string, '\'');
                        while (substr($string, $endpos-1, 1) == "\\") {
                            $endpos = $endpos + strpos(substr($string, $endpos+1, strpos($string, '\'')), '\'') + 1;
                        }
                        $strings[] = substr($string, 0, $endpos);
                    }

                    if (sizeof($strings) > 0)
                        $files[$path] = $strings;
                }
            }
        }

        $this->display('translate/plugins', array('files' => $files));
    }
    
    function listdir($start_dir='.', $plugins = false) {
        $files = array();
        if (is_dir($start_dir)) {
            $fh = opendir($start_dir);
            while (($file = readdir($fh)) !== false) {
                # loop through the files, skipping . and .., and recursing if necessary
                if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
                $filepath = $start_dir . '/' . $file;
                if ($plugins) {
                    if ( is_dir($filepath) && !strpos($filepath, 'i18n') )
                        $files = array_merge($files, $this->listdir($filepath, $plugins));
                    else {
                        if (!strpos($filepath, 'I18n') && strpos($filepath, '.php', strlen($filepath) - 5) || strpos($filepath, '.phtml', strlen($filepath) - 7))
                            array_push($files, $filepath);
                    }                    
                } else {
                    if ( is_dir($filepath) && !strpos($filepath, 'i18n') && !strpos($filepath, 'plugins') )
                        $files = array_merge($files, $this->listdir($filepath, $plugins));
                    else {
                        if (!strpos($filepath, 'I18n') && strpos($filepath, '.php', strlen($filepath) - 5) || strpos($filepath, '.phtml', strlen($filepath) - 7))
                            array_push($files, $filepath);
                    }
                }
            }
            closedir($fh);
        } else {
            # false if the function was called with an invalid non-directory argument
            $files = false;
        }

        return $files;
    }

} // TranslateController