/* String.js ---------------------------------------------------------------*/

Object.extend(String.prototype,
{
	upcase: function()
	{
		return this.toUpperCase();
	},
	downcase: function()
	{
		return this.toLowerCase();
	},
	strip: function()
	{
		return this.replace(/^\s+/, '').replace(/\s+$/, '');
	},	
	toInteger: function()
	{
		return parseInt(this);
	},
	toSlug: function()
	{
		return this.strip().downcase().replace(/[^-a-z0-9~\s\.:;+=_]/g, '').replace(/[\s\.:;=+]+/g, '_');
	}
});


/* Pages.js ----------------------------------------------------------------*/

function part_added()
{
	var partNameField = $('part-name-field');
	var partIndexField = $('part-index-field');
	var index = parseInt(partIndexField.value);
	var tab = 'tab-' + index;
	var caption = partNameField.value;
	var page = 'page-' + index;
	tabControl.addTab(tab, caption, page);
	Element.hide('add-part-popup');
	//Element.hide('busy');
	partNameField.value = '';
	partIndexField.value = (index + 1).toString();
	$('add-part-button').disabled = false;
	Field.focus(partNameField);
	tabControl.select(tab);
}
function part_loading()
{
	$('add-part-button').disabled = true;
	//new Effect.Appear('busy');
}
function valid_part_name()
{
	var partNameField = $('part-name-field');
	var name = partNameField.value.downcase().strip();
	var result = true;
	if (name == '')
	{
		alert('Part name cannot be empty.');
		return false;
	}
	tabControl.tabs.each(function(pair)
	{
		if (tabControl.tabs[pair.key].caption == name)
		{
			result = false;
			alert('Part name must be unique.');
			throw $break;
		}
	});
	return result;
}
function center(element)
{
	var header = $('header')
	element = $(element);
	element.style.position = 'fixed'
	var dim = Element.getDimensions(element)
	element.style.top = '200px';
	element.style.left = ((header.offsetWidth - dim.width) / 2) + 'px';
}
function toggle_add_part_popup()
{
	var popup = $('add-part-popup');
	var partNameField = $('part-name-field');
	center(popup);
	Element.toggle(popup);
	Field.focus(partNameField);
}
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
function toggle_popup(id, focus_field)
{
	var popup = $(id);
	focus_field = $(focus_field);
	center(popup);
	Element.toggle(popup);
	Field.focus(focus_field);
}
function allowTab(event, obj)
{
	var keyCode = event.which ? event.which: event.keyCode;
	
	// 9 is the tab key code
	if (keyCode == 9)
	{
		if (event.type == "keydown")
		{
			if (obj.setSelectionRange) // mozilla, safari
			{
				var content = '\t';
				var start = obj.selectionStart;
				var end = obj.selectionEnd;
			
				// with selection
				if (end - start > 1)
				{
					content += obj.value.substring(start, end);
					content = content.replace(/\n/g, '\n\t');
				}
			
				obj.value = obj.value.substring(0, start) + content + obj.value.substr(end);
				obj.setSelectionRange(start + 1, start + 1);
				obj.focus();
			}
			else if (obj.createTextRange) // ie
			{
				// sorry selection tab doesn't work because I can develop
				// for internet explorer. if you want me do to it, buy me a windows
				// license and I will do it for you!
				// here is my site to contact me: www.philworks.com
				document.selection.createRange().text = '\t';
				obj.onblur = function() { this.focus(); this.onblur = null; };
			}
			// else unsupported browsers
		}
		
		if (event.returnValue) // ie
			event.returnValue = false;
			
		if (event.preventDefault) // dom
			event.preventDefault();

		return false; // should work in all browsers
	}
	return true;
}

function setTextAreaToolbar(textarea, filter)
{
	if (filter == '')
		filter = 'Default';
	
	var toolbar_name = textarea + '_toolbar';
	
	// make sure the textarea is display 
	//(maybe some filter will choose to use a iframe like tinycme)
	$(textarea).style.display = 'block';
	
	var ul_toolbar = document.getElementById(toolbar_name);
	if (ul_toolbar != null)
		ul_toolbar.parentNode.removeChild(ul_toolbar);
	
	if (Control.TextArea.ToolBar[filter] != null)
		var tb = new Control.TextArea.ToolBar[filter](textarea);
	else
		var tb = new Control.TextArea.ToolBar.Default(textarea);
	
	tb.toolbar.container.id = toolbar_name;
}


