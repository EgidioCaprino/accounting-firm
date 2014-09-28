<?php
namespace Database\Model\Factory;

use Database\Model\FolderIterator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderIteratorFactory implements FactoryInterface {
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this->createFolderIterator();
    }

    /**
     * @return FolderIterator
     */
    public function createFolderIterator() {
        $folderDao = $this->serviceLocator->get('Database\Dao\FolderDao');
        $fileDao = $this->serviceLocator->get('Database\Dao\FileDao');
        $folderIterator = new FolderIterator();
        $folderIterator->setFolderDao($folderDao);
        $folderIterator->setFileDao($fileDao);
        $folderIterator->setFactory($this);
        return $folderIterator;
    }
}