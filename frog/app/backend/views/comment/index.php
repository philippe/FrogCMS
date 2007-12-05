<h1><?php echo __('Comments') ?></h1>

<div id="comments-def">
    <div class="comment"><?php echo __('Comments') ?></div>
    <div class="modify"><?php echo __('Modify') ?></div>
</div>

<?php if (count($comments)): ?>
<ol id="comments">
<?php foreach($comments as $comment): ?>

    <li class="<?php echo odd_even() ?> <?php echo ($comment->is_approved) ? 'approve': 'unapprove' ?>">
          <a href="<?php echo get_url('comment/edit/'.$comment->id) ?>"><b><?php echo $comment->page_title ?></b></a>
          <p><?php echo $comment->body ?></p>
          <div class="infos">
              <?php echo date('D, j M Y', strtotime($comment->created_on)) ?> &mdash; 
              <a href="<?php echo get_url('comment/delete/'.$comment->id) ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete it?') ?>');"><?php echo __('Delete') ?></a> | 
<?php if ($comment->is_approved): ?>
              <a href="<?php echo get_url('comment/unapprove/'.$comment->id) ?>"><?php echo __('Unapprove') ?></a>
<?php else: ?>
              <a href="<?php echo get_url('comment/approve/'.$comment->id) ?>"><?php echo __('Approve') ?></a>
<?php endif; ?>
          </div>
      </li>
<?php endforeach; ?>
</ol>
<?php else: // no comments ?>
<h3><?php echo __('No comments found.') ?></h3>
<?php endif; ?>
