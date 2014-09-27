<?php
namespace Database\Model\Factory;

use Database\Model\User;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $inputFilter = $serviceLocator->get('Database\InputFilter\Factory\UserInputFilterFactory');
        $user = new User("id_user", "user", $adapter);
        $user->setInputFilter($inputFilter);
        return $user;
    }
}