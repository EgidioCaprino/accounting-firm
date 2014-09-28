<?php
namespace Database\Dao\Factory;

use Database\Dao\FolderPermissionDao;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderPermissionDaoFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $folderPermission = $serviceLocator->get('Database\Model\FolderPermission');
        $inputFilter = $serviceLocator->get('Database\InputFilter\FolderPermissionInputFilter');
        $dao = new FolderPermissionDao("folder_permission", $adapter, new RowGatewayFeature($folderPermission));
        $dao->setInputFilter($inputFilter);
        return $dao;
    }
}