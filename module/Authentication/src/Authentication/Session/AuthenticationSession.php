<?php
namespace Authentication\Session;

use Authentication\Session\Exception\NoUserInSession;
use Database\Dao\UserDao;
use Database\Model\User;
use Zend\Authentication\Storage\Session;

class AuthenticationSession extends Session {
    /**
     * @var UserDao
     */
    private $userDao;

    public function setUserDao(UserDao $userDao) {
        $this->userDao = $userDao;
    }

    /**
     * @return User
     * @throws Exception\NoUserInSession
     */
    public function getUser() {
        if ($this->isEmpty()) {
            throw new NoUserInSession("No user data in session");
        }
        $idUser = $this->read()->id_user;
        $user = $this->userDao->findById($idUser);
        return $user;
    }
} 