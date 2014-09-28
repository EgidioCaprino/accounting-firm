<?php
namespace Database\InputFilter\Factory;

use Utils\Date\DateUtils;
use Zend\InputFilter\Factory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileInputFilterFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $adapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $factory = new Factory();
        $inputFilter = $factory->createInputFilter(array(
            "id_file" => array(
                "name" => "id_file",
                "required" => false,
                "filters" => array(
                    array("name" => "Digits"),
                    array("name" => "Int")
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
            ),
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
            "title" => array(
                "name" => "title",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim")
                )
            ),
            "filename" => array(
                "name" => "filename",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim")
                )
            ),
            "file_blob" => array(
                "name" => "file_blob",
                "required" => true
            ),
            "size" => array(
                "name" => "size",
                "required" => true,
                "filters" => array(
                    array("name" => "Digits"),
                    array("name" => "Int")
                ),
                "validators" => array(
                    array(
                        "name" => "Between",
                        "options" => array("min" => 1)
                    )
                )
            ),
            "mime_type" => array(
                "name" => "mime_type",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim")
                )
            ),
            "upload_date" => array(
                "name" => "upload_date",
                "required" => true,
                "filters" => array(
                    array("name" => "StringTrim")
                ),
                "validators" => array(
                    array(
                        "name" => "Date",
                        "options" => array(
                            "format" => DateUtils::MYSQL_DATETIME_FORMAT
                        )
                    )
                )
            )
        ));
        return $inputFilter;
    }
}