## About

Frog CMS is a migration of Radiant CMS from ruby to PHP. You are free to 
use, modify or ... read the license.txt to all details.

## Requirements

PHP (5) and MySQL with InnoDB support. Apache is recommanded.

* PHP    : http://www.php.net/
* MySQL  : http://www.mysql.com/
* Apache : http://www.apache.org/

this is what I WAS using, if there's some probleme just email me...
now I use a php 5.2 with PDO and it work just better and faster :D
please participate with us to make all peoples upgrading to php 5.2 
beacause php 4 suck alot ;) as Ilia says 

## Installation

* manualy create your database in your mysql (phpmyadmin is a good tool for that)
* open your browser go to the frog_path/install/ and answer all questions!
* go to your_frog_dir/admin/
* login as admin/password

### optional (to remove the ? in the url)

* edit file _.htaccess and set your base dir
* rename _.htaccess to .htaccess
* open config.php and define USE_MOD_REWRITE to true

## Notes

password is in sha1 so if you change it manualy in database you know how !!


and really sorry for my poor english!

(c) 2007 by Philippe Archambault
