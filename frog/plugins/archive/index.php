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

Plugin::setInfos(array(
    'id'          => 'archive',
    'title'       => 'Archive', 
    'description' => 'Provides Archive page types behave similar to a blog or news archive.', 
    'version'     => '1.0.0',
    'website'     => 'http://www.madebyfrog.com/',
    'update_url'  => 'http://www.madebyfrog.com/plugin-versions.xml'
));

Behavior::add('archive', 'archive/archive.php');
Behavior::add('archive_day_index', 'archive/archive.php');
Behavior::add('archive_month_index', 'archive/archive.php');
Behavior::add('archive_year_index', 'archive/archive.php');