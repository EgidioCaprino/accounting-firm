<?php
namespace DatabaseTest\Dao;

use DatabaseTest\Bootstrap;
use Utils\InputFilter\Exception\InvalidDataException;

class UserDaoTest extends \PHPUnit_Framework_TestCase {
    public function testPrimaryKey() {
        $serviceManager = Bootstrap::getServiceManager();
        $dao = $serviceManager->get('Database\Dao\UserDao');
        $columns = $dao->getPrimaryKey();
        $this->assertContains("id_user", $columns);
    }

    /**
     * @expectedException \Utils\InputFilter\Exception\InvalidDataException
     */
    public function testInsertInvalidData() {
        $data = array(
            "username" => "",
            "email" => "not.a.valid.email@.tld",
            "password" => "",
            "admin" => false
        );
        $serviceManager = Bootstrap::getServiceManager();
        $dao = $serviceManager->get('Database\Dao\UserDao');
        $dao->insert($data);
    }

    /**
     * @expectedException \Utils\InputFilter\Exception\InvalidDataException
     */
    public function testUpdateWithInvalidData() {
        $data = array(
            "username" => "EgidioCaprino",
            "email" => "me@egidiocaprino.it",
            "password" => hash("sha256", "qwerty123"),
            "admin" => true
        );
        $serviceManager = Bootstrap::getServiceManager();
        $dao = $serviceManager->get('Database\Dao\UserDao');
        $dao->insert($data);
        $id = $dao->getLastInsertValue();
        $data = array(
            "username" => "",
            "email" => "not.a.valid.email@.tld",
            "password" => "",
            "admin" => false
        );
        $where = array_combine($dao->getPrimaryKey(), array($id));
        try {
            $dao->update($data, $where);
        } catch (InvalidDataException $e) {
            throw $e;
        } finally {
            $dao->delete($where);
        }
    }
}