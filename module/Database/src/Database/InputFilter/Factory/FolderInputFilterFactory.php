<?php
namespace Database\InputFilter\Factory;

use Zend\InputFilter\Factory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FolderInputFilterFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $factory = new Factory();
        $inputFilter = $factory->createInputFilter(array(
            "id_folder" => array(
                "name" => "id_folder",
                "required" => false,
                "filters" => array(
                    array("name" => "Digits"),
                    array("name" => "Int")
                )
            ),
            "name" => array(
                "name" => "name",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim")
                )
            ),
            "id_parent" => array(
                "name" => "id_parent",
                "required" => false,
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
            ),
            "public" => array(
                "name" => "public",
                "required" => false,
                "filters" => array(
                    array("name" => "Boolean")
                )
            )
        ));
        return $inputFilter;
    }
}