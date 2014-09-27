<?php
return array(
    "db" => array(
        "charset" => "UTF8"
    ),
    "service_manager" => array(
        "factories" => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        )
    )
);