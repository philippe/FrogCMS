<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title><?php echo __('Forgot password') ?></title>
  <base href="<?php echo trim(BASE_URL, '?/').'/'; ?>" />
  <link href="stylesheets/login.css" rel="Stylesheet" type="text/css" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
  <div id="dialog">
    <h1><?php echo __('Forgot password') ?></h1>
<?php if (Flash::get('error') != null) { ?>
    <div id="error" onclick="this.style.display = 'none'"><?php echo Flash::get('error') ?></div>
<?php } ?>
<?php if (Flash::get('success') != null) { ?>
    <div id="success" onclick="this.style.display = 'none'"><?php echo Flash::get('success') ?></div>
<?php } ?>
    <form action="<?php echo get_url('login', 'forgot') ?>" method="post">
      <div>
        <label for="forgot-email"><?php echo __('Email address'); ?>:</label>
        <input class="long" id="forgot-email" type="text" name="forgot[email]" value="<?php echo $email ?>" />
      </div>
      <div id="forgot-submit">
        <input class="submit" type="submit" accesskey="s" value="<?php echo __('Send password'); ?> (Alt+S)" />
        <span>(<a href="<?php echo get_url('login') ?>"><?php echo __('Login'); ?></a>)</span>
      </div>
    </form>
  </div>
  <script type="text/javascript" language="javascript" charset="utf-8">
  // <![CDATA[
  document.getElementById('forgot-email').focus();
  // ]]>
  </script>
</body>
</html>