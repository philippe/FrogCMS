<div class="page" id="page-<?php echo $index; ?>">
  <div class="part" id="part-<?php echo $index; ?>">
    <input id="part_<?php echo ($index-1); ?>_name" name="part[<?php echo ($index-1); ?>][name]" type="hidden" value="<?php echo $name; ?>" />
    <p>
      <label for="part_<?php echo ($index-1); ?>_filter_id"><?php echo __('Filter'); ?></label>
      <select id="part_<?php echo ($index-1); ?>_filter_id" name="part[<?php echo ($index-1); ?>][filter_id]" onchange="setTextAreaToolbar('part_<?php echo ($index-1); ?>_content', this[this.selectedIndex].value)">
        <option value=""<?php if(isset($filter_id) && $filter_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none'); ?> &#8212;</option>
<?php foreach ($filters as $filter): ?> 
        <option value="<?php echo $filter; ?>"<?php if($filter_id == $filter) echo ' selected="selected"'; ?>><?php echo $filter; ?></option>
<?php endforeach; ?> 
      </select>
    </p>
    <div><textarea class="textarea" id="part_<?php echo ($index-1); ?>_content" name="part[<?php echo ($index-1); ?>][content]" style="width: 100%" rows="20" cols="40"
                   onkeydown="return allowTab(event, this);"
                   onkeyup="return allowTab(event,this);"
                   onkeypress="return allowTab(event,this);"><?php if(isset($content)) echo htmlentities($content, ENT_COMPAT, 'UTF-8'); ?></textarea></div>
  </div>
</div>
<script type="text/javascript">
    setTextAreaToolbar('part_<?php echo ($index-1); ?>_content', '<?php echo $filter_id; ?>');
</script>