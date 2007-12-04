<h1><?php echo __(ucfirst($action).' snippet') ?></h1>

<form action="<?php echo $action=='edit' ? get_url('snippet/edit/'.$snippet->id): get_url('snippet/add');  ?>" method="post">
  <div class="form-area">
    <p class="title">
      <label for="snippet_name"><?php echo __('Name') ?></label>
      <input class="textbox" id="snippet_name" maxlength="100" name="snippet[name]" size="100" type="text" value="<?php echo $snippet->name ?>" />
    </p>
    <p class="content">
      <label for="snippet_content"><?php echo __('Body') ?></label>
      <textarea class="textarea" cols="40" id="snippet_content" name="snippet[content]" rows="20" style="width: 100%" 
                onkeydown="return allowTab(event, this);"
                onkeyup="return allowTab(event,this);"
                onkeypress="return allowTab(event,this);"><?php echo htmlentities($snippet->content, ENT_COMPAT, 'UTF-8') ?></textarea>
    </p>
    <p>
      <label for="snippet_filter_id"><?php echo __('Filter') ?></label>
      <select id="snippet_filter_id" name="snippet[filter_id]" onchange="setTextAreaToolbar('snippet_content', this[this.selectedIndex].value)">
        <option value=""<?php if($snippet->filter_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none') ?> &#8212;</option>
<?php foreach ($filters as $filter) { ?>
        <option value="<?php echo $filter ?>"<?php if($snippet->filter_id == $filter) echo ' selected="selected"'; ?>><?php echo $filter ?></option>
<?php } // foreach ?>
      </select>
    </p>
<?php if (isset($snippet->updated_on)) { ?>
    <p style="clear: left"><small><?php echo __('Last updated by') ?> <?php echo $snippet->updated_by_name ?> <?php echo __('on') ?> <?php echo date('D, j M Y', strtotime($snippet->updated_on)) ?></small></p>
<?php } ?>
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save') ?> (Alt+S)" />
    <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing') ?> (Alt+E)" />
    <?php echo __('or') ?> <a href="<?php echo get_url('snippet') ?>"><?php echo __('Cancel') ?></a>
  </p>
</form>

<script type="text/javascript">
// <![CDATA[
  setTextAreaToolbar('snippet_content', '<?php echo $snippet->filter_id ?>');
  document.getElementById('snippet_name').focus();
// ]]>
</script>