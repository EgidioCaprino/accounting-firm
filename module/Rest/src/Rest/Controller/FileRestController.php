<?php
namespace Rest\Controller;

use Database\Dao\FileDao;
use Database\InputFilter\Factory\FileInputFilterFactory;
use Database\Model\File;
use Database\Model\Folder;
use Utils\Database\DatabaseUtils;
use Zend\Db\Sql\Select;
use Zend\Debug\Debug;
use Zend\View\Model\JsonModel;

class FileRestController extends AbstractRestController {
    protected $identifierName = 'id_file';

    public function getList() {
        $user = $this->getAuthSession()->getUser();
        $folder = $this->getFolder();
        if (!$this->getServiceLocator()->get('Database\Dao\FolderPermissionDao')->isAllowed($user, $folder)) {
            return parent::getList();
        }
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

    public function get($fileId) {
        $user = $this->getAuthSession()->getUser();
        $file = $this->getDao()->findById($fileId);
        $folder = $this->getServiceLocator()->get('Database\Dao\FolderDao')->findById($file->id_folder);
        $allowed = $this->getServiceLocator()->get('Database\Dao\FolderPermissionDao')->isAllowed($user, $folder);
        if (!$allowed) {
            return parent::get($fileId);
        }
        $response = $this->getResponse();
        $response->setContent($file->file_blob);
        $response->getHeaders()->addHeaderLine('Content-Type', $file->mime_type)
                               ->addHeaderLine('Content-Length', $file->size)
                               ->addHeaderLine('Content-Disposition', 'inline; filename="' . $file->filename . '"');
        return $response;
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