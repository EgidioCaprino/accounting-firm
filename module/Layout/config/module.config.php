<?php
return array(
    "view_manager" => array(
        "template_map" => array(
            "layout/layout" => __DIR__ . "/../view/layout/layout.phtml",
            "error/500" => __DIR__ . "/../view/error/500.phtml",
            "error/404" => __DIR__ . "/../view/error/404.phtml"
        ),
        "template_path_stack" => array(
            __DIR__ . "/../view"
        ),
        "display_exceptions" => true,
        "display_not_found_reason" => true,
        "exception_template" => "error/500",
        "not_found_template" => "error/404"
    )
);