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

if (class_exists('PDO', false))
{
	Plugin::setInfos(array(
		'id'		  => 'sqlite3',
		'title'		  => 'SQLite 3', 
		'description' => 'Provides function to run Frog CMS with SQLite 3 database.', 
		'version'	  => '1.0.0', 
		'website'	  => 'http://www.madebyfrog.com/',
        'update_url'  => 'http://www.madebyfrog.com/plugin-versions.xml'
    ));

	// adding function date_format to sqlite 3 'mysql date_format function'
	if (! function_exists('mysql_date_format_function'))
	{
		function mysql_function_date_format($date, $format)
		{
			return strftime($format, strtotime($date));
		}
	}
	
	if (isset($GLOBALS['__FROG_CONN__']))
		if ($GLOBALS['__FROG_CONN__']->getAttribute(PDO::ATTR_DRIVER_NAME) == 'sqlite')
			$GLOBALS['__FROG_CONN__']->sqliteCreateFunction('date_format', 'mysql_function_date_format', 2);
	else if (Record::getConnection()->getAttribute(Record::ATTR_DRIVER_NAME) == 'sqlite')
		Record::getConnection()->sqliteCreateFunction('date_format', 'mysql_function_date_format', 2);
}