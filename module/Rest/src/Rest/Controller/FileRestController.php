<?php
namespace Rest\Controller;

use Database\Dao\FileDao;
use Database\InputFilter\Factory\FileInputFilterFactory;
use Database\Model\File;
use Database\Model\Folder;
use Utils\Database\DatabaseUtils;
use Zend\Db\Sql\Select;
use Zend\View\Model\JsonModel;

class FileRestController extends AbstractRestController {
    protected $identifierName = 'id_file';

    public function getList() {
        $user = $this->getAuthSession()->getUser();
        $folder = $this->getFolder();
        if (!$this->getServiceLocator()->get('Database\Dao\FolderPermissionDao')->isAllowed($user, $folder)) {
            return parent::getList();
        }
//        $fileDao = $this->getDao();
//        $select = new Select($fileDao->getTable());
//        $select->columns(array(
//            'id_file',
//            'id_folder',
//            'id_user',
//            'title',
//            'filename',
//            'size',
//            'mime_type',
//            'upload_date'
//        ))->where(array('id_folder' => $this->getFolder()->id_folder))
//        ->order('title ASC');
//        $files = $fileDao->selectWith($select);
        $filesArray = DatabaseUtils::resultSetToArray($this->getDao()->getFilesInFolder($folder));
        return new JsonModel($filesArray);
    }

    public function create($data) {
        $folder = $this->getFolder();
        $user = $this->getAuthSession()->getUser();
        if (!$this->getServiceLocator()->get('Database\Dao\FolderPermissionDao')->isAllowed($user, $folder)) {
            return parent::getList();
        }
        $files = array();
        foreach ($this->getRequest()->getFiles()['files'] as $uploadedFile) {
            $file = $this->createNewModel();
            $file->id_folder = $folder->id_folder;
            $file->id_user = $user->id_user;
            $file->title = $uploadedFile['name'];
            $file->filename = $uploadedFile['name'];
            $file->file_blob = file_get_contents($uploadedFile['tmp_name']);
            $file->size = $uploadedFile['size'];
            $file->mime_type = $uploadedFile['type'];
            $file->upload_date = date('Y-m-d H:i:s');
            $file->save();
            $files[] = $file->toArray();
        }
        return new JsonModel($files);
    }

    public function delete($idFile) {
        $user = $this->getAuthSession()->getUser();
        $file = $this->getDao()->select(array('id_file' => $idFile))->current();
        $folder = $this->getServiceLocator()->get('Database\Dao\FolderDao')->select(array('id_folder' => $file->id_folder))->current();
        if (!$this->getServiceLocator()->get('Database\Dao\FolderPermissionDao')->isAllowed($user, $folder)) {
            return parent::getList();
        }
        $file->delete();
        return new JsonModel($file->toArray());
    }

    /**
     * @return FileDao
     */
    protected function getDao() {
        return $this->getServiceLocator()->get('Database\Dao\FileDao');
    }

    /**
     * @return File
     */
    protected function createNewModel() {
        return $this->getServiceLocator()->get('Database\Model\File');
    }

    /**
     * @return FileInputFilterFactory
     */
    protected function getInputFilter() {
        return $this->getServiceLocator()->get('Database\InputFilter\FileInputFilter');
    }

    /**
     * @return Folder
     */
    private function getFolder() {
        $idFolder = (int) $this->params('id_folder');
        $folderDao = $this->getServiceLocator()->get('Database\Dao\FolderDao');
        $folder = $folderDao->select(array('id_folder' => $idFolder))->current();
        return $folder;
    }
}