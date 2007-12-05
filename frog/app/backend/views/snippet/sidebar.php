<?php if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('snippet/add'); ?>"><img src="images/snippet.png" align="middle" alt="snippet icon" /> <?php echo __('New Snippet'); ?></a></p>

<div class="box">
    <h2><?php echo __('What is a snippet?'); ?></h2>
    <p><?php echo __('Snippets are generally small pieces of content which are included in other pages or layouts.'); ?></p>
</div>

<?php endif; ?>