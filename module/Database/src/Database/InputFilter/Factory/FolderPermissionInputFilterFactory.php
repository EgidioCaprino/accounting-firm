<?php
namespace Database\InputFilter\Factory;

use Zend\InputFilter\Factory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderPermissionInputFilterFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $factory = new Factory();
        $inputFilter = $factory->createInputFilter(array(
            "id_user" => array(
                "name" => "id_user",
                "required" => true,
                "filters" => array(
                    array("name" => "Digits"),
                    array("name" => "Int")
                ),
                "validators" => array(
                    array(
                        "name" => 'Db\RecordExists',
                        "options" => array(
                            "adapter" => $adapter,
                            "table" => "user",
                            "field" => "id_user"
                        )
                    )
                )
            ),
            "id_folder" => array(
                "name" => "id_folder",
                "required" => true,
                "filters" => array(
                    array("name" => "Digits"),
                    array("name" => "Int")
                ),
                "validators" => array(
                    array(
                        "name" => 'Db\RecordExists',
                        "options" => array(
                            "adapter" => $adapter,
                            "table" => "folder",
                            "field" => "id_folder"
                        )
                    )
                )
            )
        ));
        return $inputFilter;
    }
}