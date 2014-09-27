<?php
namespace Database\InputFilter\Factory;

use Zend\InputFilter\Factory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserInputFilterFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $factory = new Factory();
        $inputFilter = $factory->createInputFilter(array(
            "id_user" => array(
                "name" => "id_user",
                "required" => false,
                "filters" => array(
                    array("name" => "Digits")
                )
            ),
            "username" => array(
                "name" => "username",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim")
                )
            ),
            "email" => array(
                "name" => "email",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim"),
                    array("name" => "StringToLower")
                ),
                "validators" => array(
                    array(
                        "name" => "EmailAddress",
                        "options" => array(
                            "deep" => true,
                            "mx" => true
                        )
                    )
                )
            ),
            "admin" => array(
                "name" => "admin",
                "required" => false,
                "filters" => array(
                    array("name" => "Boolean")
                )
            )
        ));
        return $inputFilter;
    }
}