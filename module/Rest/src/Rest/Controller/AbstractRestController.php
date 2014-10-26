<?php
namespace Rest\Controller;

use Authentication\Session\AuthenticationSession;
use Database\Dao\AbstractDao;
use Database\Model\AbstractModel;
use Database\Model\User;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

abstract class AbstractRestController extends AbstractRestfulController {
    /**
     * @return AbstractDao
     */
    abstract protected function getDao();

    /**
     * @return AbstractModel
     */
    abstract protected function createNewModel();

    /**
     * @return InputFilter
     */
    abstract protected function getInputFilter();

    /**
     * @return User
     */
    protected function getLoggedUser() {
        $authSession = $this->getAuthSession();
        $user = $authSession->getUser();
        return $user;
    }

    /**
     * @return AuthenticationSession
     */
    protected function getAuthSession() {
        return $this->getServiceLocator()->get('Authentication\Session\AuthenticationSession');
    }
} 