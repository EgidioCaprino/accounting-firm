<?php
namespace AuthenticationTest\Controller;

use AuthenticationTest\Bootstrap;
use Zend\Http\Request;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class LoginControllerTest extends AbstractHttpControllerTestCase {
    public function setUp() {
        $this->setApplicationConfig(Bootstrap::getApplicationConfig());
        parent::setUp();
    }

    public function testLoginForm() {
        $this->dispatch("/login");
        $this->assertResponseStatusCode(200);
        $this->assertModuleName("Authentication");
        $this->assertControllerName('Authentication\Controller\LoginController');
        $this->assertControllerClass("LoginController");
        $this->assertMatchedRouteName("login/get");
    }

    public function testLogin() {
        $user = Bootstrap::getServiceManager()->get('Database\Model\User');
        $user->populate(array(
            "username" => "EgidioCaprino",
            "email" => "me@egidiocaprino.it",
            "password" => hash("sha256", "qwerty123")
        ));
        $user->save();

        $this->dispatch("/login", Request::METHOD_POST, array(
            "username" => "EgidioCaprino",
            "password" => "qwerty123"
        ));
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo("/");
        $this->assertModuleName("Authentication");
        $this->assertControllerName('Authentication\Controller\LoginController');
        $this->assertControllerClass("LoginController");
        $this->assertMatchedRouteName("login/post");

        $user->delete();
    }
}