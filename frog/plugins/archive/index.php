<?php

Plugin::setInfos(array(
    'id'          => 'archive',
    'title'       => 'Archive', 
    'description' => 'Provides Archive page types behave similar to a blog or news archive.', 
    'version'     => '1.0', 
    'website'     => 'http://www.madebyfrog.com/')
);

Behavior::add('archive', 'archive/archive.php');
Behavior::add('archive_day_index', 'archive/archive.php');
Behavior::add('archive_month_index', 'archive/archive.php');
Behavior::add('archive_year_index', 'archive/archive.php');