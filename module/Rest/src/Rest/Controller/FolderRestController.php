<?php
namespace Rest\Controller;

use Database\Dao\FolderDao;
use Database\Model\Folder;
use Utils\Database\DatabaseUtils;
use Zend\Db\Adapter\Adapter;
use Zend\Debug\Debug;
use Zend\InputFilter\InputFilter;
use Zend\View\Model\JsonModel;

class FolderRestController extends AbstractRestController {
    protected $identifierName = "id_folder";

    public function getList() {
        $loggedUser = $this->getAuthSession()->getUser();
        $folderUserId = (int) $this->params("id_user", 0);
        if (!$loggedUser->admin && $folderUserId !== $loggedUser->id_user) {
            return parent::getList();
        }
        $folderUser = $this->getServiceLocator()->get('Database\Dao\UserDao')->findById($folderUserId);
        $folders = $this->getDao()->getUserFolders($folderUser);
        return new JsonModel(DatabaseUtils::resultSetToArray($folders));
    }

    public function delete($idFolder) {
        $user = $this->getAuthSession()->getUser();
        $folder = $this->getDao()->select(array('id_folder' => $idFolder))->current();
        if (!$this->getServiceLocator()->get('Database\Dao\FolderPermissionDao')->isAllowed($user, $folder)) {
            return parent::delete($idFolder);
        }
        $folderDao = $this->getDao();
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $adapter->getDriver()->getConnection()->beginTransaction();
        try {
            foreach ($folderDao->getPermissions($folder) as $permission) {
                $permission->delete();
            }
            foreach ($folderDao->getFiles($folder) as $file) {
                $file->delete();
            }
            $folder->delete();
        } catch (\Exception $e) {
            $adapter->getDriver()->getConnection()->rollback();
            throw $e;
        }
        $adapter->getDriver()->getConnection()->commit();
        return new JsonModel($folder->toArray());
    }

    public function create(array $data) {
        $user = $this->getAuthSession()->getUser();
        $folderDao = $this->getDao();
        $folderPermissionDao = $this->getServiceLocator()->get('Database\Dao\FolderPermissionDao');
        $folder = $this->createNewModel();
        $folder->populate($data);
        if (is_numeric($folder->id_parent)) {
            $parentFolder = $folderDao->select(array('id_folder' => $folder->id_parent))->current();
            if (!$folderPermissionDao->isAllowed($user, $parentFolder)) {
                return parent::create($data);
            }
        }
        if ($folder->public && !$user->admin) {
            return parent::create($data);
        }
        $folder->save();
        $folderPermissionDao->insert(array(
            'id_folder' => $folder->id_folder,
            'id_user' => $user->id_user
        ));
        return new JsonModel($folder->toArray());
    }

    /**
     * @return FolderDao
     */
    protected function getDao() {
        return $this->getServiceLocator()->get('Database\Dao\FolderDao');
    }

    /**
     * @return Folder
     */
    protected function createNewModel() {
        return $this->getServiceLocator()->get('Database\Model\Folder');
    }

    /**
     * @return InputFilter
     */
    protected function getInputFilter() {
        return $this->getServiceLocator()->get('Database\InputFilter\FolderInputFilter');
    }
}