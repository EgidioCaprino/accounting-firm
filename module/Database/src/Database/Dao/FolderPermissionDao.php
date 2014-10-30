<?php
namespace Database\Dao;

use Database\Model\Folder;
use Database\Model\User;

class FolderPermissionDao extends AbstractDao {
    public function getUserFolderPermissions(User $user) {
        return $this->select(array("id_user" => $user->id_user));
    }

    public function isAllowed(User $user, Folder $folder) {
        if ($user->admin) {
            return true;
        }
        $resultSet = $this->select(array(
            'id_user' => $user->id_user,
            'id_folder' => $folder->id_folder
        ));
        return $resultSet->count() > 0;
    }
}