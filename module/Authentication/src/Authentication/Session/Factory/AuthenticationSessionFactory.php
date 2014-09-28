<?php
namespace Authentication\Session\Factory;

use Authentication\Session\AuthenticationSession;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationSessionFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $userDao = $serviceLocator->get('Database\Dao\UserDao');
        $authSession = new AuthenticationSession();
        $authSession->setUserDao($userDao);
        return $authSession;
    }
}