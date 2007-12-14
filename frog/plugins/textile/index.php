<?php

Plugin::setInfos(array(
    'id'          => 'textile',
    'title'       => 'Textile filter', 
    'description' => 'Allows you to compose page parts or snippets using the Textile text filter.', 
    'version'     => '1.0', 
    'website'     => 'http://www.madebyfrog.com/')
);

Filter::add('textile', 'textile/filter_textile.php');
