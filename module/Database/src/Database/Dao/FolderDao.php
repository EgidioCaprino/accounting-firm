<?php
namespace Database\Dao;

use Database\Model\Folder;
use Database\Model\User;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;

class FolderDao extends AbstractDao {
    /**
     * @var FolderPermissionDao
     */
    private $folderPermissionDao = null;

    /**
     * @var FileDao
     */
    private $fileDao = null;

    /**
     * @param FolderPermissionDao $folderPermissionDao
     */
    public function setFolderPermissionDao(FolderPermissionDao $folderPermissionDao) {
        $this->folderPermissionDao = $folderPermissionDao;
    }

    /**
     * @return FolderPermissionDao
     */
    public function getFolderPermissionDao() {
        return $this->folderPermissionDao;
    }

    public function setFileDao(FileDao $fileDao) {
        $this->fileDao = $fileDao;
    }

    public function getFileDao() {
        return $this->fileDao;
    }

    public function getFoldersInFolder($folder) {
        if ($folder instanceof Folder) {
            $folder = $folder->id_folder;
        }
        $folders = $this->select(array("id_parent" => $folder));
        return $folders;
    }

    public function getUserFolders(User $user) {
        $userFolderPermissions = $this->getFolderPermissionDao()->getUserFolderPermissions($user);
        $folderIds = array();
        foreach ($userFolderPermissions as $folderPermission) {
            $folderIds[] = $folderPermission->id_folder;
        }
        if (empty($folderIds)) {
            return new ResultSet();
        }
        return $this->select(array("id_folder" => $folderIds));
    }

    public function getFiles(Folder $folder) {
        $files = $this->fileDao->select(array('id_folder' => $folder->id_folder));
        return $files;
    }

    public function getPermissions(Folder $folder) {
        $permissions = $this->folderPermissionDao->select(array('id_folder' => $folder->id_folder));
        return $permissions;
    }
}