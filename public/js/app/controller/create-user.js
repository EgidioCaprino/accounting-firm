app.controller("CreateUserController", function($scope, $controller, User) {
    $scope.user = new User({password: chance.word({length: 10})});
    $scope.loading = false;

    $scope.createUser = function() {
        $scope.loading = true;
        $scope.user.$save(function() {
            window.updateList();
            alert('Utente creato con successo.');
            $scope.user = new User({password: chance.word({length: 10})});
            $scope.loading = false;
        }, function() {
            alert("Attenzione! Si è verificato un errore durante la creazione dell'utente. Verificare che il nome utente e l'indirizzo email non siano già registrati.");
            $scope.loading = false;
        });
    };
});