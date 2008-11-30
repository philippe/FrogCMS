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
?>
<h3>How to use this plugin</h3>
<p>
    The comments plugin tab displays for example: "Comments (2/5)". This means that two comments are
    waiting for approval in the moderation list out of five total comments.
</p>
<p>
  On each page edit screen, you will have a drop-down box available called "Comments".
  From this, you can choose between three options:
</p>
<ul>
  <li>none: if you do not want comments displayed on the page.</li>
  <li>open: if you want comments displayed and want people to be able to post comments.</li>
  <li>close: if you want to display comments, but do not want people do be able to post new comments.</li>
</ul>
<p>
  You will need to add this code to your layout:
</p>
<pre>
    &lt;?php
      if ($this-&gt;comment_status != Comment::NONE)
          $this-&gt;includeSnippet('comment-each');
      if ($this-&gt;comment_status == Comment::OPEN)
          $this-&gt;includeSnippet('comment-form');
    ?&gt;
</pre>

<h3>Notes</h3>
<p>
  When you disable the comments plugin, the database table, snippets and page.comment_status stay available.
</p>
<p>
  If you do disable the comments plugin, do not forget to remove the code you added earlier on from your layout, otherwise you will get PHP errors.
</p>

<h3>License</h3>
<p>
  This Frog plugin has been made available under the GNU AGPL3 or later.
</p>
<p>
  Copyright (C) 2008 Philippe Archambault &lt;philippe.archambault@gmail.com&gt;<br/>
  Copyright (C) 2008 Bebliuc George &lt;bebliuc.george@gmail.com&gt;<br/>
  Copyright (C) 2008 Martijn van der Kleijn &lt;martijn.niji@gmail.com&gt;
</p>
<p>
  Please see the full license statement in this plugin's readme.txt file.
</p>
