<?php

/**
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
 */

// place where we will upload project files (absolute path)
define('FILES_DIR', FROG_ROOT.'/public');

// place where we will upload project files (html url)
define('BASE_FILES_DIR', URL_PUBLIC . 'public'); 

// DO NOT EDIT AFTER THIS LINE -----------------------------------------------

Plugin::setInfos(array(
    'id'          => 'file_manager',
    'title'       => 'Files Manager', 
    'description' => 'Provides interface to manage file from the administration.', 
    'version'     => '1.0', 
    'website'     => 'http://www.madebyfrog.com/')
);

Plugin::addController('file_manager', 'Files');