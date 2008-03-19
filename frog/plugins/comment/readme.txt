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

The MIT License

Copyright (c) 2008 Philippe Archambault

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.