(function ($, Drupal, drupalSettings) {

  'use strict';
  var classes = drupalSettings['global_remixicon']['remixIconList'];

  CKEDITOR.dialog.add('RemixIconDialog', function (editor) {
    var icons = [];
    Object.keys(classes).forEach(element => {
      icons.push([element]);
    });
    return {
      title: 'Icône',
      id: 'RemixIconDialog',
      minWidth: 500,
      minHeight: 200,
      resizable: false,
      contents: [
        {
          id: 'Icône',
          label: 'Icône',
          title: 'Icône',
          elements: [
            {
              type: 'vbox',
              children: [
                {
                  type: 'select',
                  label: 'Icône',
                  id: 'type',
                  items: icons,
                  default: icons[0],
                  className: 'custom-form-required',
                },
              ]
            },
          ],
        },
      ],
      onOk: function () {
        editor.insertElement(CKEDITOR.dom.element.createFromHtml(getIconClass()));
      },
      onShow: function () {
      }
    }
  });

  function getIconClass() {
    let dialog = CKEDITOR.dialog.getCurrent();
    var type = dialog.getContentElement('Icône', 'type').getValue();

    var baliseHtml = '<span class="' + classes[type] + '" aria-hidden="true"></span>';
    return baliseHtml;
  }

})(jQuery, Drupal, drupalSettings);
