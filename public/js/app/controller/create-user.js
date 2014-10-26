app.controller("CreateUserController", function($scope, User) {
    $scope.user = new User();
    $scope.loading = false;

    $scope.createUser = function() {
        //$scope.loading = true;
        $scope.user.$save(function() {
            $scope.loading = false;
        });
    };
});