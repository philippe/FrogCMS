<div class="page" id="page-<?php echo $index; ?>">
  <div class="part" id="part-<?php echo $index; ?>">
    <input id="part_<?php echo ($index-1); ?>_name" name="part[<?php echo ($index-1); ?>][name]" type="hidden" value="<?php echo $page_part->name; ?>" />
    <?php if (isset($page_part->id)): ?>
    <input id="part_<?php echo ($index-1); ?>_id" name="part[<?php echo ($index-1); ?>][id]" type="hidden" value="<?php echo $page_part->id; ?>" />
    <?php endif; ?>
    <p>
      <label for="part_<?php echo ($index-1); ?>_filter_id"><?php echo __('Filter'); ?></label>
      <select id="part_<?php echo ($index-1); ?>_filter_id" name="part[<?php echo ($index-1); ?>][filter_id]" onchange="setTextAreaToolbar('part_<?php echo ($index-1); ?>_content', this[this.selectedIndex].value)">
        <option value=""<?php if ($page_part->filter_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none'); ?> &#8212;</option>
<?php foreach (Filter::findAll() as $filter): ?> 
        <option value="<?php echo $filter; ?>"<?php if ($page_part->filter_id == $filter) echo ' selected="selected"'; ?>><?php echo $filter; ?></option>
<?php endforeach; ?> 
      </select>
    </p>
    <div><textarea class="textarea" id="part_<?php echo ($index-1); ?>_content" name="part[<?php echo ($index-1); ?>][content]" style="width: 100%" rows="20" cols="40"
                   onkeydown="return allowTab(event, this);"
                   onkeyup="return allowTab(event,this);"
                   onkeypress="return allowTab(event,this);"><?php echo htmlentities($page_part->content, ENT_COMPAT, 'UTF-8'); ?></textarea></div>
  </div>
</div>
<script type="text/javascript">
    setTextAreaToolbar('part_<?php echo ($index-1); ?>_content', '<?php echo $page_part->filter_id; ?>');
</script>