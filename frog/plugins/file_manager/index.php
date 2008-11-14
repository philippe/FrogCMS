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
 * The FileManager allows users to upload and manipulate files.
 *
 * @package frog
 * @subpackage plugin.file_manager
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.0.0
 * @since Frog version 0.9.0
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault & Martijn van der Kleijn, 2008
 */

/**
 * Root location where files get uploaded to as an absolute path.
 */
define('FILES_DIR', FROG_ROOT.'/public');

/**
 * Root location where files get uploaded to as a URL.
 */
define('BASE_FILES_DIR', URL_PUBLIC . 'public'); 

// DO NOT EDIT AFTER THIS LINE -----------------------------------------------

Plugin::setInfos(array(
    'id'          => 'file_manager',
    'title'       => 'Files Manager', 
    'description' => 'Provides interface to manage file from the administration.', 
    'version'     => '1.0.0', 
    'website'     => 'http://www.madebyfrog.com/',
    'update_url'  => 'http://www.madebyfrog.com/plugin-versions.xml'
));

Plugin::addController('file_manager', 'Files', 'developer,editor');