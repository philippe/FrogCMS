<?php if (Dispatcher::getAction() != 'view'): ?>

<p class="button"><a href="#" onclick="toggle_popup('create-file-popup', 'create_file_name'); return false;"><img src="images/page.png" align="middle" alt="page icon" /> <?php echo __('Create new file'); ?></a></p>
<p class="button"><a href="#" onclick="toggle_popup('create-directory-popup', 'create_directory_name'); return false;"><img src="images/dir.png" align="middle" alt="dir icon" /> <?php echo __('Create new directory'); ?></a></p>
<p class="button"><a href="#" onclick="toggle_popup('upload-file-popup', 'upload_file'); return false;"><img src="images/upload.png" align="middle" alt="upload icon" /><?php echo __('Upload file'); ?></a></p>

<?php endif; ?>