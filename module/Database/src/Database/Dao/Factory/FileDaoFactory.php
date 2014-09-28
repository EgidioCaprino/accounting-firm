<?php
namespace Database\Dao\Factory;

use Database\Dao\FileDao;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileDaoFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $file = $serviceLocator->get('Database\Model\File');
        $inputFilter = $serviceLocator->get('Database\InputFilter\FileInputFilter');
        $dao = new FileDao("file", $adapter, new RowGatewayFeature($file));
        $dao->setInputFilter($inputFilter);
        return $dao;
    }
}