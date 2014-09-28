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
                    array("name" => "Digits"),
                    array("name" => "Int")
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
            "password" => array(
                "name" => "password",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim")
                ),
                "validators" => array(
                    array(
                        "name" => "StringLength",
                        "options" => array(
                            "min" => 64,
                            "max" => 64
                        )
                    ),
                    array(
                        "name" => "Regex",
                        "options" => array(
                            "pattern" => "/^[0-9a-z]{64}$/i"
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