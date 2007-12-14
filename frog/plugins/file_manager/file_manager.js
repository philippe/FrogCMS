function toggle_chmod_popup(filename)
{
	var popup = $('chmod-popup');
	var file_mode = $('chmod_file_mode');
	$('chmod_file_name').value = filename;
	center(popup);
	Element.toggle(popup);
	Field.focus(file_mode);
}
function toggle_rename_popup(file, filename)
{
	var popup = $('rename-popup');
	var file_mode = $('rename_file_new_name');
	$('rename_file_current_name').value = file;
	file_mode.value = filename;
	center(popup);
	Element.toggle(popup);
	Field.focus(file_mode);
}