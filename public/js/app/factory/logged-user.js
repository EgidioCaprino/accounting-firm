app.factory("LoggedUser", function(User) {
    return User.loggedUser();
});