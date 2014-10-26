app.factory("User", function($resource) {
    return $resource("/rest/user/:id_user", {
        id_user: "@id_user"
    }, {
        update: {
            method: "PUT"
        },
        loggedUser: {
            url: "/rest/user/logged-user"
        }
    });
});