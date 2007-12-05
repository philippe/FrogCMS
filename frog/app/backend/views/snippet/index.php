<h1><?php echo __('Snippets'); ?></h1>

<table id="snippets" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="snippet"><?php echo __('Snippet'); ?></th>
      <th class="modify"><?php echo __('Modify'); ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach($snippets as $snippet): ?>

    <tr class="node <?php echo odd_even(); ?>">
      <td class="snippet">
        <img align="middle" alt="snippet-icon" src="images/snippet.png" />
        <a href="<?php echo get_url('snippet/edit/'.$snippet->id); ?>"><?php echo $snippet->name; ?></a>
      </td>
      <td class="remove"><a href="<?php echo get_url('snippet/delete/'.$snippet->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete'); ?> <?php echo $snippet->name; ?>?');"><img src="images/icon-remove.gif" alt="remove icon" /></a></td>
    </tr>

<?php endforeach; ?>

  </tbody>
</table>
