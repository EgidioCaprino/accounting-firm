<?php
namespace Database\Model\Factory;

use Database\Model\Folder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $inputFilter = $serviceLocator->get('Database\InputFilter\FolderInputFilter');
        $folder = new Folder("id_folder", "folder", $adapter);
        $folder->setInputFilter($inputFilter);
        return $folder;
    }
}