<?php

define('CORE_ROOT', dirname(__FILE__).'/../frog');

$config_file = '../config.php';

include 'Template.php';

if (file_exists($config_file))
  include $config_file;

$msg = '';

// lets install this nice little CMS
if ( ! defined('DEBUG') && isset($_POST['commit']) && (file_exists($config_file) && is_writable($config_file)))
{ 
    $config_tmpl = new Template('config.tmpl');
    
    // add type of database manualy
    $_POST['config']['db_type'] = 'mysql'; 
    
    $config_tmpl->assign($_POST['config']);
    $config_content = $config_tmpl->fetch();
    
    file_put_contents($config_file, $config_content);
    $msg .= "<p>Config file successfully written!</p>\n";
    
    include $config_file;
    
    if (USE_PDO)
    {
        $PDO = new PDO(DB_DSN, DB_USER, DB_PASS);
    }
    else
    {
        require_once CORE_ROOT . '/libraries/DoLite.php';
        $PDO = new DoLite(DB_DSN, DB_USER, DB_PASS);
    }
    
    if ($PDO)
    {
        $msg .= '<p>Database connection successful</p>';
        
        include 'schema_'.$_POST['config']['db_driver'].'.php';
        include 'sql_data.php';
        
        $msg .= '<p>Tables loaded successfully! you can login with: <br /><strong>login</strong>: admin<br /><strong>password</strong>: password<br />
        <strong>at</strong>: <a href="../admin/">login page</a></p>';
    }
    else $error = '<p style="color: red;">Unable to connect to the database! Tables are not loaded! Delete the content of <b>config.php</b> file and try again. </p>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Frog CMS - Install</title>
<link href="../admin/stylesheets/admin.css" media="screen" rel="Stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="../admin/javascripts/prototype.js"></script>
<script type="text/javascript" charset="utf-8" src="../admin/javascripts/effects.js"></script>
</head>
<body id="installation">
<div id="header">
  <div id="site-title">Frog CMS</div>
</div>
<div id="main">
  <div id="content-wrapper">
      <div id="content">
              <!-- Content -->
        <h1>Frog Installation</h1>

<p style="color: red">
<?php if ( ! file_exists($config_file)) { ?>
  <strong>Error</strong>: config.php doesn't exist<br />
<?php } else if ( ! is_writable($config_file)) { ?>
  <strong>Error</strong>: config.php must be writable<br />
<?php } ?>
<?php if ( ! is_writable('../public/')): ?>
  <strong>Error</strong>: public/ folder must be writable<br />
<?php endif; ?>
</p>

<?php if ( ! defined('DEBUG')): ?>
<form action="index.php" method="post">
  <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="3"><h3>Database information</h3></td>
    </tr>
    <tr>
      <td class="label"><label for="use_pdo-yes">Use <a href="http://php.net/pdo" target="_blank">PDO</a></label></td>
      <td class="field">
        <input class="radio" id="use_pdo-yes" name="config[use_pdo]" size="10" type="radio" value="1" checked="checked" /><label for="use_pdo-yes"> yes </label>
        <input class="radio" id="use_pdo-no" name="config[use_pdo]" size="10" type="radio" value="0" onclick="return use_pdo_no_click();" /><label for="use_pdo-no"> no </label>
        <script type="text/javascript" language="javascript" charset="utf-8">
         function use_pdo_no_click()
         {
           var select_bd_driver = $('config_db_driver');
           if (select_bd_driver[select_bd_driver.selectedIndex].value == 'sqlite')
           {
             alert('SQLite 3 require PDO! If you do not have PDO use MySQL driver!');
             return false;
           }
         }
        </script>
      </td>
      <td class="help">Required. If you have PDO with MySQL or SQLite 3 driver installed on your server, it is highly recommended that you select "Yes".</td>
    </tr>
    <tr>
      <td class="label"><label for="config_db_driver">Database driver</label></td>
      <td class="field">
        <select id="config_db_driver" name="config[db_driver]" onchange="db_driver_change(this[this.selectedIndex].value);">
          <option value="mysql">MySQL</option>
          <option value="sqlite">SQLite 3</option>
        </select>
        <script type="text/javascript" language="javascript">
          function db_driver_change(driver)
          {
            Element.toggle('row-db-host');
            Element.toggle('row-db-user');
            Element.toggle('row-db-pass');
            
            if (driver == 'sqlite')
            {
              $('use_pdo-yes').checked = 'checked';
              $('help-db-name').innerHTML = 'Required. Write the absolute path to the database file.';
            }
            else if (driver == 'mysql')
            {
              $('help-db-name').innerHTML = 'Required. You have to create a database manually and enter its name here.';
            }
          }
        </script>
      </td>
      <td class="help">Required. PDO and SQLite 3 plugin is require to use SQLite 3.</td>
    </tr>
    <tr id="row-db-host">
      <td class="label"><label for="config_db_host">Database server</label></td>
      <td class="field"><input class="textbox" id="config_db_host" maxlength="100" name="config[db_host]" size="100" type="text" value="localhost" /></td>
      <td class="help">Required.</td>
    </tr>
    <tr id="row-db-user">
      <td class="label"><label for="config_db_user">Database user</label></td>
      <td class="field"><input class="textbox" id="config_db_user" maxlength="255" name="config[db_user]" size="255" type="text" value="root" /></td>

      <td class="help">Required.</td>
    </tr>
    <tr id="row-db-pass">
      <td class="label"><label class="optional" for="config_db_pass">Database password</label></td>
      <td class="field"><input class="textbox" id="config_db_pass" maxlength="40" name="config[db_pass]" size="40" type="password" value="" /></td>
      <td class="help">Optional. If there is no database password, leave it blank.</td>
    </tr>
    <tr>
      <td class="label"><label for="config_db_name">Database name</label></td>
      <td class="field"><input class="textbox" id="config_db_name" maxlength="40" name="config[db_name]" size="40" type="text" value="frog" /></td>
      <td class="help" id="help-db-name">Required. You have to create a database manually and enter its name here.</td>
    </tr>
    <tr>
      <td class="label"><label class="optional" for="config_table_prefix">Table prefix</label></td>
      <td class="field"><input class="textbox" id="config_table_prefix" maxlength="40" name="config[table_prefix]" size="40" type="text" value="" /></td>
      <td class="help">Optional. To prevent conflicts if you have, or plan to have, multiple Frog installations with a single database.</td>
    </tr>
    <tr>
      <td colspan="3"><h3>Other information</h3></td>
    </tr>
    <tr>
      <td class="label"><label class="optional" for="config_url_suffix">URL suffix</label></td>
      <td class="field"><input class="textbox" id="config_url_suffix" maxlength="40" name="config[url_suffix]" size="40" type="text" value=".html" /></td>
      <td class="help">Optional. Add a suffix to simulate static html files.</td>
    </tr>
  </table>

  <p class="buttons">
    <button class="button" name="commit" type="submit"> Install now </button>
  </p>

</form>

<?php else: ?>
    <?php echo $msg; ?> 

    <?php if (isset($error)) echo $error; ?> 

    <p><strong>Frog CMS</strong> is installed, <b>you must delete the <em>install/</em> folder now!</b></p>
<?php endif; ?>

    </div>
  </div>
</div>

<div id="footer">
  <p>This site was made with <a href="http://www.php.net" target="_blank">PHP</a> and is powered by <a href="http://www.madebyfrog.com/">Frog CMS</a></p>
</div>
</body>
</html>
