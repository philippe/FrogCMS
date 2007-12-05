<?php if (Dispatcher::getAction() == 'index'): ?>

<p class="button"><a href="<?php echo get_url('layout/add'); ?>"><img src="images/layout.png" align="middle" alt="layout icon" /> <?php echo __('New Layout'); ?></a></p>

<div class="box">
<h2><?php echo __('What is a layout?'); ?></h2>
<p><?php echo __('Use layouts to apply a visual look to a Web page. Layouts can contain special tags to include
  page content and other elements such as the header or footer. Click on a layout name below to
  edit it or click <strong>Remove</strong> to delete it.'); ?></p>
</div>

<?php endif; ?>