/* RuledTable.js -----------------------------------------------------------*/

var RuledTable = Class.create();
RuledTable.prototype = {
	
	initialize: function(element_id)
	{
		var table = $(element_id);
		var rows = table.getElementsByTagName('TR');
		
		for (var i = 0; i < rows.length; i++)
			this.setupRow(rows[i]);
	},
	
	setupRow: function(row)
	{
		Event.observe(row, 'mouseover', this.onMouseOverRow.bindAsEventListener(row));
		Event.observe(row, 'mouseout', this.onMouseOutRow.bindAsEventListener(row));
		if (this.onRowSetup) this.onRowSetup(row);
	},
	
	onMouseOverRow: function(event)
	{
		this.className = this.className.replace(/\s*\bhighlight\b|$/, ' highlight');
	},
	
	onMouseOutRow: function(event)
	{
		this.className = this.className.replace(/\s*\bhighlight\b\s*/, ' ');
	}

};


/* RuledList.js ------------------------------------------------------------*/

var RuledList = Class.create();
RuledList.prototype = {
	
	initialize: function(element_id)
	{
		this.setupRows(element_id);
	},
	
	setupRows: function(element_id)
	{
		var list = $(element_id);
		this.setupRow(list);
		var rows = list.getElementsByTagName('LI');
		
		for (var i = 0; i < rows.length; i++)
			this.setupRow(rows[i]);
	},
	
	setupRow: function(row)
	{
		Event.observe(row, 'mouseover', this.onMouseOverRow.bindAsEventListener(row));
		Event.observe(row, 'mouseout', this.onMouseOutRow.bindAsEventListener(row));
		if (this.onRowSetup) this.onRowSetup(row);
	},
	
	onMouseOverRow: function(event)
	{
		this.className = this.className.replace(/\s*\bhighlight\b|$/, ' highlight');
	},
	
	onMouseOutRow: function(event)
	{
		this.className = this.className.replace(/\s*\bhighlight\b\s*/, ' ');
	}

};


/* Sitemap.js --------------------------------------------------------------*/

var SiteMap = Class.create();
SiteMap.prototype = Object.extend({}, RuledList.prototype);

