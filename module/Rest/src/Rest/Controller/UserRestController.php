<?php
namespace Rest\Controller;

use Application\MailSender;
use Database\Dao\UserDao;
use Database\Model\User;
use Utils\Database\DatabaseUtils;
use Zend\Db\Sql\Select;
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
        $message = "Il tuo account su accounting-firm.egidiocaprino.it è stato creato correttamente.\n"
                 . "Le tue credenziali di accesso sono:\n"
                 . "Username: " . $user->username . "\n"
                 . "Password: " . $data['password'];
        MailSender::sendMail(array($user->email), 'Nuovo account su Accounting Firm', $message);
        return new JsonModel($user->toArray());
    }

    public function delete($id) {
        $user = $this->getDao()->findById($id);
        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')->getDriver()->getConnection();
        $db->beginTransaction();
        try {
            $folderPermissionDao = $this->getServiceLocator()->get('Database\Dao\FolderPermissionDao');
            $folderPermissions = $folderPermissionDao->select(array('id_user' => $user->id_user));
            $folderDao = $this->getServiceLocator()->get('Database\Dao\FolderDao');
            $fileDao = $this->getServiceLocator()->get('Database\Dao\FileDao');
            foreach ($folderPermissions as $permission) {
                if ($folderPermissionDao->select(array('id_folder' => $permission->id_folder))->count() === 1) {
                    $permission->delete();
                    $folder = $folderDao->select(array('id_folder' => $permission->id_folder))->current();
                    $folder->deleteDependencies($folderDao, $fileDao, $folderPermissionDao);
                    $folder->delete();
                }
            }
            $folderPermissionDao->delete(array('id_user' => $user->id_user));
            $user->delete();
            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        }
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
            $dao = $this->getDao();
            $select = new Select($dao->getTable());
            $select->order('username ASC');
            $users = $dao->selectWith($select);
            $array = DatabaseUtils::resultSetToArray($users);
        } else {
            $array[] = $user->toArray();
        }
        return new JsonModel($array);
    }

    public function update($id, $data) {
        $loggedUser = $this->getLoggedUser();
        if ($id != $data['id_user']) {
            throw new \Exception();
        }
        if (!$loggedUser->admin && ($loggedUser->id_user != $id || $data['id_user'] != $id)) {
            throw new BadMethodCallException(sprintf("User %d is not admin so he cannot update user %d", $loggedUser->id_user, $id));;
        }
        $user = $this->getDao()->findById($id);
        $newData = array();
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                if (!empty($value)) {
                    $newData['password'] = UserDao::encryptPassword($value);
                } else {
                    $newData['password'] = $user->password;
                }
            } else {
                $newData[$key] = $value;
            }
        }
        if (!isset($newData['password'])) {
            $newData['password'] = $user->password;
        }
        $this->getDao()->update($newData, array('id_user' => $id));
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