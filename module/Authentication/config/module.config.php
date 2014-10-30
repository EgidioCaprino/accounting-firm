<?php
return array(
    "service_manager" => array(
        "factories" => array(
            'Authentication\UsernameAndPasswordAuthenticationAdapter' => 'Authentication\Factory\UsernameAndPasswordAuthenticationAdapterFactory',
            'Authentication\Session\AuthenticationSession' => 'Authentication\Session\Factory\AuthenticationSessionFactory',
            'Authentication\Acl\Acl' => 'Authentication\Factory\AclFactory'
        ),
        "services" => array(
            "permissions" => array(
                'Authentication\Controller\LoginController' => array(
                    "index" => array(\Authentication\Acl\Acl::ROLE_GUEST),
                    "authenticate" => array(\Authentication\Acl\Acl::ROLE_GUEST)
                ),
                'Authentication\Controller\LogoutController' => array(
                    "index" => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN)
                ),
                'Application\Controller\WebApplicationController' => array(
                    "index" => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN)
                ),
                'Rest\Controller\UserRestController' => array(
                    "logged-user" => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_GET => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_POST => array(\Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_PUT => array(\Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_DELETE => array(\Authentication\Acl\Acl::ROLE_ADMIN)
                ),
                'Rest\Controller\FolderRestController' => array(
                    \Zend\Http\Request::METHOD_GET => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_DELETE => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_POST => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                    'public-folders' => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                ),
                'Rest\Controller\FileRestController' => array(
                    \Zend\Http\Request::METHOD_GET => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_POST => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                    \Zend\Http\Request::METHOD_DELETE => array(\Authentication\Acl\Acl::ROLE_USER, \Authentication\Acl\Acl::ROLE_ADMIN),
                )
            )
        )
    ),
    "controllers" => array(
        "invokables" => array(
            'Authentication\Controller\LoginController' => 'Authentication\Controller\LoginController',
            'Authentication\Controller\LogoutController' => 'Authentication\Controller\LogoutController'
        )
    ),
    "router" => array(
        "routes" => array(
            "login" => array(
                "type" => "Literal",
                "options" => array(
                    "route" => "/login",
                    "defaults" => array(
                        "controller" => 'Authentication\Controller\LoginController'
                    )
                ),
                "may_terminate" => false,
                "child_routes" => array(
                    "get" => array(
                        "type" => "Method",
                        "options" => array(
                            "verb" => "get",
                            "defaults" => array(
                                "action" => "index"
                            )
                        )
                    ),
                    "post" => array(
                        "type" => "Method",
                        "options" => array(
                            "verb" => "post",
                            "defaults" => array(
                                "action" => "authenticate"
                            )
                        )
                    )
                )
            ),
            "logout" => array(
                "type" => "Literal",
                "options" => array(
                    "route" => "/logout",
                    "defaults" => array(
                        "controller" => 'Authentication\Controller\LogoutController',
                        "action" => "index"
                    )
                )
            )
        )
    ),
    "view_manager" => array(
        "template_path_stack" => array(
            __DIR__ . "/../view",
        ),
    ),
);