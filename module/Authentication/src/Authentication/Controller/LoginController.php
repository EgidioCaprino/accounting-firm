<?php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController {
    public function indexAction() {

    }

    public function authenticateAction() {
        $post = $this->getRequest()->getPost();
        $username = $post->username;
        $password = $post->password;
        $authAdapter = $this->getServiceLocator()->get('Authentication\UsernameAndPasswordAuthenticationAdapter');
        $authAdapter->setIdentity($username);
        $authAdapter->setCredential($password);
        $result = $authAdapter->authenticate();
        if ($result->isValid()) {
            $userRow = $authAdapter->getResultRowObject();
            $authSession = $this->getServiceLocator()->get('Authentication\Session\AuthenticationSession');
            $authSession->write($userRow);
            return $this->redirect()->toRoute("web-application");
        } else {
            $view = new ViewModel(array("error" => true));
            $view->setTemplate("authentication/login/index");
            return $view;
        }
    }
}