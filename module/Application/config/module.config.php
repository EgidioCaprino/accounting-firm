<?php
return array(
    "controllers" => array(
        "invokables" => array(
            'Application\Controller\WebApplicationController' => 'Application\Controller\WebApplicationController'
        )
    ),
    "router" => array(
        "routes" => array(
            "web-application" => array(
                "type" => "Literal",
                "options" => array(
                    "route" => "/",
                    "defaults" => array(
                        "controller" => 'Application\Controller\WebApplicationController',
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