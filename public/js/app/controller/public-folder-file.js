app.controller('PublicFolderFileController', function($scope, $routeParams, $upload, User, Folder, File) {
    $scope.folder = {};
    $scope.files = [];
    $scope.uploadingProgress = null;
    var $fileUpload = $('#fileUpload');
    $scope.user = User.loggedUser(function(user) {
        $scope.folder = Folder.get({
            id_user: user.id_user,
            id_folder: $routeParams.id_folder
        });
        $scope.files = File.query({
            id_user: user.id_user,
            id_folder: $routeParams.id_folder
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
                url: '/rest/user/' + $scope.user.id_user + '/folder/' + $scope.folder.id_folder + '/file',
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
                    id_folder: $scope.folder.id_folder
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
});