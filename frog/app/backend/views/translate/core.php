<?php
// Prevent any possible caching
header('Content-type: text/plain');
header("Cache-Control: no-cache, must-revalidate");     // HTTP/1.1
header("Expires: Tue, 05 Dec 2000 00:00:01 GMT");       // Date in the past

// Do work
writeTemplate($complete);
// End work

/**
 * Outputs the core template.
 *
 * @param array  $strings
 */
function writeTemplate($strings) {
    echo '<?php

    /**
     * YourLanguage language file
     *
     * @package frog
     * @subpackage translations
     *
     * @author Your Name <email@domain.something>
     * @version Frog x.y.z
     */

    return array(
    ';

    $strings = removeDoubles($strings);
    sort($strings);

    foreach ($strings as $string) {
        echo "\t'".$string."' => '',\n";
    }    

    echo "    );\n\n\n\n\n\n";
}

/**
 * Removes any double entries in the array.
 *
 * @param array $array
 * @return array 
 */
function removeDoubles($array) {
    $result = array();
        
    foreach ($array as $string) {
        if (!in_array($string, $result))
        $result[] = $string;
    }

    return $result;
}