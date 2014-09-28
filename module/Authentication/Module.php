<?php
namespace Authentication;

use Authentication\Acl\Acl;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

class Module {
    public function onBootstrap(MvcEvent $event) {
        $event->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $event) {
            $role = Acl::ROLE_GUEST;
            $authSession = $event->getApplication()->getServiceManager()->get('Authentication\Session\AuthenticationSession');
            if (!$authSession->isEmpty()) {
                $user = $authSession->getUser();
                if ($user->admin) {
                    $role = Acl::ROLE_ADMIN;
                } else {
                    $role = Acl::ROLE_USER;
                }
            }

            $controller = $event->getRouteMatch()->getParam("controller");
            $action = $event->getRouteMatch()->getParam("action");

            $acl = $event->getApplication()->getServiceManager()->get('Authentication\Acl\Acl');
            if (!$acl->isAllowed($role, $controller, $action)) {
                $url = "/login";
                if ($role !== Acl::ROLE_GUEST) {
                    $url = "/";
                }
                $response = $event->getResponse();
                $response->getHeaders()->addHeaderLine("Location", $url);
                $response->setStatusCode(Response::STATUS_CODE_302);
                return $response;
            }
        }, 100);
    }

    public function getConfig() {
        return include __DIR__ . "/config/module.config.php";
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                "namespaces" => array(
                    __NAMESPACE__ => __DIR__ . "/src/" . __NAMESPACE__
                )
            )
        );
    }
}