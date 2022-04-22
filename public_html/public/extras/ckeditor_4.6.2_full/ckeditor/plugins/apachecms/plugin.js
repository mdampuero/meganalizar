CKEDITOR.plugins.add('apachecms',
{
    init: function (editor) {
        var pluginName = 'apachecms';
        editor.ui.addButton('Apachecms-picture',
            {
                label: 'Insertar Imagen',
                command: 'openModal',
                icon: CKEDITOR.plugins.getPath('apachecms') + 'apachecms-icon.png'
            });
        var cmd = editor.addCommand('openModal', { exec: apachecmsPictureEvent });
    }
});
