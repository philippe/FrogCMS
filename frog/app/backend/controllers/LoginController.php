<?php 

/**
 * class LoginController
 *
 * Log a use in and out and send a mail with something on 
 * if the user doesn't remember is password !!!
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
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
        {
            redirect(get_url());
        }

        // show it!
        $this->display('login/login', array(
            'username' => Flash::get('username')
        ));
    } // add
    
    function login()
    {
        // already log in ?
        if (AuthUser::isLoggedIn())
        {
            redirect(get_url());
        }

        $data = isset($_POST['login']) ? $_POST['login']: array('username' => '', 'password' => '');
        Flash::set('username', $data['username']);
        
        if (AuthUser::login($data['username'], $data['password'], isset($data['remember'])))
        {
            $this->_checkVersion();
            // redirect to defaut controller and action
            redirect(get_url());

        }
        else
        {
            // login error
            Flash::set('error', __('Failed to log you in. Please check your login data and try again'));
        } // if
        
        // not find or password is wrong
        redirect(get_url('login'));
        
    } // login
    
    function logout()
    {
        AuthUser::logout();
        redirect(get_url());
    } // logout
    
    function forgot()
    {
        if (get_request_method() == 'POST')
        {
            return $this->_sendPasswordTo($_POST['forgot']['email']);
        }
        $this->display('login/forgot', array('email' => Flash::get('email')));
    } // forgot
    
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
