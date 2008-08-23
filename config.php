<?php 

// database informations
// for sqlite, use sqlite:/tmp/frog.db (SQlite 3)
// the path can only be absolute path or :memory:
// for more info look at: www.php.net/pdo

define('DB_DSN', 'mysql:dbname=svn_frog;host=localhost');
define('DB_USER', 'root');
define('DB_PASS', '');

define('TABLE_PREFIX', 'svn_');

define('DEBUG', false);

// The full URL of your Frog CMS install
define('URL_PUBLIC', 'http://localhost/svn/madebyfrogs/');

// The directory name of your Frog CMS administration (you will need to change it manualy)
define('ADMIN_DIR', 'admin');

// Change this setting to enable mod_rewrite. Set to "true" to remove the "?" in the URL.
// To enable mod_rewrite, you must also change the name of "_.htaccess" in your
// Frog CMS root directory to ".htaccess"
define('USE_MOD_REWRITE', false);

// add a suffix to pages (simluating static pages '.html')
define('URL_SUFFIX', '.html');

// if your server doesn't have PDO (with MySQL driver) set the below to false
define('USE_PDO', true);

// Set the timezone of your choise
// go here for more information of the available timezone:
// http://php.net/timezones
define('DEFAULT_TIMEZONE', 'Europe/Helsinki');
