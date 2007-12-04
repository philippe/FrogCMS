<h1><?php echo __('Comments') ?></h1>

<div id="comments-def">
    <div class="comment"><?php echo __('Comments') ?></div>
    <div class="modify"><?php echo __('Modify') ?></div>
</div>

<?php if (count($comments)): ?>
<ol id="comments">
<?php foreach($comments as $comment): ?>

    <li class="<?php echo odd_even() ?>">
          <a href="<?php echo get_url('comment/edit/'.$comment->id) ?>"><b><?php echo $comment->page_title ?></b></a>
          <p><?php echo $comment->body ?></p>
          <div class="infos">
              <?php echo date('D, j M Y', strtotime($comment->created_on)) ?> &mdash; 
              <a href="<?php echo get_url('comment/delete/'.$comment->id) ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete it?') ?>');"><img src="images/icon-remove.gif" alt="remove icon" /></a>
          </div>
      </li>
<?php endforeach; ?>
</ol>
<?php else: // no comments ?>
<h3><?php echo __('No comments found.') ?></h3>
<?php endif; ?>
