<?php
namespace DatabaseTest\Dao;

use DatabaseTest\Bootstrap;

class FolderDaoTest extends \PHPUnit_Framework_TestCase {
    public function testInsertValidData() {
        $dao = Bootstrap::getServiceManager()->get('Database\Dao\FolderDao');
        $dao->insert(array(
            "name" => "test_folder",
            "public" => true
        ));
        $id = $dao->getLastInsertValue();
        $folder = $dao->findById($id);
        $this->assertNotNull($folder);
        $folder->delete();
        $folder = $dao->findById($id);
        $this->assertNull($folder);
    }

    /**
     * @expectedException \Utils\InputFilter\Exception\InvalidDataException
     */
    public function testInsertWithInvalidData() {
        $dao = Bootstrap::getServiceManager()->get('Database\Dao\FolderDao');
        $dao->insert(array(
            "name" => "",
            "id_parent" => "abc",
            "public" => 1234567890
        ));
    }
}