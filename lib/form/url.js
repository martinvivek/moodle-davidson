M.form_url = {};

M.form_url.init = function(Y, options) {
    options.formcallback = M.form_url.callback;
    if (!M.core_filepicker.instances[options.client_id]) {
        M.core_filepicker.init(Y, options);
    }
    Y.on('click', function(e, client_id) {
        e.preventDefault();
        M.core_filepicker.instances[client_id].show();
    }, '#filepicker-button-js-'+options.client_id, null, options.client_id);
};

M.form_url.callback = function (params) {
    // Good for only one URL button per Form page. (MDL-52318)
    //document.getElementById('id_externalurl').value = params.url;

    // Support for multiple URL input buttons on the same Form page.
    document.getElementById(Y.one('#filepicker-button-'
        + params.client_id).siblings('input[type=text]').item(0).getAttribute('id')).value = params.url;
};
