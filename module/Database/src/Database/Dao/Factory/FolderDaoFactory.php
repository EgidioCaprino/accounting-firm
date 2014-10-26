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
        $inputFilter = $serviceLocator->get('Database\InputFilter\FolderInputFilter');
        $dao = new FolderDao("folder", $adapter, new RowGatewayFeature($folder));
        $dao->setInputFilter($inputFilter);
        $folderPermissionDao = $serviceLocator->get('Database\Dao\FolderPermissionDao');
        $dao->setFolderPermissionDao($folderPermissionDao);
        $fileDao = $serviceLocator->get('Database\Dao\FileDao');
        $dao->setFileDao($fileDao);
        return $dao;
    }
}