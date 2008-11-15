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
 * @subpackage views
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault, 2008
 */
?>
<html>
<head>
  <title>404 Not Found</title>
</head>
<body>
  <h1>Not Found</h1>
  <p>The requested URL <?php echo Dispatcher::getStatus('requested_url'); ?> was not found on this server.</p>
  <hr/>
  <address>KISS 'Keep It Simple, Stupid'</address>
</body>
</html>

<!--
   - Unfortunately, Microsoft has added a clever new 'feature' to Internet Explorer. 
   - If the text of an error's message is 'too small', specifically less than 512 bytes, 
   - Internet Explorer returns its own error message. You can turn that off, but it's 
   - pretty tricky to find switch called 'smart error messages'. That means, of course,
   - that short error messages are censored by default.
   - 
   - The workaround is pretty simple: pad the error message with a big comment like this 
   - to push it over the five hundred and twelve bytes minimum. Of course, that's exactly 
   - what you're reading right now.
   -->