<h1><?php echo __(ucfirst($action).' Page'); ?></h1>

<form action="<?php if ($action == 'add') echo get_url('page/add'); else echo  get_url('page/edit/'.$page->id); ?>" method="post">

  <input id="page_parent_id" name="page[parent_id]" type="hidden" value="<?php echo $page->parent_id; ?>" />
  <div class="form-area">
    <div id="tab-control-meta">
        <div id="tabs-meta" class="tabs">
            <div id="tab-meta-toolbar">&nbsp;</div>
        </div>
        <div id="meta-divs">
            <div id="div-title" class="title" style="display: none">
              <input class="textbox" id="page_title" maxlength="255" name="page[title]" size="255" type="text" value="<?php echo $page->title; ?>" />
            </div>
            <div id="div-metadata" style="display: none">
              <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="label"><label for="page_slug"><?php echo __('Slug'); ?></label></td>
                  <td class="field"><input class="textbox" id="page_slug" maxlength="100" name="page[slug]" size="100" type="text" value="<?php echo Page::getSlug($page->slug); ?>" /></td>
                </tr>
                <tr>
                  <td class="label"><label for="page_breadcrumb"><?php echo __('Breadcrumb'); ?></label></td>
                  <td class="field"><input class="textbox" id="page_breadcrumb" maxlength="160" name="page[breadcrumb]" size="160" type="text" value="<?php echo htmlentities($page->breadcrumb, ENT_COMPAT, 'UTF-8'); ?>" /></td>
                </tr>
                <tr>
                  <td class="label optional"><label for="page_tags"><?php echo __('Tags'); ?></label></td>
                  <td class="field"><input class="textbox" id="page_tags" maxlength="255" name="page_tag[tags]" size="255" type="text" value="<?php echo join(', ', $tags); ?>" /></td>
                </tr>
<?php if (isset($page->created_on)): ?>
                <tr>
                  <td class="label"><label for="page_created_on"><?php echo __('Created date'); ?></label></td>
                  <td class="field">
                    <input id="page_created_on" maxlength="10" name="page[created_on]" size="10" type="text" value="<?php echo substr($page->created_on, 0, 10); ?>" /> 
                    <img onclick="displayDatePicker('page[created_on]');" src="images/icon_cal.gif" alt="<?php echo __('Show Calendar'); ?>" /> 
                    <input id="page_created_on_time" maxlength="5" name="page[created_on_time]" size="5" type="text" value="<?php echo substr($page->created_on, 11); ?>" />
                  </td>
                </tr>
<?php endif; ?>
<?php if (isset($page->published_on)): ?>
                <tr>
                  <td class="label"><label for="page_published_on"><?php echo __('Published date'); ?></label></td>
                  <td class="field">
                    <input id="page_published_on" maxlength="10" name="page[published_on]" size="10" type="text" value="<?php echo substr($page->published_on, 0, 10); ?>" /> 
                    <img onclick="displayDatePicker('page[published_on]');" src="images/icon_cal.gif" alt="<?php echo __('Show Calendar'); ?>" />
                    <input id="page_published_on_time" maxlength="5" name="page[published_on_time]" size="5" type="text" value="<?php echo substr($page->published_on, 11); ?>" /> 
                  </td>
                </tr>
<?php endif; ?>
              </table>
              <script type="text/javascript">
              // <![CDATA[
                /*$title = $('page_title');
                $slug = $('page_slug');
                $breadcrumb = $('page_breadcrumb');
                $old_title = $title.value || '';
                function title_updated() {
                  if ($old_title.toSlug() == $slug.value) $slug.value = $title.value.toSlug();
                  if ($old_title == $breadcrumb.value) $breadcrumb.value = $title.value;
                  $old_title = $title.value;
                }
                Event.observe('page_title', 'keyup', title_updated);*/
              // ]]>
              </script>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    // <![CDATA[
      var tabControlMeta = new TabControl('tab-control-meta');
      tabControlMeta.addTab('tab-title', '<?php echo __('Page Title'); ?>', 'div-title');
      tabControlMeta.addTab('tab-metadata', '<?php echo __('Metadata'); ?>', 'div-metadata');
      tabControlMeta.select(tabControlMeta.firstTab());
    // ]]>
    </script>

    <div id="tab-control">
      <div id="tabs" class="tabs">
        <div id="tab-toolbar">
          <a href="#" onclick="toggle_add_part_popup(); return false;" title="<?php echo __('Add Tab'); ?>"><img src="images/plus.png" alt="plus icon" /></a>
          <a href="#" onclick="if(tabControl._tabify(tabControl.selected).tab_id == 'tab-1') { alert('You can\'t remove the Body Tab');} else if(confirm('<?php echo __('Delete the current tab?'); ?>')) { tabControl.removeTab(tabControl.selected) }; return false;" title="<?php echo __('Remove Tab'); ?>"><img src="images/minus.png" alt="minus icon" /></a>
        </div>
      </div>
      <div id="pages" class="pages">
