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
 * @package frog
 * @subpackage controllers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault, 2008
 */

/**
 * Class LoginController
 *
 * Log a use in and out and send a mail with something on
 * if the user doesn't remember is password !!!
 *
 * @version 0.1
 * @since  0.1
 */

class LoginController extends Controller
{
    function __construct()
    {
        AuthUser::load();
    }
    
    function index()
    {
        // already log in ?
        if (AuthUser::isLoggedIn())
            redirect(get_url());
        
        // show it!
        $this->display('login/login', array(
            'username' => Flash::get('username')
        ));
    }
    
    function login()
    {
        // already log in ?
        if (AuthUser::isLoggedIn())
            redirect(get_url());
        
        $data = isset($_POST['login']) ? $_POST['login']: array('username' => '', 'password' => '');
        Flash::set('username', $data['username']);
        
        if (AuthUser::login($data['username'], $data['password'], isset($data['remember'])))
        {
            $this->_checkVersion();
            // redirect to defaut controller and action
            redirect(get_url());
        }
        else Flash::set('error', __('Login failed. Please check your login data and try again.'));
        
        // not find or password is wrong
        redirect(get_url('login'));
        
    }
    
    function logout()
    {
        AuthUser::logout();
        redirect(get_url());
    }
    
    function forgot()
    {
        if (get_request_method() == 'POST')
            return $this->_sendPasswordTo($_POST['forgot']['email']);
        
        $this->display('login/forgot', array('email' => Flash::get('email')));
    }
    
    /**
     * This method is used to send a newly generated password to a user.
     * 
     * @param string $email The user's email adress.
     */
    function _sendPasswordTo($email)
    {
        $user = User::findBy('email', $email);
        if ($user)
        {
            use_helper('Email');
            
            $new_pass = '12'.dechex(rand(100000000, 4294967295)).'K';
            $user->password = sha1($new_pass);
            $user->save();
            
            $email = new Email();
            $email->from('no-reply@madebyfrog.com', 'Frog CMS');
            $email->to($user->email);
            $email->subject('Your new password from Frog CMS');
            $email->message('username: '.$user->username."\npassword: ".$new_pass);
            $email->send();
            
            Flash::set('success', 'An email has been send with your new password!');
            redirect(get_url('login'));
        }
        else
        {
            Flash::set('email', $email);
            Flash::set('error', 'No user found!');
            redirect(get_url('login/forgot'));
        }
    }

    /**
     * Checks what the latest Frog version is that is available at madebyfrog.com
     *
     * @todo Make this check optional through the configuration file
     */
    function _checkVersion()
    {
        $v = file_get_contents('http://www.madebyfrog.com/version/');
        if ($v > FROG_VERSION)
        {
            Flash::set('error', __('<b>Information!</b> New Frog version available (v. <b>:version</b>)! Visit <a href="http://www.madebyfrog.com/">http://www.madebyfrog.com/</a> to upgrade your version!',
                       array(':version' => $v )));
        }
    }
    
} // end LoginController class
