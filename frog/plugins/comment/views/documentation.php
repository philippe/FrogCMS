<h3>How to use this plugin</h3>
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
  Copyright (C) 2008 Bebliuc George &lt;bebliuc.george@gmail.com&gt;
</p>
<p>
  Please see the full license statement in this plugin's readme.txt file.
</p>