<?php $index_part =1; foreach ($page_parts as $page_part): ?>
<div class="page" id="page-<?php echo $index_part; ?>">
  <div class="part" id="part-<?php echo $index_part; ?>">
    <input id="part_<?php echo ($index_part-1); ?>_name" name="part[<?php echo ($index_part-1); ?>][name]" type="hidden" value="<?php echo $page_part->name; ?>" />
<?php if (isset($page_part->id)) { ?>
    <input id="part_<?php echo ($index_part-1); ?>_id" name="part[<?php echo ($index_part-1); ?>][id]" type="hidden" value="<?php echo $page_part->id; ?>" />
<?php } ?>
    <p>
      <label for="part_<?php echo ($index_part-1); ?>_filter_id"><?php echo __('Filter'); ?></label>
      <select id="part_<?php echo ($index_part-1); ?>_filter_id" name="part[<?php echo ($index_part-1); ?>][filter_id]" onchange="setTextAreaToolbar('part_<?php echo ($index_part-1); ?>_content', this[this.selectedIndex].value)">
        <option value=""<?php if ($page_part->filter_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none'); ?> &#8212;</option>
<?php foreach ($filters as $filter): ?>
        <option value="<?php echo $filter; ?>"<?php if ($page_part->filter_id == $filter) echo ' selected="selected"'; ?>><?php echo $filter; ?></option>
<?php endforeach; ?>
      </select>
    </p>
    <div><textarea class="textarea" id="part_<?php echo ($index_part-1); ?>_content" name="part[<?php echo ($index_part-1); ?>][content]" style="width: 100%" rows="20" cols="40"
                   onkeydown="return allowTab(event, this);"
                   onkeyup="return allowTab(event,this);"
                   onkeypress="return allowTab(event,this);"><?php echo htmlentities($page_part->content, ENT_COMPAT, 'UTF-8'); ?></textarea></div>
  </div>
</div>
<script type="text/javascript">
    setTextAreaToolbar('part_<?php echo ($index_part-1); ?>_content', '<?php echo $page_part->filter_id; ?>');
</script>
<?php $index_part++; endforeach; ?>
      </div>
    </div>
    <script type="text/javascript">
    // <![CDATA[
/*      var tabControl = new TabControl('tab-control');
<?php $index_part=1; foreach ($page_parts as $page_part): ?>
      tabControl.addTab('tab-<?php echo $index_part; ?>', '<?php echo $page_part->name; ?>', 'page-<?php echo $index_part; ?>');
<?php $index_part++; ?>
<?php endforeach; ?>
      tabControl.select(tabControl.firstTab());*/
    // ]]>
    </script>
    
    <div class="row">
      <p><label for="page_layout_id"><?php echo __('Layout'); ?></label>
        <select id="page_layout_id" name="page[layout_id]">
          <option value="">&#8212; <?php echo __('inherit'); ?> &#8212;</option>
<?php foreach ($layouts as $layout): ?>
          <option value="<?php echo $layout->id; ?>"<?php echo $layout->id == $page->layout_id ? ' selected="selected"': ''; ?>><?php echo $layout->name; ?></option>
<?php endforeach; ?>
        </select>
      </p>

      <p><label for="page_behavior_id"><?php echo __('Page Type'); ?></label>
        <select id="page_behavior_id" name="page[behavior_id]">
          <option value=""<?php if ($page->behavior_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none'); ?> &#8212;</option>
<?php foreach ($behaviors as $behavior): ?>
          <option value="<?php echo $behavior; ?>"<?php if ($page->behavior_id == $behavior) echo ' selected="selected"'; ?>><?php echo Inflector::humanize($behavior); ?></option>
<?php endforeach; ?>
        </select>
      </p>

      <p><label for="page_status_id"><?php echo __('Status'); ?></label>
        <select id="page_status_id" name="page[status_id]">
          <option value="<?php echo Page::STATUS_DRAFT; ?>"<?php echo $page->status_id == Page::STATUS_DRAFT ? ' selected="selected"': ''; ?>><?php echo __('Draft'); ?></option>
          <option value="<?php echo Page::STATUS_REVIEWED; ?>"<?php echo $page->status_id == Page::STATUS_REVIEWED ? ' selected="selected"': ''; ?>><?php echo __('Reviewed'); ?></option>
          <option value="<?php echo Page::STATUS_PUBLISHED; ?>"<?php echo $page->status_id == Page::STATUS_PUBLISHED ? ' selected="selected"': ''; ?>><?php echo __('Published'); ?></option>
          <option value="<?php echo Page::STATUS_HIDDEN; ?>"<?php echo $page->status_id == Page::STATUS_HIDDEN ? ' selected="selected"': ''; ?>><?php echo __('Hidden'); ?></option>
        </select>
      </p>
      
<?php if (Setting::get('enable_comment')): ?> 
      <p><label for="page_comment_status"><?php echo __('Comments'); ?></label>
         <select id="page_comment_status" name="page[comment_status]">
           <option value="none"<?php echo $page->comment_status == 'none' ? ' selected="selected"': ''; ?>>&#8212; <?php echo __('none'); ?> &#8212;</option>
           <option value="open"<?php echo $page->comment_status == 'open' ? ' selected="selected"': ''; ?>><?php echo __('Open'); ?></option>
           <option value="closed"<?php echo $page->comment_status == 'closed' ? ' selected="selected"': ''; ?>><?php echo __('Closed'); ?></option>
         </select>
      </p>
<?php endif; ?> 

    </div>
    <p class="clear">&nbsp;</p>
    
<?php if (AuthUser::hasPermission('administrator') || AuthUser::hasPermission('developer')): ?>
    <p style="float: right"><input id="page_is_protected" name="page[is_protected]" class="checkbox" type="checkbox" value="1"<?php if ($page->is_protected) echo ' checked="checked"'; ?>/><label for="page_is_protected"> <?php echo __('protected'); ?> </label></p>
<?php endif; ?>
    <p><small>
<?php if (isset($page->updated_on)): ?>
    <?php echo __('Last updated by'); ?> <?php echo $page->updated_by_name; ?> <?php echo __('on'); ?> <?php echo date('D, j M Y', strtotime($page->updated_on)); ?>
<?php endif; ?>
    &nbsp;
    </small></p>


  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?> (Alt+S)" />
    <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?> (Alt+E)" />
    <?php echo __('or'); ?> <a href="<?php echo get_url('page'); ?>"><?php echo __('Cancel'); ?></a>
  </p>

</form>

<div id="popups">
  <div class="popup" id="add-part-popup" style="display:none;">
    <div id="busy" class="busy" style="display: none"><img alt="Spinner" src="images/spinner.gif" /></div>
    <h3><?php echo __('Add Part'); ?></h3>
    <!--form action="<?php echo get_url('page/addPart'); ?>" method="post" onsubmit="if (valid_part_name()) { new Ajax.Updater('page_parts', '<?php echo get_url('page/addPart'); ?>', {asynchronous:true, evalScripts:true, insertion:Insertion.Bottom, onComplete:function(request){part_added()}, onLoading:function(request){part_loading()}, parameters:Form.serialize(this)}); }; return false;"-->
    <form action="<?php echo get_url('page/addPart'); ?>" method="post" onsubmit="if (valid_part_name()) { new Ajax.Updater('pages', '<?php echo get_url('page/addPart'); ?>', {asynchronous:true, evalScripts:true, insertion:Insertion.Bottom, onComplete:function(request){part_added()}, onLoading:function(request){part_loading()}, parameters:Form.serialize(this)}); }; return false;"> 
      <div>
        <input id="part-index-field" name="part[index]" type="hidden" value="<?php echo $index_part; ?>" />
        <input id="part-name-field" maxlength="100" name="part[name]" type="text" value="" /> 
        <input id="add-part-button" name="commit" type="submit" value="<?php echo __('Add'); ?>" />
      </div>
      <p><a class="close-link" href="#" onclick="Element.hide('add-part-popup'); return false;"><?php echo __('Close'); ?></a></p>
    </form>
  </div>
</div>
<script type="text/javascript">
    Field.activate('page_title');
</script>