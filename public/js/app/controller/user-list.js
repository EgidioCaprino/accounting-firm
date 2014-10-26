app.controller("UserListController", function($scope, User) {
    $scope.users = User.query();
});