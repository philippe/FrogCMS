<h1><?php echo __('Pages'); ?></h1>

<div id="site-map-def">
    <div class="page"><?php echo __('Page'); ?> (<a href="#" onclick="$$('.handle').each(function(e) { e.style.display = e.style.display == 'inline' ? 'none': 'inline'; }); return false;"><?php echo __('reorder'); ?></a>)</div>
    <div class="status"><?php echo __('Status'); ?></div>
    <div class="modify"><?php echo __('Modify'); ?></div>
</div>

<ul id="site-map-root">
    <li id="page_root" class="node level-0">
      <div class="page" style="padding-left: 4px">
        <span class="w1">
<?php if (AuthUser::hasPermission('editor') && $root->is_protected): ?>
          <img align="middle" class="icon" src="images/page.png" alt="page icon" /> <span class="title"><?php echo $root->title; ?></span>
<?php else: ?>
          <a href="<?php echo get_url('page/edit/1'); ?>" title="/"><img align="middle" class="icon" src="images/page.png" alt="page icon" /> <span class="title"><?php echo $root->title; ?></span></a>
<?php endif; ?>
        </span>
      </div>
      <div class="status published-status"><?php echo __('Published'); ?></div>
      <div class="modify">
          <a href="<?php echo get_url('page/add/1'); ?>"><img src="images/plus.png" align="middle" alt="<?php echo __('Add child'); ?>" /></a>&nbsp; 
          <img class="remove" src="images/icon-remove-disabled.gif" align="middle" alt="remove icon disabled" />
      </div>
    </li>
</ul>

<?php echo $content_children; ?>

<script type="text/javascript">
// <![CDATA[
  new SiteMap('site-map', [1]);
// ]]>
</script>
