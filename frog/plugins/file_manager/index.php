<?php

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