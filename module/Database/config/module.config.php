<?php
return array(
    "service_manager" => array(
        "factories" => array(
            'Database\Model\User' => 'Database\Model\Factory\UserFactory',
            'Database\Model\Folder' => 'Database\Model\Factory\FolderFactory',
            'Database\Dao\UserDao' => 'Database\Dao\Factory\UserDaoFactory',
            'Database\Dao\FolderDao' => 'Database\Dao\Factory\FolderDaoFactory',
            'Database\InputFilter\Factory\UserInputFilterFactory' => 'Database\InputFilter\Factory\UserInputFilterFactory'
        ),
        "shared" => array(
            'Database\Model\User' => false,
            'Database\Model\Folder' => false
        )
    )
);