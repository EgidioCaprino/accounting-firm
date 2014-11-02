app.run(function(User, $rootScope) {
    $rootScope.user = User.loggedUser();
});