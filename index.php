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

//  Constants  ---------------------------------------------------------------

define('FROG_ROOT', dirname(__FILE__));
define('CORE_ROOT', FROG_ROOT.'/frog');

define('APP_PATH', CORE_ROOT.'/app');

//  Init  --------------------------------------------------------------------

require FROG_ROOT.'/config.php';

define('BASE_URL', URL_PUBLIC . (USE_MOD_REWRITE ? '': '?'));

// if you have installed frog and see this line, you can comment it or delete it :)
if ( ! defined('DEBUG')) { header('Location: install/'); exit(); }

require CORE_ROOT.'/Framework.php';

if (USE_PDO)
{
    try 
	{
        $__FROG_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
	} 
	catch (PDOException $error) 
	{
        die('DB Connection failed: '.$error->getMessage());
	}
    
    if ($__FROG_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__FROG_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
}
else
{
    require_once CORE_ROOT . '/libraries/DoLite.php';
    $__FROG_CONN__ = new DoLite(DB_DSN, DB_USER, DB_PASS);
}

//$__FROG_CONN__->exec("set names 'utf8'");
Record::connection($__FROG_CONN__);
Record::getConnection()->exec("set names 'utf8'");

Setting::init();

use_helper('I18n');
I18n::setLocale(Setting::get('language'));

//Plugin::init();


// run everything!
require APP_PATH.'/main.php';
