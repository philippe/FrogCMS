<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title><?php echo __('Login') ?></title>
  <link href="stylesheets/login.css" rel="Stylesheet" type="text/css" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
  <div id="dialog">
    <h1><?php echo __('Login') ?></h1>
<?php if (Flash::get('error') !== null) { ?>
        <div id="error" onclick="this.style.display = 'none'"><?php echo Flash::get('error') ?></div>
<?php } ?>
    <form action="<?php echo get_url('login/login') ?>" method="post">
      <div id="login-username-div">
        <label for="login-username"><?php echo __('Username') ?>:</label>
        <input id="login-username" class="medium" type="text" name="login[username]" value="" />
      </div>
      <div id="login-password-div">
        <label for="login-password"><?php echo __('Password') ?>:</label>
        <input id="login-password" class="medium" type="password" name="login[password]" value="" />
      </div>
      <div class="clean"></div>
      <div style="margin-top: 6px">
        <input id="login-remember-me" type="checkbox" class="checkbox" name="login[remember]" value="checked" />
        <label class="checkbox" for="login-remember-me"><?php echo __('Remember me for 14 days') ?></label>
      </div>
      <div id="login_submit">
        <input class="submit" type="submit" accesskey="s" value="<?php echo __('Login') ?> (Alt+S)" />
        <span>(<a href="<?php echo get_url('login/forgot') ?>"><?php echo __('Forgot password?') ?></a>)</span>
      </div>
    </form>
  </div>
  <script type="text/javascript" language="javascript" charset="utf-8">
  // <![CDATA[
  var loginUsername = document.getElementById('login-username');
  if (loginUsername.value == '') {
    loginUsername.focus();
  } else {
    document.getElementById('login-password').focus();
  }
  // ]]>
  </script>
</body>
</html>