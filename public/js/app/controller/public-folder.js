app.controller('PublicFolderController', function($scope, Folder, User) {
    $scope.folders = Folder.getPublicFolders();
    $scope.user = User.loggedUser();
    $scope.createFolder = function() {
        var newFolder = new Folder();
        newFolder.name = prompt('Crea una nuova cartella', '');
        if (newFolder.name !== null && newFolder.name.trim().length > 0) {
            newFolder.public = true;
            newFolder.$save(function() {
                $scope.folders.push(newFolder);
            });
        }
    };
    $scope.deleteFolder = function(folder) {
        if (confirm('Sei sicuro di voler eliminare questa cartella? Tutti i file contenuti saranno eliminati e non sarà possibile recuperarli.')) {
            folder.$delete(function() {
                var index = $scope.folders.indexOf(folder);
                $scope.folders.splice(index, 1);
            });
        }
    };
});