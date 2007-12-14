<h1><?php echo __('Administration'); ?></h1>

<div class="form-area">
<div id="tab-control-admin" class="tab_control">
    <div id="tabs-admin" class="tabs">
        <div id="tab-admin-toolbar" class="tab_toolbar">&nbsp;</div>
    </div>
    <div id="admin-pages" class="pages">

        <div id="plugin-page" class="page">

<table id="plugins" class="index" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr>
      <th class="plugin"><?php echo __('Plugin'); ?></th>
      <th class="website"><?php echo __('Website'); ?></th>
      <th class="version"><?php echo __('Version'); ?></th>
      <th class="enabled"><?php echo __('Enabled'); ?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach(Plugin::findAll() as $plugin): ?>
    <tr>
      <td class="plugin">
        <h4><?php echo $plugin->title; ?></h4>
        <p><?php echo $plugin->description; ?></p>
      </td>
      <td class="website"><a href="<?php echo $plugin->website; ?>" target="_blank"><?php echo __('Website') ?></a></td>
      <td class="version"><?php echo $plugin->version; ?></td>
      <td class="enabled"><input type="checkbox" name="enabled_<?php echo $plugin->id; ?>" value="<?php echo $plugin->id; ?>"<?php if (isset(Plugin::$plugins[$plugin->id])) echo ' checked="checked"'; ?> onclick="new Ajax.Request('<?php echo get_url('setting'); ?>'+(this.checked ? '/activate_plugin/':'/deactivate_plugin/')+this.value, {method: 'get'});" /></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<script type="text/javascript">
// <![CDATA[
  new RuledTable('plugins');
// ]]>
</script>

        </div>
        <div id="setting-page" class="page">
            
<form action="<?php echo get_url('setting'); ?>" method="post">
  <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="label"><label for="setting_admin_title"><?php echo __('Admin Site title'); ?></label></td>
        <td class="field"><input class="textbox" id="setting_admin_title" maxlength="255" name="setting[admin_title]" size="255" type="text" value="<?php echo htmlentities(Setting::get('admin_title'), ENT_COMPAT, 'UTF-8'); ?>" /></td>
        <td class="help"><?php echo __('By using <strong>&lt;img src="img_path" /&gt;</strong> you can set your company logo instead of a title.'); ?></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_language"><?php echo __('Language'); ?></label></td>
        <td class="field">
          <select class="select" id="setting_language" name="setting[language]">
<?php $current_language = Setting::get('language'); ?>
<?php foreach (Setting::getLanguages() as $code => $label): ?>
            <option value="<?php echo $code; ?>"<?php if ($code == $current_language) echo ' selected="selected"'; ?>><?php echo __($label); ?></option>
<?php endforeach; ?>
          </select>
        </td>
        <td class="help"><?php echo __('This will set your language for the backend.'); ?></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_theme"><?php echo __('Administration Theme'); ?></label></td>
        <td class="field">
          <select class="select" id="setting_language" name="setting[theme]" onchange="$('css_theme').href = 'themes/' + this[this.selectedIndex].value + '.css';">
<?php $current_theme = Setting::get('theme'); ?>
<?php foreach (Setting::getThemes() as $code => $label): ?>
            <option value="<?php echo $code; ?>"<?php if ($code == $current_theme) echo ' selected="selected"'; ?>><?php echo __($label); ?></option>
<?php endforeach; ?>
          </select>
        </td>
        <td class="help"><?php echo __('This will change your Administration theme.'); ?></td>
      </tr>
      <tr>
        <td colspan="3"><h3><?php echo __('Page options'); ?></h3></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_default_status_id-draft"><?php echo __('Default Status'); ?></label></td>
        <td class="field">
          <input class="radio" id="setting_default_status_id-draft" name="setting[default_status_id]" size="10" type="radio" value="<?php echo Page::STATUS_DRAFT; ?>"<?php if (Setting::get('default_status_id') == Page::STATUS_DRAFT) echo ' checked="checked"'; ?> /><label for="setting_default_status_id-draft"> <?php echo __('Draft'); ?> </label> &nbsp; 
          <input class="radio" id="setting_default_status_id-published" name="setting[default_status_id]" size="10" type="radio" value="<?php echo Page::STATUS_PUBLISHED; ?>"<?php if (Setting::get('default_status_id') == Page::STATUS_PUBLISHED) echo ' checked="checked"'; ?> /><label for="setting_default_status_id-published"> <?php echo __('Published'); ?> </label>
        </td>
        <td class="help">&nbsp;</td>
      </tr>
      <tr>
        <td class="label"><label for="setting_default_filter_id"><?php echo __('Default Filter'); ?></label></td>
        <td class="field">
          <select class="select" id="setting_default_filter_id" name="setting[default_filter_id]">
<?php $current_default_filter_id = Setting::get('default_filter_id'); ?>
            <option value=""<?php if ($current_default_filter_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none'); ?> &#8212;</option>
<?php foreach (Filter::findAll() as $filter_id): ?>
            <option value="<?php echo $filter_id; ?>"<?php if ($filter_id == $current_default_filter_id) echo ' selected="selected"'; ?>><?php echo $filter_id; ?></option>
<?php endforeach; ?>
          </select>
        </td>
        <td class="help"><?php echo __('Only for filter in pages, NOT in snippets'); ?></td>
      </tr>
      <tr>
        <td colspan="3"><h3><?php echo __('Optional component'); ?></h3></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_enable_comment-yes"><?php echo __('Enable comments'); ?></label></td>
        <td class="field">
          <input class="radio" id="setting_enable_comment-yes" name="setting[enable_comment]" size="10" type="radio" value="1"<?php if (Setting::get('enable_comment')) echo ' checked="checked"'; ?> /><label for="setting_enable_comment-yes"> <?php echo __('yes'); ?> </label> &nbsp; 
          <input class="radio" id="setting_enable_comment-no" name="setting[enable_comment]" size="10" type="radio" value="0"<?php if ( ! Setting::get('enable_comment')) echo ' checked="checked"'; ?> /><label for="setting_enable_comment-no"> <?php echo __('no'); ?> </label>
        </td>
        <td class="help">&nbsp;</td>
      </tr>
      <tr>
        <td class="label"><label for="setting_auto_approve_comment-yes"><?php echo __('Auto approve comments'); ?></label></td>
        <td class="field">
          <input class="radio" id="setting_auto_approve_comment-yes" name="setting[auto_approve_comment]" size="10" type="radio" value="1"<?php if (Setting::get('auto_approve_comment')) echo ' checked="checked"'; ?> /><label for="setting_auto_approve_comment-yes"> <?php echo __('yes'); ?> </label> &nbsp; 
          <input class="radio" id="setting_auto_approve_comment-no" name="setting[auto_approve_comment]" size="10" type="radio" value="0"<?php if ( ! Setting::get('auto_approve_comment')) echo ' checked="checked"'; ?> /><label for="setting_auto_approve_comment-no"> <?php echo __('no'); ?> </label>
        </td>
        <td class="help">&nbsp;</td>
      </tr>
  </table>

  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
  </p>

</form>

<script type="text/javascript">
// <![CDATA[
Field.activate('user_name');
// ]]>
</script>

        </div>
    </div>
</div>
</div>
<script type="text/javascript">
// <![CDATA[
  var tabControlMeta = new TabControl('tab-control-admin');
  tabControlMeta.addTab('tab-plugin', '<?php echo __('Plugins'); ?>', 'plugin-page');
  tabControlMeta.addTab('tab-setting', '<?php echo __('Settings'); ?>', 'setting-page');
  tabControlMeta.select(tabControlMeta.firstTab());
// ]]>
</script>