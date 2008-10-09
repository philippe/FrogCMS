<?php if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('translate/core'); ?>"><img src="images/file.png" align="middle" alt="document icon" /> <?php echo __('Create Core template'); ?></a></p>
<p class="button"><a href="<?php echo get_url('translate/plugins'); ?>"><img src="images/file.png" align="middle" alt="document icon" /> <?php echo __('Create Plugin templates'); ?></a></p>

<div class="box">
    <h2>What is the difference...</h2>
    <p>between the core translation template and the plugin translation template?</p>
    <p>Easy. If you select the plugin translation template, the output that is generated will be one file containing all template files for all plugins that are installed.</p>
    <p>Provided that the plugins support tranlations offcourse.</p>
    <p>You will have to manually copy-paste the various plugin translation templates to their own files.</p>
</div>

<?php endif; ?>