<h1><?php echo __('Layouts'); ?></h1>

<div id="layouts-def" class="index-def">
  <div class="layout"><?php echo __('Layout'); ?> (<a href="#" onclick="$$('.handle').each(function(e) { e.style.display = e.style.display != 'inline' ? 'inline': 'none'; }); return false;"><?php echo __('reorder'); ?></a>)</div>
</div>

<ul id="layouts" class="index">
<?php foreach($layouts as $layout) { ?>
  <li id="layout_<?php echo $layout->id; ?>" class="layout node <?php echo odd_even(); ?>">
    <img align="middle" alt="layout-icon" src="images/layout.png" title="" />
    <a href="<?php echo get_url('layout/edit/'.$layout->id); ?>"><?php echo $layout->name; ?></a>
    <img class="handle" src="images/drag.gif" alt="<?php echo __('Drag and Drop'); ?>" align="middle" />
    <div class="remove"><a href="<?php echo get_url('layout/delete/'.$layout->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete'); ?> <?php echo $layout->name; ?>?');"><img alt="<?php echo __('Remove Layout'); ?>" src="images/icon-remove.gif" /></a></div>
  </li>
<?php } ?>
</ul>

<script type="text/javascript" language="javascript" charset="utf-8">
Sortable.create('layouts', {
    constraint: 'vertical',
    scroll: window,
    handle: 'handle',
    onUpdate: function() {
        new Ajax.Request('index.php?/layout/reorder', {method: 'post', parameters: {data: Sortable.serialize('layouts')}});
    }
});
</script>