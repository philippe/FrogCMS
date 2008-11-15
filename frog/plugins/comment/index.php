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
 * @version 1.1.0
 * @since Frog version 0.9.3
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault & Bebliuc George, 2008
 */
Plugin::setInfos(array(
	'id'          => 'comment',
	'title'       => 'Comments',
	'description' => 'Provides interface to add page comments.',
	'version'     => '1.1.0',
	'license'     => 'AGPL',
	'author'      => 'Philippe Archambault',
	'website'     => 'http://www.madebyfrog.com/',
    'update_url'  => 'http://www.madebyfrog.com/plugin-versions.xml',
	'require_frog_version' => '0.9.3'
));

// AutoLoader class only exists in backend
if (class_exists('AutoLoader'))
{
	// adding the new model Comment to the AutoLoader
	AutoLoader::addFile('Comment', CORE_ROOT.'/plugins/comment/Comment.php');

	Plugin::addController('comment', 'Comments');

	Observer::observe('view_page_edit_plugins', 'comment_display_dropdown');

	function comment_display_dropdown(&$page)
	{
		echo '
		<p><label for="page_comment_status">'.__('Comments').'</label>
		   <select id="page_comment_status" name="page[comment_status]">
		     <option value="'.Comment::NONE.'"'.($page->comment_status == Comment::NONE ? ' selected="selected"': '').'>&#8212; '.__('none').' &#8212;</option>
		     <option value="'.Comment::OPEN.'"'.($page->comment_status == Comment::OPEN ? ' selected="selected"': '').'>'.__('Open').'</option>
		     <option value="'.Comment::CLOSED.'"'.($page->comment_status == Comment::CLOSED ? ' selected="selected"': '').'>'.__('Closed').'</option>
		   </select>
		</p>';
	}
}
else // list of fonctions, classes used by the frontend
{
	Observer::observe('page_found', 'comment_save');

	function comments(&$page)
	{
		global $__FROG_CONN__;

		$comments = array();
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'comment WHERE is_approved=1 AND page_id=?';

		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute(array($page->id));

		while ($comment = $stmt->fetchObject('Comment'))
			$comments[] = $comment;

		return $comments;
	}

	function comments_count(&$page)
	{
		global $__FROG_CONN__;

		$sql = 'SELECT COUNT(id) AS num FROM '.TABLE_PREFIX.'comment WHERE is_approved=1 AND page_id=?';

		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute(array($page->id));
		$obj = $stmt->fetchObject();

		return (int) $obj->num;
	}

	function comment_save(&$page)
	{
		global $__FROG_CONN__;
		$sql = "SELECT * FROM ".TABLE_PREFIX."setting WHERE name = 'use_captcha'";
		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute();
		$captcha = $stmt->fetchObject();
		session_start();
		$data = $_POST['comment'];
		if (is_null($data)) return;
		// check if we need to save a comment
		if ( ! isset($_POST['comment'])) return;
		
		if($captcha->value == "1") {
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
	
	function captcha() {
		global $__FROG_CONN__;
		$sql = "SELECT * FROM ".TABLE_PREFIX."setting WHERE name = 'use_captcha'";
		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute();
		$captcha = $stmt->fetchObject();
		if($captcha->value == "1") {
		$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']:($_SERVER['REMOTE_ADDR']);
			if($data['secure'] != $_SESSION['security_number'] AND !empty($data['secure'])) {
				echo 'Oh, come on! This is second grade math!<br />';
			}
			else {
				echo 'Please insert the result of the arithmetical operation from the following image<br />';
			}
			
			echo "<img src=\"".URL_PUBLIC."frog/plugins/comment/image.php\" alt=\"Enter the result of the arithmetical operation from this image\">";
			echo " <br /><br /><input class=\"input\" type=\"text\" name=\"comment[secure]\" />";
			echo "<input type=\"hidden\" value=\"".$ip."\" name=\"comment[author_ip]\" /> ";
		}
	
		$sql = "SELECT * FROM ".TABLE_PREFIX."setting WHERE name = 'auto_approve_comment'";
		$stmt = $__FROG_CONN__->prepare($sql);
		$stmt->execute();
		$approve = $stmt->fetchObject();
		
		$data = $_POST['comment'];
		
		if(isset($_POST['commit-comment'])) {
			if($data['secure'] == $_SESSION['security_number']) {
				if(isset($_POST['commit-comment']) AND  $approve->value = "2") {
					echo '<p>Your comment is waiting for moderation.</p>';
				}
			}
		else {
			echo '<p>Oh, come on! This is second grade math!</p>';
			}
		}
	}
	
	/**
     * @ignore
     */
    class Comment
	{
		const NONE = 0;
		const OPEN = 1;
		const CLOSED = 2;

		function name($class='')
		{
			if ($this->author_link != '')
			{
				return sprintf(
					'<a class="%s" href="%s" title="%s">%s</a>',
					$class,
					$this->author_link,
					$this->author_name,
					$this->author_name
				);
			}
			else return $this->author_name;
		}

		function email() { return $this->author_email; }
		function link() { return $this->author_link; }
		function body() { return $this->body; }

		function date($format='%a, %e %b %Y')
		{
			return strftime($format, strtotime($this->created_on));
		}
		
		function gravatar($size = "80") {
			$default = URL_PUBLIC.'public/images/gravatar.png';
			$grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($this->author_email)."&amp;default=".urlencode($default)."&amp;size=".$size;
			echo $grav_url;
		}

	} // end Comment class
}
