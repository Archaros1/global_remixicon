(function ($, Drupal, drupalSettings) {
  var classes = drupalSettings['global_remixicon']['remixIconList'];
  var dsfrPath = drupalSettings['global_remixicon']['dsfrPath'];
  var modulePath = drupalSettings['global_remixicon']['modulePath'];
  var ckeditorPath = drupalSettings['global_remixicon']['ckeditorPath'];
  var icons = [];
  Object.keys(classes).forEach(element => {
    icons.push(element);
  });
  CKEDITOR.plugins.add('remix_icon', {
    init: function (editor) {
      editor.contextMenu.addListener(function (element) {
        editor.addMenuItems({
          remix_icon: {
            label: Drupal.t('Remix Icon'),
            command: 'remix_icon',
            group: 'remix_icon',
            order: 1
          }
        });
      });

      editor.config.allowedContent = true;
      editor.ui.addRichCombo('remix_icon', {
        label: "Remix Icon",
        title: "Remix Icon",
        voiceLabel: "Remix Icon",
        multiSelect: false,

        panel: {
          css: [
            '/' + dsfrPath + '/assets/dsfr/dsfr.min.css',
            '/' + dsfrPath + '/assets/dsfr/utility/utility.main.min.css',
            '/' + modulePath + '/css/richCombo.css',
            '/' + ckeditorPath + '/vendor/skins/moono-lisa/editor_gecko.css?t=rtgieh'
          ]
        },

        init: function () {
          for (var iconId in icons) {
            var cssClass = classes[icons[iconId]];
            this.add(cssClass, '<span class="' + cssClass + '" aria-hidden="true"></span>');
          }
        },

        onClick: function (value) {
          editor.focus();
          editor.fire('saveSnapshot');
          editor.insertHtml('<span class="' + value + '" aria-hidden="true"></span>');
          editor.fire('saveSnapshot');
        }
      });
    },
  });

  if ('editor' in drupalSettings && 'global_remixicon' in drupalSettings.editor) {
    $.each(drupalSettings.editor.global_remixicon.allowedEmptyTags, function (_, tag) {
      CKEDITOR.dtd.$removeEmpty[tag] = 0;
    });
  }

})(jQuery, Drupal, drupalSettings);
