<h1><?php echo __('Snippets'); ?></h1>

<div id="site-map-def" class="index-def">
  <div class="snippet"><?php echo __('Snippet'); ?> (<a href="#" onclick="$$('.handle').each(function(e) { e.style.display = e.style.display != 'inline' ? 'inline': 'none'; }); return false;"><?php echo __('reorder'); ?></a>)</div><div class="modify"><?php echo __('Modify'); ?></div>
</div>

<ul id="snippets" class="index">
<?php foreach($snippets as $snippet): ?>
  <li id="snippet_<?php echo $snippet->id; ?>" class="snippet node <?php echo odd_even(); ?>">
    <img align="middle" alt="snippet-icon" src="images/snippet.png" />
    <a href="<?php echo get_url('snippet/edit/'.$snippet->id); ?>"><?php echo $snippet->name; ?></a>
    <img class="handle" src="images/drag.gif" alt="<?php echo __('Drag and Drop'); ?>" align="middle" />
    <div class="remove"><a class="remove" href="<?php echo get_url('snippet/delete/'.$snippet->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete'); ?> <?php echo $snippet->name; ?>?');"><img src="images/icon-remove.gif" alt="remove icon" /></a></div>
  </li>
<?php endforeach; ?>
</ul>

<script type="text/javascript" language="javascript" charset="utf-8">
Sortable.create('snippets', {
    constraint: 'vertical',
    scroll: window,
    handle: 'handle',
    onUpdate: function() {
        new Ajax.Request('index.php?/snippet/reorder', {method: 'post', parameters: {data: Sortable.serialize('snippets')}});
    }
});
</script>
