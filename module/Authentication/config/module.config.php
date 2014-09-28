<?php
return array(
    "service_manager" => array(
        "factories" => array(
            'Authentication\UsernameAndPasswordAuthenticationAdapter' => 'Authentication\Factory\UsernameAndPasswordAuthenticationAdapterFactory',
            'Authentication\Session\AuthenticationSession' => '\Authentication\Session\Factory\AuthenticationSessionFactory'
        ),
        "invokables" => array(
            'Authentication\Acl\Acl' => 'Authentication\Acl\Acl'
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