<?php
namespace Authentication\Factory;

use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UsernameAndPasswordAuthenticationAdapterFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $authAdapter = new CredentialTreatmentAdapter($db, "user", "username", "password", "SHA2(?, 256)");
        return $authAdapter;
    }
}