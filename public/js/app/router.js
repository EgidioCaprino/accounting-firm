app.config(function($routeProvider) {
    var basePath = "/js/app/template/";

    $routeProvider.when("/user/create", {
        controller: "CreateUserController",
        templateUrl: basePath + "create-user.html"
    }).when("/user/:id_user/folder/:id_folder", {
        controller: "FolderController",
        templateUrl: basePath + "/folder.html"
    }).when('/public-folder', {
        controller: 'PublicFolderController',
        templateUrl: basePath + '/public-folder.html'
    }).when('/public-folder/:id_folder', {
        controller: 'PublicFolderFileController',
        templateUrl: basePath + '/public-folder-file.html'
    }).when('/welcome', {
        controller: 'WelcomeController',
        templateUrl: basePath + '/welcome.html'
    }).when('/user/:id_user', {
        controller: 'UpdateUserController',
        templateUrl: basePath + '/update-user.html'
    }).otherwise({
        redirectTo: "/welcome"
    });
});