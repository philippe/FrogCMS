<h1><?php echo __('Users'); ?></h1>

<table id="users" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th><?php echo __('Name'); ?> / <?php echo __('Username'); ?></th>
      <th><?php echo __('Email'); ?></th>
      <th><?php echo __('Roles'); ?></th>
      <th><?php echo __('Modify'); ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach($users as $user): ?> 
    <tr class="node <?php echo odd_even(); ?>">
      <td class="user">
        <img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($user->email); ?>&amp;default=<?php echo URL_PUBLIC; ?>/admin/images/user.png&amp;size=32" align="middle" alt="user icon" />
        <a href="<?php echo get_url('user/edit/'.$user->id); ?>"><?php echo $user->name; ?></a>
        <small><?php echo $user->username; ?></small>
      </td>
      <td><?php echo $user->email; ?></td>
      <td><?php echo implode(', ', $user->getPermissions()); ?></td>
      <td>
<?php if ($user->id > 1): ?>
        <a href="<?php echo get_url('user/delete/'.$user->id); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete it?'); ?>');"><img src="images/icon-remove.gif" alt="remove icon" /></a>
<?php else: ?>
        <img src="images/icon-remove-disabled.gif" alt="remove icon disabled" />
<?php endif; ?>
      </td>
    </tr>
<?php endforeach; ?> 
  </tbody>
</table>
