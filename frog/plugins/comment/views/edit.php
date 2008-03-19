<h1><?php echo __('Edit comment'); ?></h1>

<form action="<?php echo get_url('plugin/comment/edit/'.$comment->id); ?>" method="post">
  <div class="form-area">
    <p class="content">
      <label for="comment_body"><?php echo __('Body'); ?></label>
      <textarea class="textarea" cols="40" id="comment_body" name="comment[body]" rows="20" style="width: 100%" onkeydown="return allowTab(event, this);"><?php echo htmlentities($comment->body, ENT_COMPAT, 'UTF-8'); ?></textarea>
    </p>
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    <?php echo __('or'); ?> <a href="<?php echo get_url('comment'); ?>"><?php echo __('Cancel'); ?></a>
  </p>
</form>
