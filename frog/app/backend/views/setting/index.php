<h1><?php echo __('Settings') ?></h1>

<form action="<?php echo get_url('setting') ?>" method="post">
  <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td colspan="3"><h3><?php echo __('Site information') ?></h3></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_admin_title"><?php echo __('Admin Site title') ?></label></td>
        <td class="field"><input class="textbox" id="setting_admin_title" maxlength="40" name="setting[admin_title]" size="40" type="text" value="<?php echo Setting::get('admin_title') ?>" /></td>
        <td class="help"><?php echo __('Changes the title shown in backend.') ?></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_language"><?php echo __('Language') ?></label></td>
        <td class="field">
          <select class="select" id="setting_language" name="setting[language]">
<?php $current_language = Setting::get('language'); ?>
<?php foreach ($languages as $code => $label): ?>
            <option value="<?php echo $code ?>"<?php if ($code == $current_language) echo ' selected="selected"'; ?>><?php echo __($label) ?></option>
<?php endforeach; ?>
          </select>
        </td>
        <td class="help"><?php echo __('This will set your language for the backend.') ?></td>
      </tr>
      <tr>
        <td colspan="3"><h3><?php echo __('Page options') ?></h3></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_default_status_id-draft"><?php echo __('Default Status') ?></label></td>
        <td class="field">
          <input class="radio" id="setting_default_status_id-draft" name="setting[default_status_id]" size="10" type="radio" value="<?php echo Page::STATUS_DRAFT ?>"<?php if (Setting::get('default_status_id') == Page::STATUS_DRAFT) echo ' checked="checked"'; ?> /><label for="setting_default_status_id-draft"> <?php echo __('Draft') ?> </label> &nbsp; 
          <input class="radio" id="setting_default_status_id-published" name="setting[default_status_id]" size="10" type="radio" value="<?php echo Page::STATUS_PUBLISHED ?>"<?php if (Setting::get('default_status_id') == Page::STATUS_PUBLISHED) echo ' checked="checked"'; ?> /><label for="setting_default_status_id-published"> <?php echo __('Published') ?> </label>
        </td>
        <td class="help">&nbsp;</td>
      </tr>
      <tr>
        <td class="label"><label for="setting_default_filter_id"><?php echo __('Default Filter') ?></label></td>
        <td class="field">
          <select class="select" id="setting_default_filter_id" name="setting[default_filter_id]">
<?php $current_default_filter_id = Setting::get('default_filter_id'); ?>
            <option value=""<?php if ($current_default_filter_id == '') echo ' selected="selected"'; ?>>&#8212; <?php echo __('none') ?> &#8212;</option>
<?php foreach (Filter::findAll() as $filter_id): ?>
            <option value="<?php echo $filter_id ?>"<?php if ($filter_id == $current_default_filter_id) echo ' selected="selected"'; ?>><?php echo $filter_id ?></option>
<?php endforeach; ?>
          </select>
        </td>
        <td class="help"><?php echo __('Only for filter in pages, NOT in snippets') ?></td>
      </tr>
      <tr>
        <td colspan="3"><h3><?php echo __('Optional component') ?></h3></td>
      </tr>
      <tr>
        <td class="label"><label for="setting_enable_comment-yes"><?php echo __('Enable comments') ?></label></td>
        <td class="field">
          <input class="radio" id="setting_enable_comment-yes" name="setting[enable_comment]" size="10" type="radio" value="1"<?php if (Setting::get('enable_comment')) echo ' checked="checked"'; ?> /><label for="setting_enable_comment-yes"> <?php echo __('yes') ?> </label> &nbsp; 
          <input class="radio" id="setting_enable_comment-no" name="setting[enable_comment]" size="10" type="radio" value="0"<?php if ( ! Setting::get('enable_comment')) echo ' checked="checked"'; ?> /><label for="setting_enable_comment-no"> <?php echo __('no') ?> </label>
        </td>
        <td class="help">&nbsp;</td>
      </tr>
      <tr>
        <td class="label"><label for="setting_display_stats-yes"><?php echo __('Display stats') ?></label></td>
        <td class="field">
          <input class="radio" id="setting_display_stats-yes" name="setting[display_stats]" size="10" type="radio" value="1"<?php if (Setting::get('display_stats')) echo ' checked="checked"'; ?> /><label for="setting_display_stats-yes"> <?php echo __('yes') ?> </label> &nbsp; 
          <input class="radio" id="setting_display_stats-no" name="setting[display_stats]" size="10" type="radio" value="0"<?php if ( ! Setting::get('display_stats')) echo ' checked="checked"'; ?> /><label for="setting_display_stats-no"> <?php echo __('no') ?> </label>
        </td>
        <td class="help">&nbsp;</td>
      </tr>
      <tr>
        <td class="label"><label for="setting_display_file_manager-yes"><?php echo __('Display file manager') ?></label></td>
        <td class="field">
          <input class="radio" id="setting_display_file_manager-yes" name="setting[display_file_manager]" size="10" type="radio" value="1"<?php if (Setting::get('display_file_manager')) echo ' checked="checked"'; ?> /><label for="setting_display_file_manager-yes"> <?php echo __('yes') ?> </label> &nbsp; 
          <input class="radio" id="setting_display_file_manager-no" name="setting[display_file_manager]" size="10" type="radio" value="0"<?php if ( ! Setting::get('display_file_manager')) echo ' checked="checked"'; ?> /><label for="setting_display_file_manager-no"> <?php echo __('no') ?> </label>
        </td>
        <td class="help">&nbsp;</td>
      </tr>
  </table>

  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save') ?> (Alt+S)" />
  </p>

</form>

<script type="text/javascript">
// <![CDATA[
Field.activate('user_name');
// ]]>
</script>
