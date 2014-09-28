<?php
namespace Authentication\Acl;

class Acl extends \Zend\Permissions\Acl\Acl {
    const ROLE_GUEST = "guest";
    const ROLE_USER = "user";
    const ROLE_ADMIN = "admin";

    public function __construct() {
        $this->addRole(self::ROLE_GUEST);
        $this->addRole(self::ROLE_USER);
        $this->addRole(self::ROLE_ADMIN);

        $this->addResource('Authentication\Controller\LoginController');
        $this->addResource('Authentication\Controller\LogoutController');
        $this->addResource('Application\Controller\WebApplicationController');

        $this->deny();

        $this->allow(self::ROLE_GUEST, 'Authentication\Controller\LoginController', array("index", "authenticate"));
        $this->allow(array(self::ROLE_USER, self::ROLE_ADMIN), 'Authentication\Controller\LogoutController', "index");
        $this->allow(array(self::ROLE_USER, self::ROLE_ADMIN), 'Application\Controller\WebApplicationController', "index");
    }
} 