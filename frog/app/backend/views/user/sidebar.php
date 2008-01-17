<?php if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('user/add'); ?>"><img src="images/user.png" align="middle" alt="user icon" /> <?php echo __('New User'); ?></a></p>

<div class="box">
    <h2><?php echo __('Where do the avatars come from?'); ?></h2>
    <p><?php echo __('The avatars are automatically linked for those with a <a href="http://www.gravatar.com/" target="_blank">Gravatar</a> (a free service) account.'); ?></p>
</div>

<?php endif; ?>