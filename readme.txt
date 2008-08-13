# FROG CMS - INFORMATION AND INSTALLATION

## About

Frog CMS began as migration of Radiant CMS from Ruby-on-Rails to PHP.
This product has been made available under the terms of the GNU AGPL version 3.
Please read the license.txt for the exact details;

The official Frog website can be found at www.madebyfrog.com - visit for further
information and resources.

## Requirements

PHP 5 and MySQL with InnoDB support. Apache is recommended.

* PHP    : http://www.php.net/
* MySQL  : http://www.mysql.com/
* Apache : http://www.apache.org/

Note that Frog CMS does *not* run on PHP 4.
PDO is recommended, but not required.
Frog CMS can also run on SQLite 3 as the database.

## Installation

1. Manually create your database in your mySQL (phpMyAdmin is a good tool
   for this). You will need to know the database name, user, and password.
2. Upload the Frog CMS package to your webserver; it is happy to work in
   a subdirectory. Open your browser go to the frog_path/install/ (e.g.
   http://www.mysite.com/install if Frog is in the root; or
   http://www.mysite.com/frog/install if Frog is in a subdirectory) and
   answer all questions!
3. When you have finished with the install, you will get a success message
   which includes a link for you to go to your_frog_dir/admin/. Don't forget
   to delete the /install directory.
4. Login as admin/password. You should change your admin passsword to
   something private and secure!

## Optional (to remove the ? in the url)

1. Edit file _.htaccess and set your base dir.
2. Rename _.htaccess to .htaccess.
3. Open config.php (in Frog's root dir) and define USE_MOD_REWRITE to true.

## Notes

Password is in sha1 so if you change it manually in the database, you know how!!

(c) 2007 by Philippe Archambault
