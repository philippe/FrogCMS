<?php
  /**
   * Create the structures from dump-file
   * @param string file with sql dump
   */
  function sql_import_file($file)
  {
    $content = file_get_contents($file);
    sql_import_content($content);
  }
  
  /**
   * Create the structures from content of a dump-file
   * @param string  content of dump-file
   */
  function sql_import_content($content)
  {
    $querys = sql_split_file($content);
    foreach($querys as $query) {
      //echo "<p style=\"padding: 50px;\">$query</p><hr />\n";
      mysql_query($query);
    }
  }
   
  /**
   * Removes comment lines and splits up large sql files into individual queries
   * Based on phpMyAdmin 2.3.0 Read Copyright at phpmyadmin.net for reuse.
   * @param   string   the sql commands
   * @return  array    querys
   */
  function sql_split_file($sql)
  {
    $ret          = array();
    $sql          = trim($sql);
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = false;
    $time0        = time();

    for ($i=0; $i<$sql_len; ++$i) {
      $char = $sql[$i];

      if ($in_string) {
        for (;;) {
          $i = strpos($sql, $string_start, $i);
          if (!$i) {
            $ret[] = $sql;
            return $ret;
          } else if ($string_start == '`' || $sql[$i-1] != '\\') {
            $string_start = '';
            $in_string = false;
            break;
          } else {
            $j = 2;
            $escaped_backslash = false;
            while ($i-$j > 0 && $sql[$i-$j] == '\\') {
              $escaped_backslash = !$escaped_backslash;
              $j++;
            } // while
            if ($escaped_backslash) {
              $string_start = '';
              $in_string = false;
              break;
            } else {
              $i++;
            }
          }
        } // for
      } else if ($char == ';') {
          $ret[] = substr($sql, 0, $i);
          $sql = ltrim(substr($sql, min($i + 1, $sql_len)));
          $sql_len = strlen($sql);
          if ($sql_len) {
            $i = -1;
          } else {
            return $ret;
          }
      } else if (($char == '"') || ($char == '\'') || ($char == '`')) {
        $in_string = true;
        $string_start = $char;
      } else if ($char == '#' || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
        $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
        $end_of_comment = (strpos(' ' . $sql, "\012", $i+2))
                          ? strpos(' ' . $sql, "\012", $i+2)
                          : strpos(' ' . $sql, "\015", $i+2);
        if (!$end_of_comment) {
          if ($start_of_comment > 0) {
            $ret[] = trim(substr($sql, 0, $start_of_comment));
          }
          return $ret;
        } else {
          $sql = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
          $sql_len = strlen($sql);
          $i--;
        }
      } else if ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*') {
        $sql[$i] = ' ';
      } 

      $time1 = time();
      if ($time1 >= $time0 + 30) {
        $time0 = $time1;
        header('X-pmaPing: Pong');
      }
    } // for

    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
      $ret[] = $sql;
    }
    return $ret;
  } // sql_split_file
?>