(function() {
	tinymce.PluginManager.add('jeba_tab_button', function( editor, url ) {
		editor.addButton('jeba_tab_button', {
			text: 'Jeba',
			icon: false,
			onclick: function() {
				editor.insertContent('[jeba_tab post_type="jeba-tab-items" category=""]');
			}
		});
	});
})();