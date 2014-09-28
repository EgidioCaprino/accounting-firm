<?php
namespace DatabaseTest\Model;

use DatabaseTest\Bootstrap;

class FolderPermissionTest extends \PHPUnit_Framework_TestCase {
    public function testInsertValidData() {
        $user = Bootstrap::getServiceManager()->get('Database\Model\User');
        $user->populate(array(
            "username" => "test_folder_permission_user",
            "email" => "test_folder_permission_user@egidiocaprino.it",
            "password" => hash("sha256", "qwerty123")
        ));
        $user->save();

        $folder = Bootstrap::getServiceManager()->get('Database\Model\Folder');
        $folder->populate(array("name" => "folder_for_permission_test"));
        $folder->save();

        $folderPermission = Bootstrap::getServiceManager()->get('Database\Model\FolderPermission');
        $folderPermission->populate(array(
            "id_user" => $user->id_user,
            "id_folder" => $folder->id_folder
        ));
        $folderPermission->save();

        $folderPermission->delete();
        $folder->delete();
        $user->delete();
    }
}