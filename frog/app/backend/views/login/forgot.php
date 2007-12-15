<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title><?php echo __('Forgot password'); ?></title>
  <base href="<?php echo trim(BASE_URL, '?/').'/'; ?>" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <link href="stylesheets/login.css" rel="Stylesheet" type="text/css" />
  <link href="themes/<?php echo Setting::get('theme'); ?>.css" id="css_theme" media="screen" rel="Stylesheet" type="text/css" />
  <script src="javascripts/protolous.js" type="text/javascript"></script>
</head>
<body>
  <div id="dialog">
    <h1><?php echo __('Forgot password'); ?></h1>
<?php if (Flash::get('error') != null) { ?>
    <div id="error" style="display: none"><?php echo Flash::get('error'); ?></div>
    <script type="text/javascript">Effect.Appear('error', {duration:.5});</script>
<?php } ?>
<?php if (Flash::get('success') != null) { ?>
    <div id="success" style="display: none"><?php echo Flash::get('success'); ?></div>
    <script type="text/javascript">Effect.Appear('success', {duration:.5});</script>
<?php } ?>
    <form action="<?php echo get_url('login', 'forgot'); ?>" method="post">
      <div>
        <label for="forgot-email"><?php echo __('Email address'); ?>:</label>
        <input class="long" id="forgot-email" type="text" name="forgot[email]" value="<?php echo $email; ?>" />
      </div>
      <div id="forgot-submit">
        <input class="submit" type="submit" accesskey="s" value="<?php echo __('Send password'); ?>" />
        <span>(<a href="<?php echo get_url('login'); ?>"><?php echo __('Login'); ?></a>)</span>
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