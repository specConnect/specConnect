/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.toolbar = 'Custom';
 
	config.toolbar_Custom =
	[
		{ name: 'document', items : [ 'Preview' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
        { name: 'styles', items : ['FontSize'] },
            '/',
		{ name: 'insert', items : [ 'Table','HorizontalRule','Smiley','SpecialChar'] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'tools', items : [ 'Maximize'] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
	];
};
CKEDITOR.on('instanceReady', function(ev){
        var tags = ['p', 'ol', 'ul', 'li', 'img', 'a', 'br', 'hr', 'strong', 'em', 'table', 'tr', 'td', 'blockquote', 'tbody','iframe']; // etc.

        for (var key in tags) {
            ev.editor.dataProcessor.writer.setRules(tags[key],
                {
                    indent : false,
                    breakBeforeOpen : true,
                    breakAfterOpen : false,
                    breakBeforeClose : false,
                    breakAfterClose : true
                });
        }
});