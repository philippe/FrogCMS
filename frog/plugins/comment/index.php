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
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.2.0
 * @since Frog version 0.9.3
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault, Bebliuc George & Martijn van der Kleijn, 2008
 */
Plugin::setInfos(array(
	'id'          => 'comment',
	'title'       => 'Comments',
	'description' => 'Provides interface to add page comments.',
	'version'     => '1.2.0',
	'license'     => 'AGPL',
	'author'      => 'Philippe Archambault',
	'website'     => 'http://www.madebyfrog.com/',
    'update_url'  => 'http://www.madebyfrog.com/plugin-versions.xml',
	'require_frog_version' => '0.9.3'
));


// Load the Comment class into the system.
AutoLoader::addFile('Comment', CORE_ROOT.'/plugins/comment/Comment.php');

// Add the plugin's tab and controller
Plugin::addController('comment', 'Comments');

// Observe the necessary events.
Observer::observe('view_page_edit_plugins', 'comment_display_dropdown');
Observer::observe('page_found', 'comment_save');

/**
 * Allows for a dropdown box with comment status on the edit page view in the backend.
 *
 * @param Page $page The object instance for the page that is being edited.
 */
function comment_display_dropdown(&$page)
{
    echo '<p><label for="page_comment_status">'.__('Comments').'</label><select id="page_comment_status" name="page[comment_status]">';
    echo '<option value="'.Comment::NONE.'"'.($page->comment_status == Comment::NONE ? ' selected="selected"': '').'>&#8212; '.__('none').' &#8212;</option>';
    echo '<option value="'.Comment::OPEN.'"'.($page->comment_status == Comment::OPEN ? ' selected="selected"': '').'>'.__('Open').'</option>';
    echo '<option value="'.Comment::CLOSED.'"'.($page->comment_status == Comment::CLOSED ? ' selected="selected"': '').'>'.__('Closed').'</option>';
    echo '</select></p>';
}

/**
 * Retrieve an array with all approved comments for a particular page.
 * 
 * @param Page $page The object instance for a particular page.
 * @return Array Returns an array of Comment objects, if any.
 */
function comments(&$page)
{
    $comments = array();
    $comments = Comment::findApprovedByPageId($page->id);

    return $comments;
}

/**
 * Returns the number of approved comments for a particular page.
 *
 * @global <type> $__FROG_CONN__
 * @param <type> $page The object instance of a particular page.
 * @return <type> 
 */
function comments_count(&$page)
{
    return (int) count(comments($page));
}

/**
 * Executed through the Observer system each time a page is found.
 * 
 * @global <type> $__FROG_CONN__
 * @param Page $page The object instance for the page that was found.
 * @return <type> Nothing.
 */
function comment_save(&$page)
{
    // Check if we need to save a comment
    if (!isset($_POST['comment'])) return;

    $data = $_POST['comment'];
    if (is_null($data)) return;

    global $__FROG_CONN__;
    $sql = 'SELECT * FROM '.TABLE_PREFIX.'setting WHERE name = "use_captcha"';
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();
    $captcha = $stmt->fetchObject();

    if($captcha->value == '1') {
        if(isset($data['secure']))
        {
            if ($data['secure'] == "" OR empty($data['secure']) OR $data['secure'] != $_SESSION['security_number']) return;
        }
        else {
            return;
        }
    }

    if ($page->comment_status != Comment::OPEN) return;

    if ( ! isset($data['author_name']) or trim($data['author_name']) == '') return;
    if ( ! isset($data['author_email']) or trim($data['author_email']) == '') return;
    if ( ! preg_match('/[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+/i', $data['author_email'])) return;
    if ( ! isset($data['body']) or trim($data['body']) == '') return;
		
		
    use_helper('Kses');

    $allowed_tags = array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'abbr' => array(
            'title' => array()
        ),
        'acronym' => array(
            'title' => array()
        ),
        'b' => array(),
        'blockquote' => array(
            'cite' => array()
        ),
        'br' => array(),
        'code' => array(),
        'em' => array(),
        'i' => array(),
        'p' => array(),
        'strike' => array(),
        'strong' => array()
    );

    $sql = 'SELECT value FROM '.TABLE_PREFIX.'setting WHERE name=\'auto_approve_comment\'';
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();
    $auto_approve_comment = (int) $stmt->fetchColumn();
		
    $sql = 'INSERT INTO '.TABLE_PREFIX.'comment (page_id, author_name, author_email, author_link, ip, body, is_approved, created_on) VALUES ('.
           '\''.$page->id.'\', '.
           $__FROG_CONN__->quote(strip_tags($data['author_name'])).', '.
           $__FROG_CONN__->quote(strip_tags($data['author_email'])).', '.
           $__FROG_CONN__->quote(strip_tags($data['author_link'])).', '.
           $__FROG_CONN__->quote($data['author_ip']).', '.
           $__FROG_CONN__->quote(kses($data['body'], $allowed_tags)).', '.
           $__FROG_CONN__->quote($auto_approve_comment).', '.
           $__FROG_CONN__->quote(date('Y-m-d H:i:s')).')';

    $__FROG_CONN__->exec($sql);
}
	
/**
 * Displays a captcha when required by the settings.
 * 
 * @return none Nothing.
 */
function captcha()
{
    // Initialize proper defaults
    $data = null;
    $captcha = 1;
    $approve = 0;

    // Check if comment is available
    if (isset($_POST['comment'])) {
        $data = $_POST['comment'];
        if (is_null($data)) return;
    }

    // Get settings
    global $__FROG_CONN__;
    $sql = 'SELECT * FROM '.TABLE_PREFIX.'setting WHERE name = "use_captcha"';
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();
    $captcha = $stmt->fetchObject();

    $sql = 'SELECT * FROM '.TABLE_PREFIX.'setting WHERE name = "auto_approve_comment"';
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();
    $approve = $stmt->fetchObject();

    // Display captcha if required
    if ($captcha->value == '1') {
        if($data && ($data['secure'] != $_SESSION['security_number'] && !empty($data['secure'])) ) {
            echo '<p class="comment-captcha-error">'.__('Incorrect result value. Please try again:').'</p>';
        }
        else {
            echo '<p>'.__('Please insert the result of the arithmetical operation from the following image:').'</p>';
        }

        echo '<img id="comment-captcha" src="'.URL_PUBLIC.'frog/plugins/comment/image.php" alt="'.__('Please insert the result of the arithmetical operation from this image.').'" />';
        echo ' = <input id="comment-captcha-answer" class="input" type="text" name="comment[secure]" />';
    }

    // Add a field with user's IP address.
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']:($_SERVER['REMOTE_ADDR']);
    echo '<input type="hidden" value="'.$ip.'" name="comment[author_ip]" />';
	
	// Display results
    if (isset($_POST['commit-comment'])) {
        if ($captcha->value != '1' || $data['secure'] == $_SESSION['security_number']) {
            if($approve->value == '1') {
                echo '<p class="comment-captcha-success">'.__('Thank you for your comment. It has been added.').'</p>';
            }
            else {
                echo '<p class="comment-captcha-success">'.__('Thank you for your comment. It is waiting for approval.').'</p>';
            }
        }
    }
}
