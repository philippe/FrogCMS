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
<p class="button"><a href="<?php echo get_url('plugin/comment/'); ?>"><img src="../frog/plugins/comment/images/comment.png" align="middle" alt="page icon" /> <?php echo __('Comments'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/comment/moderation/'); ?>"><img src="../frog/plugins/comment/images/moderation.png" align="middle" alt="page icon" /> <?php echo __('Moderation'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/comment/settings'); ?>"><img src="../frog/plugins/comment/images/settings.png" align="middle" alt="page icon" /> <?php echo __('Settings'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/comment/documentation/'); ?>"><img src="../frog/plugins/comment/images/documentation.png" align="middle" alt="page icon" /> <?php echo __('Documentation'); ?></a></p>