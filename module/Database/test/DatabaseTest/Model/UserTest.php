<?php
namespace DatabaseTest\Model;

use DatabaseTest\Bootstrap;

class UserTest extends \PHPUnit_Framework_TestCase {
    public function testInstancesFromServiceManager() {
        $firstInstance = Bootstrap::getServiceManager()->get('Database\Model\User');
        $this->assertInstanceOf('Database\Model\User', $firstInstance);
        $secondInstance = Bootstrap::getServiceManager()->get('Database\Model\User');
        $this->assertInstanceOf('Database\Model\User', $secondInstance);
        $this->assertNotSame($firstInstance, $secondInstance);
    }

    public function testCrudOperations() {
        $data = array(
            "username" => "EgidioCaprino",
            "email" => "me@egidiocaprino.it",
            "password" => hash("sha256", "qwerty123"),
            "admin" => true
        );
        $user = Bootstrap::getServiceManager()->get('Database\Model\User');
        $user->populate($data);
        $user->save();

        $dao = Bootstrap::getServiceManager()->get('Database\Dao\UserDao');;
        $fetchedUser = $dao->findById($user->id_user);
        $this->assertInstanceOf('Database\Model\User', $fetchedUser);
        $this->assertSame($data["username"], $fetchedUser->username);
        $this->assertSame($data["email"], $fetchedUser->email);
        $this->assertEquals($data["admin"], $fetchedUser->admin);

        $fetchedUser->delete();
        $this->assertNull($dao->findById($user->id_user));
    }

    /**
     * @expectedException \Utils\InputFilter\Exception\InvalidDataException
     */
    public function testSaveInvalidData() {
        $data = array(
            "username" => "",
            "email" => "not.a.valid.email@.tld",
            "password" => "",
            "admin" => false
        );
        $user = Bootstrap::getServiceManager()->get('Database\Model\User');
        $user->populate($data);
        $user->save();
    }
}