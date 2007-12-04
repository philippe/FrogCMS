<?php if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('user/add') ?>"><img src="images/user.png" align="middle" alt="user icon" /> <?php echo __('New User') ?></a></p>

<div class="box">
    <h2><?php echo __('Where avatar come from?') ?></h2>
    <p><?php echo __('They are directly linked with <a href="http://www.gravatar.com/" target="_blank">Gravatar</a> site. It is free!') ?></p>
</div>

<?php endif; ?>