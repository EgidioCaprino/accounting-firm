app.factory('File', function($resource) {
    return $resource('/rest/user/:id_user/folder/:id_folder/file/:id_file', {
        'id_user': '@id_user',
        'id_folder': '@id_folder',
        'id_file': '@id_file'
    }, {
        update: {
            method: 'PUT'
        }
    });
});