<?php
namespace Database\Dao;

use Database\Model\Folder;

class FolderDao extends AbstractDao {
    public function getFoldersInFolder($folder) {
        if ($folder instanceof Folder) {
            $folder = $folder->id_folder;
        }
        $folders = $this->select(array("id_parent" => $folder));
        return $folders;
    }
}