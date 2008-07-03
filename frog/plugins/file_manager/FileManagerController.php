<?php

class FileManagerController extends PluginController
{
    var $path;
    var $fullpath;
    
    public function __construct()
    {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../../plugins/file_manager/views/sidebar'));
    }
    
    public function index()
    {
        $this->browse();
    }
    
    public function browse()
    {
        $params = func_get_args();
        
        $this->path = join('/', $params);
        // make sure there's a / at the end
        if (substr($this->path, -1, 1) != '/') $this->path .= '/';
        
        //security
        
        // we dont allow back link
        //$this->path = preg_replace('/\./', '', $this->path);
        $this->path = str_replace('..', '', $this->path);
        
        // clean up nicely
        //$this->path = preg_replace('/\/\//', '', $this->path);
        
        $this->path = str_replace('//', '', $this->path);
        
        // we dont allow leading slashes
        $this->path = preg_replace('/^\//', '', $this->path); 
        
        $this->fullpath = FILES_DIR.'/'.$this->path;
        
        // clean up nicely
        $this->fullpath = preg_replace('/\/\//', '/', $this->fullpath);
        
        $this->display('file_manager/views/index', array(
            'dir'   => $this->path,
            'files' => $this->_getListFiles()
        ));
    } // browse
    
    public function view()
    {
        $params = func_get_args();
        
        $content = '';
        $filename = urldecode(join('/', $params));
        $file = FILES_DIR.'/'.$filename;
        if ( ! $this->_isImage($file) && file_exists($file))
        {
            $content = file_get_contents($file);
        }
        
        $this->display('file_manager/views/view', array(
            'is_image' => $this->_isImage($file),
            'filename' => $filename,
            'content'  => $content
        ));
    }
    
    public function save()
    {
        $data = $_POST['file'];
        
        // security (remove all ..)
        $data['name'] = str_replace('..', '', $data['name']);
        $file = FILES_DIR.'/'.$data['name'];
        if (file_exists($file))
        {
            if (file_put_contents($file, $data['content']))
            {
                Flash::set('success', __('File has been saved with success!'));
            }
            else
            {
                Flash::set('error', __('File is not writable! File has not been saved!'));
            }
        }
        else
        {
            if (file_put_contents($file, $data['content']))
            {
                Flash::set('success', __('File :name has been created with success!', array(':name'=>$data['name'])));
            }
            else
            {
                Flash::set('error', __('Directory is not writable! File has not been saved!'));
            }
        }
        
        // save and quit or save and continue editing ?
        if (isset($_POST['commit']))
        {
            redirect(get_url('plugin/file_manager/browse/'.substr($data['name'], 0, strrpos($data['name'], '/'))));
        }
        else
        {
            redirect(get_url('plugin/file_manager/view/'.$data['name']));
        }
        
    }
    
    public function create_file()
    {
        $data = $_POST['file'];
        
        $path = str_replace('..', '', $data['path']);
        $filename = str_replace('..', '', $data['name']);
        $file = FILES_DIR."/{$path}/{$filename}";
        
        if (file_put_contents($file, '') !== false)
        {
            chmod($file, 0644);
        }
        else
        {
            Flash::set('error', __('File :name has not been created!', array(':name'=>$filename)));
        }
        redirect(get_url('plugin/file_manager/browse/'.$path));
    }
    
    public function create_directory()
    {
        $data = $_POST['directory'];
        
        $path = str_replace('..', '', $data['path']);
        $dirname = str_replace('..', '', $data['name']);
        $dir = FILES_DIR."/{$path}/{$dirname}";
        
        if (mkdir($dir))
        {
            chmod($dir, 0755);
        }
        else
        {
            Flash::set('error', __('Directory :name has not been created!', array(':name'=>$dirname)));
        }
        redirect(get_url('plugin/file_manager/browse/'.$path));
    }
    
    public function delete()
    {
        $paths = func_get_args();
        
        $file = urldecode(join('/', $paths));
        
        $file = FILES_DIR.'/'.str_replace('..', '', $file);
        $filename = array_pop($paths);
        $paths = join('/', $paths);
        
        if (is_file($file))
        {
            if ( ! unlink($file))
                Flash::set('error', __('Permission denied!'));
        }
        else
        {
            if ( ! rrmdir($file))
                Flash::set('error', __('Permission denied!'));
        }
        
        redirect(get_url('plugin/file_manager/browse/'.$paths));
    }
    
    public function upload()
    {
        $data = $_POST['upload'];
        $path = str_replace('..', '', $data['path']);
        $overwrite = isset($data['overwrite']) ? true: false;
        
        if (isset($_FILES))
        {
            $file = upload_file($_FILES['upload_file']['name'], FILES_DIR.'/'.$path.'/', $_FILES['upload_file']['tmp_name'], $overwrite);
            
            if ($file === false)
               Flash::set('error', __('File has not been uploaded!'));
        }
        redirect(get_url('plugin/file_manager/browse/'.$path));
    }
    
