<?php
namespace Database\Dao\Factory;

use Database\Dao\FolderDao;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderDaoFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $folder = $serviceLocator->get('Database\Model\Folder');
        return new FolderDao("folder", $adapter, new RowGatewayFeature($folder));
    }
}