/**
 * @author Ryan Johnson <ryan@livepipe.net>
 * @copyright 2007 LivePipe LLC
 * @package Control.TextArea.ToolBar.Textile
 * @license MIT
 * @url http://livepipe.net/projects/control_textarea/textile
 * @version 1.0.0
 */

Control.TextArea.ToolBar.Default = Class.create();
Object.extend(Control.TextArea.ToolBar.Default.prototype,{
	textarea: false,
	toolbar: false,
	options: {},
	initialize: function(textarea,options){
		this.textarea = new Control.TextArea(textarea);
		this.toolbar = new Control.TextArea.ToolBar(this.textarea);
		this.toolbar.container.className ='filter_toolbar';
		this.options = {
			preview: false,
			afterPreview: Prototype.emptyFunction
		};
		Object.extend(this.options,options || {});
		if(this.options.preview){
			this.textarea.observe('change',function(textarea){
				$(this.options.preview).update(textarea.getValue());
				this.options.afterPreview();
			}.bind(this));
		}
		
		//buttons
		this.toolbar.addButton('Bold',function(){
			this.wrapSelection('<strong>','</strong>');
		},{
			id: 'filter_bold_button'
		});
		
		this.toolbar.addButton('Italic',function(){
			this.wrapSelection('<em>','</em>');
		},{
			id: 'filter_italic_button'
		});
		
		this.toolbar.addButton('Ordered List',function(event){
			this.injectEachSelectedLine(function(lines,line){
				lines.push('\t<li>' + line + '</li>');
				return lines;
			});
			this.wrapSelection('<ol>\n','\n</ol>');
		},{
			id: 'filter_ordered_list_button'
		});
		
		this.toolbar.addButton('Unordered List',function(event){
			this.injectEachSelectedLine(function(lines,line){
				lines.push('\t<li>' + line + '</li>');
				return lines;
			});
			this.wrapSelection('<ul>\n','\n</ul>');
		},{
			id: 'filter_unordered_list_button'
		});
		
		this.toolbar.addButton('Paragraph',function(){
			this.wrapSelection('<p>','</p>');
		},{
			id: 'filter_paragraph_button'
		});
		
		this.toolbar.addButton('Heading 1',function(){
			this.wrapSelection('<h1>','</h1>');
		},{
			id: 'filter_h1_button'
		});
		
		this.toolbar.addButton('Heading 2',function(){
			this.wrapSelection('<h2>','</h2>');
		},{
			id: 'filter_h2_button'
		});
		
		this.toolbar.addButton('Heading 3',function(){
			this.wrapSelection('<h3>','</h3>');
		},{
			id: 'filter_h3_button'
		});
		
		this.toolbar.addButton('Heading 4',function(){
			this.wrapSelection('<h4>','</h4>');
		},{
			id: 'filter_h4_button'
		});
		
		this.toolbar.addButton('Block Quote',function(){
			this.wrapSelection('<blockquote>','</blockquote>');
		},{
			id: 'filter_quote_button'
		});
		
		this.toolbar.addButton('Link',function(){
			selection = this.getSelection();
			response = prompt('Enter Link URL','');
			if(response == null)
				return;
			this.replaceSelection('<a href="' + (response == '' ? 'http://link_url/' : response).replace(/^(?!(f|ht)tps?:\/\/)/,'http://') + '" title="' + (selection == '' ? 'Link Text' : selection) + '">' + (selection == '' ? 'Link Text' : selection) + '</a>');
		},{
			id: 'filter_link_button'
		});
		
		this.toolbar.addButton('Image',function(){
			selection = this.getSelection();
			this.replaceSelection('<img src="' + (selection == '' ? 'image_url' : selection) + '" alt="alt text" />');
		},{
			id: 'filter_image_button'
		});
		
		this.toolbar.addButton('Left Justify',function(){
			this.injectEachSelectedLine(function(lines,line){
				if (line.match(/<(p|h[1-6]|bq)([^>]*)>/))
					lines.push(line.replace(/<(p|h[1-6]|bq)([^>]*)>/, '<$1>'));
				else if (line.match(/^\s*$/)) // safari bug
					lines.push(line);
				else
					lines.push('<p>' + line + '</p>');
				return lines;
			});
		},{
			id: 'filter_left_justify_button'
		});
		
		this.toolbar.addButton('Center Text',function(){
			this.injectEachSelectedLine(function(lines,line){
				if (line.match(/<(p|h[1-6]|bq)([^>]*)>/))
					lines.push(line.replace(/<(p|h[1-6]|bq)([^>]*)>/, '<$1 style="text-align: center">'));
				else if (line.match(/^\s*$/)) // safari bug
					lines.push(line);
				else
					lines.push('<p style="text-align: center">' + line + '</p>');
				return lines;
			});
		},{
			id: 'filter_center_text_button'
		});
		
		this.toolbar.addButton('Right Justify',function(){
			this.injectEachSelectedLine(function(lines,line){
				if (line.match(/<(p|h[1-6]|bq)([^>]*)>/))
					lines.push(line.replace(/<(p|h[1-6]|bq)([^>]*)>/, '<$1 style="text-align: right">'));
				else if (line.match(/^\s*$/)) // safari bug
					lines.push(line);
				else
					lines.push('<p style="text-align: right">' + line + '</p>');
				return lines;
			});
		},{
			id: 'filter_right_justify_button'
		});
		
		this.toolbar.addButton('Justify',function(){
			this.injectEachSelectedLine(function(lines,line){
				if (line.match(/<(p|h[1-6]|bq)([^>]*)>/))
					lines.push(line.replace(/<(p|h[1-6]|bq)([^>]*)>/, '<$1 style="text-align: justify">'));
				else if (line.match(/^\s*$/)) // safari bug
					lines.push(line);
				else
					lines.push('<p style="text-align: justify">' + line + '</p>');
				return lines;
			});
		},{
			id: 'filter_justify_button'
		});

	}
});