    public function chmod()
    {
        $data = $_POST['file'];
        $data['name'] = str_replace('..', '', $data['name']);
        $file = FILES_DIR.'/'.$data['name'];
        
        if (file_exists($file))
        {
            if ( ! chmod($file, octdec($data['mode'])))
                Flash::set('error', __('Permission denied!'));
        }
        else
        {
            Flash::set('error', __('File or directory not found!'));
        }
        
        $path = substr($data['name'], 0, strrpos($data['name'], '/'));
        redirect(get_url('plugin/file_manager/browse/'.$path));
    }
    
    public function rename()
    {
        $data = $_POST['file'];
        
        $data['current_name'] = str_replace('..', '', $data['current_name']);
        $data['new_name'] = str_replace('..', '', $data['new_name']);
        
        $path = substr($data['current_name'], 0, strrpos($data['current_name'], '/'));
        $file = FILES_DIR.'/'.$data['current_name'];
        
        if (file_exists($file))
        {
            if ( ! rename($file, FILES_DIR.'/'.$path.'/'.$data['new_name']))
                Flash::set('error', __('Permission denied!'));
        }
        else
        {
            Flash::set('error', __('File or directory not found! '.$file));
        }
        
        redirect(get_url('plugin/file_manager/browse/'.$path));
    }
    
    //
    // Privates
    //
    
    public function _getPath()
    {
        $path = join('/', get_params());
        return str_replace('..', '', $path);
    }
    
    public function _getListFiles()
    {
        $files = array();
        
        if (is_dir($this->fullpath) && $handle = opendir($this->fullpath))
        {
            $i = 0;
            // check each files ...
            while (false !== ($file = readdir($handle)))
            {
                // do not display . and the root ..
                if ($file == '.' || $file == '..')
                    continue;
                
                $object = new stdClass;
                $file_stat = stat($this->fullpath.$file);
                
                // make the link depending on if it's a file or a dir
                if (is_dir($this->fullpath.$file))
                {
                    $object->is_dir = true;
                    $object->is_file = false;
                    $object->link = '<a href="'.get_url('plugin/file_manager/browse/'.$this->path.$file).'">'.$file.'</a>';
                }
                else
                {
                    $object->is_dir = false;
                    $object->is_file = true;
                    $object->link = '<a href="'.get_url('plugin/file_manager/view/'.$this->path.$file).'">'.$file.'</a>';
                }
                
                $object->name = $file;
                // humain size
                $object->size = convert_size($file_stat['size']);
                // permission
                list($object->perms, $object->chmod) = $this->_getPermissions($this->fullpath.$file);
                // date modification
                $object->mtime = date('D, j M, Y', $file_stat['mtime']);
                
                if ($object->is_dir)
                    $files['d_'.$i] = $object;
                else
                    $files['f_'.$i] = $object;
                
                $i++;
            } // while
            closedir($handle);
        }
        
        ksort($files);
        return $files;
    } // _getListFiles

    public function _getPermissions($file)
    {
        $perms = fileperms($file);

        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                 (($perms & 0x0800) ? 's' : 'x' ) :
                 (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                 (($perms & 0x0400) ? 's' : 'x' ) :
                 (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                 (($perms & 0x0200) ? 't' : 'x' ) :
                 (($perms & 0x0200) ? 'T' : '-'));

        //$info .= ' ('.substr(sprintf('%o', $perms), -4, 4).')';
        return array($info, substr(sprintf('%o', $perms), -4, 4)); // (perm, chmod)
    } // _getPermissions

    public function _isImage($file)
    {
        if ( ! @is_file($file))
            return false;
        else if ( ! preg_match('/^(.*).(jpe?g|gif|png)$/i', $file))
            return false;
        
        return true;
    }

} // end FileController class

// Usage: upload_file($_FILE['file']['name'],'temp/',$_FILE['file']['tmp_name'])
function upload_file($origin, $dest, $tmp_name, $overwrite=false)
{
    $origin = strtolower(basename($origin));
    $full_dest = $dest.$origin;
    $file_name = $origin;
    for ($i=1; file_exists($full_dest); $i++)
    {
        if ($overwrite)
        {
            unlink($full_dest);
            continue;
        }
        
        $file_ext = (strpos($origin, '.') === false ? '': '.'.substr(strrchr($origin, '.'), 1));
        $file_name = substr($origin, 0, strlen($origin) - strlen($file_ext)).'_'.$i.$file_ext;
        $full_dest = $dest.$file_name;
    }

    if (move_uploaded_file($tmp_name, $full_dest))
    {
        // change mode of the dire to 0644 by default
        chmod($full_dest, 0644);
        return $file_name;
    }
    
    return false;
} // upload_file

// recursiv rmdir
function rrmdir($dirname)
{
    if (is_dir($dirname))
    {
        // Append slash if necessary
        if (substr($dirname,-1)!='/')
            $dirname.='/';
        
        $handle = opendir($dirname);
        while (false !== ($file = readdir($handle)))
        {
            if ($file != '.' && $file != '..')
            {
                $path = $dirname.$file;
                if (is_dir($path))
                {
                    rrmdir($path);
                }
                else
                {
                    unlink($path);
                }
            }
        }
        closedir($handle);
        rmdir($dirname); // Remove dir
        return true; // Return array of deleted items
    }
    else
    {
        return false; // Return false if attempting to operate on a file
    }
} // rrmdir
