<?php
namespace Database\Model;

use Database\Dao\FileDao;
use Database\Dao\FolderDao;
use Database\Dao\FolderPermissionDao;

class Folder extends AbstractModel {
    public function deleteDependencies(FolderDao $folderDao, FileDao $fileDao, FolderPermissionDao $folderPermissionDao) {
        $subFolders = $folderDao->select(array('id_parent' => $this->id_folder));
        foreach ($subFolders as $folder) {
            $folder->deleteDependencies($folderDao, $fileDao, $folderPermissionDao);
            $folder->delete();
        }
        $folderPermissionDao->delete(array('id_folder' => $this->id_folder));
        $fileDao->delete(array('id_folder' => $this->id_folder));
    }
}