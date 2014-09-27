<?php
namespace Database\Model\Factory;

use Database\Model\Folder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        return new Folder("id_folder", "folder", $adapter);
    }
}