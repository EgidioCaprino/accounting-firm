<?php
namespace Database\Model;

use Database\Dao\Exception\InvalidModelInstanceException;
use Database\Dao\FileDao;
use Database\Dao\FolderDao;
use Database\Model\Factory\FolderIteratorFactory;
use Utils\Database\DatabaseUtils;

class FolderIterator implements \Iterator {
    private $model;

    /**
     * @var FolderIteratorFactory
     */
    private $factory;

    private $folders = array();
    private $files = array();
    private $foldersAndFiles = array();
    private $index;

    /**
     * @var FolderDao
     */
    private $folderDao;

    /**
     * @var FileDao
     */
    private $fileDao;

    public function setFolderDao(FolderDao $folderDao) {
        $this->folderDao = $folderDao;
    }

    public function setFileDao(FileDao $fileDao) {
        $this->fileDao = $fileDao;
    }

    public function setFactory(FolderIteratorFactory $factory) {
        $this->factory = $factory;
    }

    public function setModel($model) {
        $this->index = 0;
        $this->model = $model;
        if ($model instanceof File) {
            $this->folders = array();
            $this->files = array();
            $this->foldersAndFiles = array();
        } elseif ($model instanceof Folder) {
            $folders = $this->folderDao->getFoldersInFolder($model);
            $files = $this->fileDao->getFilesInFolder($model);
            $this->folders = DatabaseUtils::resultSetToArray($folders);
            $this->files = DatabaseUtils::resultSetToArray($files);
            $this->foldersAndFiles = array_merge($this->folders, $this->files);
        } else {
            throw new InvalidModelInstanceException("Instance of File or Folder expected");
        }
    }

    public function getModel() {
        return $this->model;
    }

    public function current() {
        $current = $this->foldersAndFiles[$this->index];
        $folderIterator = $this->factory->createFolderIterator();
        $folderIterator->setModel($current);
        return $folderIterator;
    }

    public function next() {
        $this->index++;
    }

    public function key() {
        $this->index;
    }

    public function valid() {
        if ($this->index >= 0 && $this->index < count($this->foldersAndFiles)) {
            return true;
        }
        return false;
    }

    public function rewind() {
        $this->index = 0;
    }

    public function isFile() {
        return $this->model instanceof File;
    }

    public function isFolder() {
        return $this->model instanceof Folder;
    }
}