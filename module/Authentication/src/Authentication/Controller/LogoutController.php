<?php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class LogoutController extends AbstractActionController {
    public function indexAction() {
        $session = $this->getServiceLocator()->get('Authentication\Session\AuthenticationSession');
        $session->clear();
        return $this->redirect()->toRoute("login/get");
    }
}