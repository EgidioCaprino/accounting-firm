<?php
return array(
    "controllers" => array(
        "invokables" => array(
            'Rest\Controller\UserRestController' => 'Rest\Controller\UserRestController',
            'Rest\Controller\FolderRestController' => 'Rest\Controller\FolderRestController',
            'Rest\Controller\FileRestController' => 'Rest\Controller\FileRestController'
        )
    ),
    "router" => array(
        "routes" => array(
            "rest" => array(
                "type" => "Literal",
                "options" => array(
                    "route" => "/rest"
                ),
                "may_terminate" => false,
                "child_routes" => array(
                    "user" => array(
                        "type" => "Segment",
                        "options" => array(
                            "route" => "/user[/:id_user]",
                            "defaults" => array(
                                "controller" => 'Rest\Controller\UserRestController'
                            ),
                            "constraints" => array(
                                "id_user" => "[0-9]+"
                            )
                        ),
                        "may_terminate" => true,
                        "child_routes" => array(
                            "logged-user" => array(
                                "type" => "Literal",
                                "options" => array(
                                    "route" => "/logged-user",
                                    "defaults" => array(
                                        "action" => "logged-user"
                                    )
                                )
                            ),
                            "folder" => array(
                                "type" => "Segment",
                                "options" => array(
                                    "route" => "/folder[/:id_folder]",
                                    "defaults" => array(
                                        "controller" => 'Rest\Controller\FolderRestController'
                                    ),
                                    "constraints" => array(
                                        "id_folder" => "[0-9]+"
                                    )
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'file' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/file[/:id_file]',
                                            'defaults' => array(
                                                'controller' => 'Rest\Controller\FileRestController'
                                            ),
                                            'constraints' => array(
                                                'id_file' => '[0-9]+'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    ),
                    'publicFolder' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/public-folder',
                            'defaults' => array(
                                'controller' => 'Rest\Controller\FolderRestController',
                                'action' => 'public-folders'
                            )
                        )
                    )
                )
            )
        )
    ),
    "view_manager" => array(
        "template_path_stack" => array(
            __DIR__ . "/../view",
        ),
        "strategies" => array(
            "ViewJsonStrategy"
        )
    ),
);