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

if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('snippet/add'); ?>"><img src="images/snippet.png" align="middle" alt="snippet icon" /> <?php echo __('New Snippet'); ?></a></p>

<div class="box">
    <h2><?php echo __('What is a Snippet?'); ?></h2>
    <p><?php echo __('Snippets are generally small pieces of content which are included in other pages or layouts.'); ?></p>
</div>
<div class="box">
    <h2><?php echo __('Tag to use this snippet'); ?></h2>
    <p><?php echo __('Just replace <b>snippet</b> by the snippet name you want to include.'); ?></p>
    <p><code>&lt;?php $this->includeSnippet('snippet'); ?&gt;</code></p>
</div>

<?php endif; ?>