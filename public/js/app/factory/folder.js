app.factory("Folder", function($resource) {
    return $resource("/rest/user/:id_user/folder/:id_folder", {
        id_user: "@id_user",
        id_folder: "@id_folder"
    }, {
        update: {
            method: "PUT"
        }
    });
});