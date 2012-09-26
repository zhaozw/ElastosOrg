/**
 * $Id: editor_plugin_src.js 126 2006-10-22 16:19:55Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2006, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('advcolor', 'en');

var TinyMCE_AdvColorPlugin = {
	getInfo : function() {
		return {
			longname : 'Photoshop style color chooser',
			author : 'Joakim Calais',
			authorurl : 'http://www.locotech.fi',
			infourl : 'http://tinymce.moxiecode.com/punbb/viewtopic.php?id=4933',
			version : "1.0"
		};
	},

	/**
	 * Returns the HTML contents of the insertdate, inserttime controls.
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "advcolor":
				return tinyMCE.getButtonHTML(cn, 'lang_advcolor_desc', '{$pluginurl}/images/colorpicker.gif', 'mceAdvColor');
		}

		return "";
	},

	/**
	 * Executes the mceEmotion command.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mceAdvColor":
				var template = new Array();

				template['file'] = '../../plugins/advcolor/popup.htm'; // Relative to theme
				template['width'] = 450;
				template['height'] = 415;

				tinyMCE.openWindow(template, {editor_id : editor_id});
				
				// Let TinyMCE know that something was modified
				tinyMCE.triggerNodeChange(false);

				// Pass to next handler in chain
				return true;
				
			case "putForeColor":
				tinyMCE.execCommand("forecolor",false,value);
				return true;
				
			case "putBgColor":
				tinyMCE.execCommand("HiliteColor",false,value);
				return true;
		}
	}


};

tinyMCE.addPlugin("advcolor", TinyMCE_AdvColorPlugin);
