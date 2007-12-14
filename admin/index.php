<?php

//  Constantes ---------------------------------------------------------------

define('FROG_VERSION', '0.9.0');

define('FROG_ROOT', dirname(__FILE__).'/..');
define('CORE_ROOT', FROG_ROOT.'/frog');

define('APP_PATH',  CORE_ROOT.'/app/backend');

define('SESSION_LIFETIME', 3600);
define('REMEMBER_LOGIN_LIFETIME', 1209600); // two weeks

define('DEFAULT_CONTROLLER', 'page');
define('DEFAULT_ACTION', 'index');

define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', false);


//  Init ---------------------------------------------------------------------

include FROG_ROOT.'/config.php';

define('BASE_URL', URL_PUBLIC . ADMIN_DIR . (USE_MOD_REWRITE ? '/': '/?/'));

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

// Initialize settings and plugins
Setting::init();
Plugin::init();

use_helper('I18n');
I18n::setLocale(Setting::get('language'));

// Get controller and action and execute...
Dispatcher::dispatch();
