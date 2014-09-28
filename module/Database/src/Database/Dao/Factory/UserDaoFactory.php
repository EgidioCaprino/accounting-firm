<?php
namespace Database\Dao\Factory;

use Database\Dao\UserDao;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserDaoFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $user = $serviceLocator->get('Database\Model\User');
        $inputFilter = $serviceLocator->get('Database\InputFilter\UserInputFilter');
        $dao = new UserDao("user", $adapter, new RowGatewayFeature($user));
        $dao->setInputFilter($inputFilter);
        return $dao;
    }
}