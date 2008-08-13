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
    'id'          => 'markdown',
    'title'       => 'Markdown filter', 
    'description' => 'Allows you to compose page parts or snippets using the Markdown text filter.', 
    'version'     => '1.0', 
    'website'     => 'http://www.madebyfrog.com/')
);

Filter::add('markdown', 'markdown/filter_markdown.php');