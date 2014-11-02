app.controller('UpdateUserController', function($scope, $routeParams, User) {
    $scope.loading = false;
    $scope.oldUsername = null;
    $scope.user = User.get({id_user: $routeParams.id_user}, function() {
        $scope.user.password = null;
        $scope.oldUsername = $scope.user.username;
    });
    $scope.updateUser = function() {
        $scope.loading = true;
        $scope.user.$update(function() {
            $scope.oldUsername = $scope.user.username;
            window.updateList();
            alert('Utente aggiornato con successo.');
            $scope.user.password = null;
            $scope.loading = false;
        }, function() {
            alert("Attenzione! Si è verificato un errore durante la creazione dell'utente. Verificare che il nome utente e l'indirizzo email non siano già registrati.");
            $scope.loading = false;
        });
    };
});