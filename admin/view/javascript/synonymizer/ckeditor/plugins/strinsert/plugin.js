/**
 * @license Copyright © 2013 Stuart Sillitoe <stuart@vericode.co.uk>
 * This work is mine, and yours. You can modify it as you wish.
 *
 * Stuart Sillitoe
 * stuartsillitoe.co.uk
 *
 */

CKEDITOR.plugins.add('strinsert',
{
	requires : ['richcombo'],
	init : function( editor )
	{

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}
	var someSessionVariable = getCookie ( "token" );
//var strings = [];
var data = CKEDITOR.ajax.load( 'index.php?route=module/synonymizer/test&token=' + someSessionVariable );
var strings = JSON.parse(data);
		//  array of strings to choose from that'll be inserted into the editor
/*
		var strings = [];
		strings.push(['@@FAQ::displayList()@@', 'FAQs', 'FAQs']);
		strings.push(['@@Glossary::displayList()@@', 'Glossary', 'Glossary']);
		strings.push(['@@CareerCourse::displayList()@@', 'Career Courses', 'Career Courses']);
		strings.push(['@@CareerProfile::displayList()@@', 'Career Profiles', 'Career Profiles']);
*/
		// add the menu to the editor
		editor.ui.addRichCombo('strinsert',
		{
			label: 		'Атрибуты',
			title: 		'Атрибуты товаров',
			voiceLabel: 'Product Attributes',
			className: 	'cke_format',
			multiSelect:false,
			panel:
			{
				css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function()
			{
				this.startGroup( "Product Attributes" );
				for (var i in strings)
				{
					this.add('['+strings[i]['g_name']+'__'+strings[i]['a_name']+']', strings[i]['g_name']+' > '+strings[i]['a_name']);
				}
			},

			onClick: function( value )
			{
				editor.focus();
				editor.fire( 'saveSnapshot' );
				editor.insertHtml(value);
				editor.fire( 'saveSnapshot' );
			}
		});
	}
});