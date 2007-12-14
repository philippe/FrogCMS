<?php

Plugin::setInfos(array(
    'id'          => 'markdown',
    'title'       => 'Markdown filter', 
    'description' => 'Allows you to compose page parts or snippets using the Markdown text filter.', 
    'version'     => '1.0', 
    'website'     => 'http://www.madebyfrog.com/')
);

Filter::add('markdown', 'markdown/filter_markdown.php');