Object.extend(SiteMap.prototype,
{
	ruledListInitialize: RuledList.prototype.initialize,
	
	initialize: function(id, expanded)
	{
		this.id = id;
		this.ruledListInitialize(id);
		this.expandedRows = expanded;
		this.sortablize();
	},
	
	sortablize: function()
	{
		Sortable.destroy(this.id);
		Sortable.create(this.id,
		{ 
			constraint:'vertical', scroll:window, handle:'handle', tree:true,
			onChange: SiteMap.prototype.adjustLevelOf,
			onUpdate: SiteMap.prototype.update
		});
	},
	
	onRowSetup: function(row)
	{
		var toggler = row.getElementsByTagName('IMG')[0];
		Event.observe(toggler, 'click', this.onMouseClickRow.bindAsEventListener(this), false);
	},
	
	onMouseClickRow: function(event)
	{
		var element = Event.element(event);
		if (this.isExpander(element))
		{
			var row = Event.findElement(event, 'LI');
			if (this.hasChildren(row))
				this.toggleBranch(row, element);
		}
	},
	
	hasChildren: function(row)
	{
		return ! /\bno-children\b/.test(row.className);
	},
	
	isExpander: function(element)
	{
		return (element.tagName.strip().downcase() == 'img') && /\bexpander\b/i.test(element.className);
	},
	
	isExpanded: function(row)
	{
		return /\bchildren-visible\b/i.test(row.className);
	},
	
	isRow: function(element)
	{
		return element.tagName && (element.tagName.strip() == 'LI');
	},
	
	extractLevel: function(row)
	{
		if (/level-(\d+)/i.test(row.className))
			return RegExp.$1.toInteger();
	},
	
	extractPageId: function(row)
	{
		if (/page_(\d+)/i.test(row.id))
			return RegExp.$1.toInteger();
	},
	
	getExpanderImageForRow: function(row)
	{
		var images = $A(row.getElementsByTagName('IMG', row));
		var expanders = [];
		
		images.each(function(image)
		{
			expanders.push(image);
		}.bind(this));
		
		return expanders.first();
	},
	
	saveExpandedCookie: function()
	{
		document.cookie = "expanded_rows=" + this.expandedRows.uniq().join(",");
	}, 
	
	hideBranch: function(row, img)
	{
		var level = this.extractLevel(row);
		
		for (var i = row.childNodes.length-1; i>=0; i--)
		{
			if (row.childNodes[i].nodeName == 'UL')
			{
				Element.hide(row.childNodes[i]);
				break;
			}
		}

		var pageId = this.extractPageId(row);
		var newExpanded = [];
		
		for (i = 0; i < this.expandedRows.length; i++)
		{
			if (this.expandedRows[i] != pageId)
				newExpanded.push(this.expandedRows[i]);
		}
		
		this.expandedRows = newExpanded;
		this.saveExpandedCookie();
		
		if (img == null)
			img = this.getExpanderImageForRow(row);
		
		img.src = img.src.replace(/collapse/, 'expand');
		row.className = row.className.replace(/children-visible/, 'children-hidden');
	},
	
	showBranchInternal: function(row, img)
	{
		var level = this.extractLevel(row);
		var children = false;

		for (var i=row.childNodes.length-1; i>=0; i--)
		{
				if (row.childNodes[i].nodeName == 'UL')
				{
						Element.show(row.childNodes[i]);
						children = true;
						break;
				}
		}

		if ( ! children)
			this.getBranch(row);

		if (img == null)
			img = this.getExpanderImageForRow(row);

		img.src = img.src.replace(/expand/, 'collapse');
		row.className = row.className.replace(/children-hidden/, 'children-visible');
	},
	
	showBranch: function(row, img)
	{
		this.showBranchInternal(row, img);
		this.expandedRows.push(this.extractPageId(row));
		this.saveExpandedCookie();
	},
	
	getBranch: function(row)
	{
		var level = this.extractLevel(row).toString();
		var id = this.extractPageId(row).toString();
		new Ajax.Updater(
			row,
			'?/page/children/' + id + '/' + level,
			{
				evalScripts: true,
				asynchronous: true,
				insertion: Insertion.EndOfRow,
				onLoading: function(request)
				{
					Element.show('busy-' + id);
					this.updating = true;
				}.bind(this),
				onComplete: function(request)
				{
					Effect.Fade('busy-' + id);
					this.setupRows(row);
					this.sortablize();
					this.updating = false;
				}.bind(this)
			}
		);
	},
	
	toggleBranch: function(row, img)
	{
		if (! this.updating)
		{
			if (this.isExpanded(row))
				this.hideBranch(row, img);
			else
				this.showBranch(row, img);
		}
	},
	
	adjustLevelOf: function(element)
	{
		// this will make the page displayed at the level + 1 of the parent
		var currentLevel = 1;
		var parentLevel = 0;
		currentElementSelected = element;
		
		if (/level-(\d+)/i.test(element.className))
			currentLevel = RegExp.$1.toInteger();
			
		if (/level-(\d+)/i.test(element.parentNode.parentNode.className))
			parentLevel = RegExp.$1.toInteger();

		if (currentLevel != parentLevel+1)
		{
			Element.removeClassName(element, 'level-'+currentLevel);
			Element.addClassName(element, 'level-'+(parentLevel+1));
		}
		// this will update all childs level
		var container = Element.findChildren(element, false, false, 'UL');
		if (container.length == 1)
		{
			var childs = Element.findChildren(container[0], false, false, 'LI');
			for (var i=0; i < childs.length; i++)
				childs[i].className = childs[i].className.replace(/level-(\d+)/, 'level-'+(parentLevel+2));
		}
	},
	
	update: function()
	{
		var parent = currentElementSelected.parentNode;
		var parent_id = 1;
		var pages = [];
		var data = '';
		
		if (/page_(\d+)/i.test(currentElementSelected.parentNode.parentNode.id))
			parent_id = RegExp.$1.toInteger();
		
		pages = Element.findChildren(parent, false, false, 'LI');
		
		for(var i=0; i<pages.length; i++)
			data += 'pages[]='+SiteMap.prototype.extractPageId(pages[i])+'&';
		
		new Ajax.Request('?/page/reorder/'+parent_id, {method: 'post', parameters: { 'data': data }});
	}
});

