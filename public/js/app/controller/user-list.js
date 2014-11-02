app.controller("UserListController", function($scope, $timeout, User) {
    $scope.users = [];
    $scope.loggedUser = User.loggedUser();
    window.updateList = function () {
        $scope.users = User.query();
        $timeout(function() {
            $scope.$apply();
        });
    };
    window.updateList();
    $scope.deleteUser = function(user) {
        if (confirm('Sei sicuro di voler eliminare questo utente? Verranno cancellati tutte le cartelle e i file associati.')) {
            user.$delete(function() {
                var index = $scope.users.indexOf(user);
                if (index >= 0) {
                    $scope.users.splice(index, 1);
                }
            });
        }
    };
});