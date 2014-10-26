<?php
namespace Authentication\Factory;

use Authentication\Acl\Acl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $permissions = $serviceLocator->get("permissions");
        $acl = new Acl($permissions);
        return $acl;
    }
}