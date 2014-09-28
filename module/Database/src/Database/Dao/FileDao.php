<?php
namespace Database\Dao;

use Database\Model\Folder;

class FileDao extends AbstractDao {
    public function getFilesInFolder($folder) {
        if ($folder instanceof Folder) {
            $folder = $folder->id_folder;
        }
        $files = $this->select(array("id_folder" => $folder));
        return $files;
    }
}