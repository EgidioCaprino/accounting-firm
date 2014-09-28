<?php
namespace DatabaseTest\Model;

use DatabaseTest\Bootstrap;

class FolderTest extends \PHPUnit_Framework_TestCase {
    public function testCrudOperations() {
        $data = array(
            "name" => "folder_1",
            "public" => false
        );
        $folder = Bootstrap::getServiceManager()->get('Database\Model\Folder');
        $this->assertInstanceOf('Database\Model\Folder', $folder);
        $folder->populate($data);
        $folder->save();

        $dao = Bootstrap::getServiceManager()->get('Database\Dao\FolderDao');
        $fetchedFolder = $dao->findById($folder->id_folder);
        $this->assertSame($data["name"], $fetchedFolder->name);
        $this->assertFalse($fetchedFolder->public);

        $childFolder = Bootstrap::getServiceManager()->get('Database\Model\Folder');
        $childFolder->populate(array(
            "name" => "folder_2",
            "id_parent" => $folder->id_folder
        ));
        $childFolder->save();
        $childFolder = $dao->findById($childFolder->id_folder);
        $this->assertNotNull($childFolder);

        $childFolder->delete();
        $deletedFolder = $dao->findById($childFolder->id_folder);
        $this->assertNull($deletedFolder);
        $fetchedFolder->delete();
        $deletedFolder = $dao->findById($folder->id_folder);
        $this->assertNull($deletedFolder);
    }

    /**
     * @expectedException \Utils\InputFilter\Exception\InvalidDataException
     */
    public function testSaveInvalidData() {
        $folder = Bootstrap::getServiceManager()->get('Database\Model\Folder');
        $folder->populate(array(
            "name" => "valid_folder_name",
            "id_parent" => -1,
            "public" => false
        ));
        $folder->save();
    }
}