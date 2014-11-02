app.controller("FolderController", function($scope, $routeParams, Folder, File, FolderUtils, DateUtils, $upload) {
    $scope.DateUtils = DateUtils;
    $scope.folders = [];
    $scope.path = [];
    $scope.idUser = $routeParams.id_user;
    $scope.uploadingProgress = null;
    $scope.files = [];
    var folder = null;
    var $fileUpload = $('#fileUpload');
    Folder.query({id_user: $routeParams.id_user}, function(folders) {
        var idFolder = parseInt($routeParams.id_folder);
        if (idFolder > 0) {
            folder = FolderUtils.findById(folders, idFolder);
        } else {
            folder = FolderUtils.root(folders);
        }
        $scope.folders = FolderUtils.children(folders, folder.id_folder);
        $scope.path = FolderUtils.pathTo(folders, folder);
        $scope.files = File.query({
            id_user: $scope.idUser,
            id_folder: folder.id_folder
        });
    });
    $scope.uploadFiles = function($files) {
        if ($files.length > 0) {
            $scope.uploadingProgress = {
                percent: 0,
                style: {
                    width: '0%'
                }
            };
            $upload.upload({
                url: '/rest/user/' + $scope.idUser + '/folder/' + folder.id_folder + '/file',
                method: 'POST',
                file: $files,
                fileFormDataName: 'files[]'
            }).progress(function(event) {
                var percent = parseInt(100.0 * event.loaded / event.total);
                $scope.uploadingProgress.percent = percent;
                $scope.uploadingProgress.style.width = percent + '%';
            }).success(function() {
                $scope.files = File.query({
                    id_user: $scope.idUser,
                    id_folder: folder.id_folder
                });
                $scope.uploadingProgress = null;
                $fileUpload.val(null);
            }).error(function() {
                alert('Si è verificato un errore durante il caricamento dei file.');
                $scope.uploadingProgress = null;
            });
        }
    };
    $scope.deleteFile = function(file) {
        if (confirm('Sei sicuro di voler eliminare questo file? Non sarà possibile recuperarlo.')) {
            file.$delete(function() {
                var index = $scope.files.indexOf(file);
                $scope.files.splice(index, 1);
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
    $scope.createFolder = function() {
        var newFolder = new Folder();
        newFolder.name = prompt('Crea una nuova cartella', '');
        if (newFolder.name !== null && newFolder.name.trim().length > 0) {
            newFolder.id_parent = folder.id_folder;
            newFolder.public = false;
            newFolder.$save(function() {
                $scope.folders.push(newFolder);
            });
        }
    };
});