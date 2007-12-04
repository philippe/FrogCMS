<?php

// ---------------------------------------------------
//  Directories
// ---------------------------------------------------
define('FROG_VERSION', '0.8.7');

define('FROG_ROOT', dirname(__FILE__).'/..');
define('CORE_ROOT', FROG_ROOT.'/frog');

define('APP_PATH',  CORE_ROOT.'/app/backend');
define('FILES_DIR', FROG_ROOT.'/public'); // place where we will upload project files

// ---------------------------------------------------
//  config.php + extended config
// ---------------------------------------------------

define('SESSION_LIFETIME', 3600);
define('REMEMBER_LOGIN_LIFETIME', 1209600); // two weeks

// Defaults
define('DEFAULT_CONTROLLER', 'page');
define('DEFAULT_ACTION', 'index');

// Default cookie settings...
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', false);

// ---------------------------------------------------
//  Init...
// ---------------------------------------------------
include FROG_ROOT.'/config.php';

define('BASE_URL', URL_PUBLIC . ADMIN_DIR . (USE_MOD_REWRITE ? '/': '/?/'));
define('BASE_FILES_DIR', URL_PUBLIC . 'public'); // place where we will upload project files (html url)

include CORE_ROOT.'/Framework.php';

// database connection
if (USE_PDO)
{
    $__FROG_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);
    $__FROG_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
}
else
{
    require_once CORE_ROOT . '/libraries/DoLite.php';
    $__FROG_CONN__ = new DoLite(DB_DSN, DB_USER, DB_PASS);
}

Record::connection($__FROG_CONN__);
Record::getConnection()->exec("set names 'utf8'");

// load setting
Setting::load();

use_helper('I18n');
I18n::setLocale(Setting::get('language'));

// set route
//Dispatcher::addRoute('/logout', '/login/logout');

// Get controller and action and execute...
Dispatcher::dispatch();
