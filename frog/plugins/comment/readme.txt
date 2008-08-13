== HOW TO USE IT:

On each page edition you will have a drop-down available to choose between 3 
options (none, open and close).

 - none:  if you do not want a comment displayed on the page
 - open:  if you want comment and want people to post comment
 - close: if you want to display comment, but do not want people do post other

You will need to add this little code in your layout:

    <?php if ($this->comment_status != Comment::NONE) 
          $this->includeSnippet('comment-each'); ?>
    <?php if ($this->comment_status == Comment::OPEN) 
          $this->includeSnippet('comment-form'); ?>


== NOTES:

When you disable the comment plugin, database table, snippet and 
page.comment_status stay available.

Do not forget to remove you code in your layout otherwise you will get PHP 
errors.


== LICENSE:

Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