Insertion.EndOfRow = Class.create();
Insertion.EndOfRow.prototype = {
	initialize: function(element, content)
	{
		this.element = $(element);
		this.content = content.stripScripts();

		this.element.innerHTML += this.content;

		setTimeout(function() {content.evalScripts();}, 10);
	}
};


/* TabControl.js -----------------------------------------------------------*/

var TabControl = Class.create();

TabControl.controls = $H();
TabControl.BadTabError = new Error('TabControl: Invalid tab.');

TabControl.prototype = {
	/*
	  Initializes a tab control. The variable +element_id+ must be the id of an HTML element
	  containing one element with it's class name set to 'tabs' and another element with it's
	  class name set to 'pages'.
	*/
	initialize: function(element_id)
	{
		TabControl.controls[element_id] = this;
		this.control_id = element_id;
		this.element = $(element_id);
		this.tab_container = document.getElementsByClassName('tabs', this.element).first();
		this.tabs = $H();
	},
	
	/*
	  Creates a new tab. The variable +tab_id+ is a unique string used to identify the tab
	  when calling other methods. The variable +caption+ is a string containing the caption
	  of the tab. The variable +page+ is the ID of an HTML element, or the HTML element
	  itself. When a tab is initially added the page element is hidden.
	*/
	addTab: function(tab_id, caption, page)
	{
		new Insertion.Bottom (
			this.tab_container,
			'<a class="tab" href="javascript:TabControl.controls[\''
			+ this.control_id
			+ '\'].select(\'' + tab_id + '\');">' + caption + '</a>'
		);
		var tab = this.tab_container.lastChild;
		tab.tab_id = tab_id;
		tab.caption = caption;
		tab.page = $(page);
		this.tabs[tab_id] = tab;
		this._setNotHere(tab);
		return tab;
	},
	
	/*
	  Removes +tab+. The variable +tab+ may be either a tab ID or a tab element.
	*/
	removeTab: function(tab)
	{
		var t = this._tabify(tab);
		var id = t.tab_id;
		Element.remove(t.page);
		Element.remove(t);
		new_tabs = $H();
		this.tabs.each(
			function(pair)
			{
				if (pair.key != id) new_tabs[pair.key] = pair.value;
			}
		);
		this.tabs = new_tabs;
		if (this.selected.tab_id == id)
		{
			var first = this.firstTab();
			if (typeof first != 'undefined') this.select(first.tab_id);
		}
	},

	/*
	  Selects +tab+ updating the control. The variable +tab+ may be either a tab ID or a
	  tab element.
	*/
	select: function(tab)
	{
		var t = this._tabify(tab);
		this.tabs.each(
			function(pair)
			{
				if (pair.key == t.tab_id)
				{
					if (this.selected) this.selected.selected = false;
					this.selected = t;
					t.selected = true;
					this._setHere(pair.key);
				}
				else
				{
					this._setNotHere(pair.key);
				}
			}.bind(this)
		);
		false;
	},

	firstTab: function()
	{
		return this.tabs[this.tabs.keys().first()];
	},
	
	lastTab: function()
	{
		return this.tabs[this.tabs.keys().last()];
	},

	tabCount: function()
	{
		return this.tabs.keys().length;
	},
	
	/*
	  Private Methods
	*/
	
	/*
	  Shows the page for +tab+ and adds the class 'here' to tab. The variable +tab+ may
	  be either a tab ID or a tab element.
	*/
	_setHere: function(tab)
	{
		var t = this._tabify(tab);
		Element.show(t.page);
		Element.addClassName(t, 'here');
	},
	
	/*
	  Hides the page for +tab+ and removes the class 'here' from tab. The variable +tab+
	  may be either a tab ID or a tab element.
	*/
	_setNotHere: function(tab)
	{
		var t = this._tabify(tab);
		Element.hide(t.page);
		Element.removeClassName(t, 'here');
	},

	/*
	  Returns a tab when passed a string or tab element. Throws a BadTabError otherwise.
	*/
	_tabify: function(something)
	{
		if (typeof something == 'string')
			var object = this.tabs[something];
		else
			var object = something;
		
		if ((typeof object != 'undefined') && (object.tab_id))
			return object;
		else
			throw TabControl.BadTabError;
	}
};