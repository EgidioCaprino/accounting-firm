<?php
namespace Database\Model\Factory;

use Database\Model\File;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $inputFilter = $serviceLocator->get('Database\InputFilter\FileInputFilter');
        $file = new File("id_file", "file", $adapter);
        $file->setInputFilter($inputFilter);
        return $file;
    }
}