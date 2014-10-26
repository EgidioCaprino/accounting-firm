<?php
namespace Rest\Controller;

use Database\Dao\UserDao;
use Database\Model\User;
use Utils\Database\DatabaseUtils;
use Zend\Mvc\Exception\BadMethodCallException;
use Zend\View\Model\JsonModel;

class UserRestController extends AbstractRestController {
    protected $identifierName = "id_user";

    public function create($data) {
        $inputFilter = $this->getInputFilter();
        $inputFilter->setData($data);
        $user = $this->createNewModel();
        $user->populate($data);
        if (isset($user->password) && !empty($user->password)) {
            $user->password = UserDao::encryptPassword($user->password);
        }
        $user->save();
        $folder = $this->getServiceLocator()->get('Database\Model\Folder');
        $folder->name = $user->username;
        $folder->id_parent = null;
        $folder->public = false;
        $folder->save();
        $folderPermission = $this->getServiceLocator()->get('Database\Model\FolderPermission');
        $folderPermission->id_user = $user->id_user;
        $folderPermission->id_folder = $folder->id_folder;
        $folderPermission->save();
        return new JsonModel($user->toArray());
    }

    public function delete($id) {
        $user = $this->getDao()->findById($id);
        $user->delete();
        return new JsonModel($user->toArray());
    }

    public function get($id) {
        if (!$this->getLoggedUser()->admin && $this->getLoggedUser()->id_user != $id) {
            return parent::get($id);
        }
        $user = $this->getDao()->findById($id);
        return new JsonModel($user->toArray());
    }

    public function getList() {
        $array = array();
        $user = $this->getLoggedUser();
        if ($user->admin) {
            $users = $this->getDao()->select();
            $array = DatabaseUtils::resultSetToArray($users);
        } else {
            $array[] = $user->toArray();
        }
        return new JsonModel($array);
    }

    public function update($id, $data) {
        $loggedUser = $this->getLoggedUser();
        if (!$loggedUser->admin && $loggedUser->id_user != $id) {
            throw new BadMethodCallException(sprintf("User %d is not admin so he cannot update user %d", $loggedUser->id_user, $id));;
        }
        $user = $this->getDao()->findById($id);
        $user->exchangeArray($data);
        $user->save();
        return new JsonModel($user->toArray());
    }

    public function loggedUserAction() {
        $user = $this->getLoggedUser();
        return new JsonModel($user->toArray());
    }

    /**
     * @return UserDao
     */
    protected function getDao() {
        return $this->getServiceLocator()->get('Database\Dao\UserDao');
    }

    /**
     * @return User
     */
    protected function createNewModel() {
        return $this->getServiceLocator()->get('Database\Model\User');
    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    protected function getInputFilter() {
        return $this->getServiceLocator()->get('Database\InputFilter\UserInputFilter');
    }
}