<?php
namespace Database\Model\Factory;

use Database\Model\FolderPermission;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderPermissionFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $inputFilter = $serviceLocator->get('Database\InputFilter\FolderPermissionInputFilter');
        $folderPermission = new FolderPermission(array("id_user", "id_folder"), "folder_permission", $adapter);
        $folderPermission->setInputFilter($inputFilter);
        return $folderPermission;
    }
}