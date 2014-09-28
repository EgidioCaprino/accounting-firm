<?php
return array(
    "service_manager" => array(
        "factories" => array(
            'Database\Model\User' => 'Database\Model\Factory\UserFactory',
            'Database\Model\Folder' => 'Database\Model\Factory\FolderFactory',
            'Database\Model\File' => 'Database\Model\Factory\FileFactory',
            'Database\Model\FolderPermission' => 'Database\Model\Factory\FolderPermissionFactory',
            'Database\Dao\UserDao' => 'Database\Dao\Factory\UserDaoFactory',
            'Database\Dao\FolderDao' => 'Database\Dao\Factory\FolderDaoFactory',
            'Database\Dao\FileDao' => 'Database\Dao\Factory\FileDaoFactory',
            'Database\Dao\FolderPermissionDao' => 'Database\Dao\Factory\FolderPermissionDaoFactory',
            'Database\InputFilter\UserInputFilter' => 'Database\InputFilter\Factory\UserInputFilterFactory',
            'Database\InputFilter\FolderInputFilter' => 'Database\InputFilter\Factory\FolderInputFilterFactory',
            'Database\InputFilter\FileInputFilter' => 'Database\InputFilter\Factory\FileInputFilterFactory',
            'Database\InputFilter\FolderPermissionInputFilter' => 'Database\InputFilter\Factory\FolderPermissionInputFilterFactory',
            'Database\Model\FolderIterator' => 'Database\Model\Factory\FolderIteratorFactory'
        ),
        "shared" => array(
            'Database\Model\User' => false,
            'Database\Model\Folder' => false,
            'Database\Model\File' => false,
            'Database\Model\FolderPermission' => false,
            'Database\Model\FolderIterator' => false
        )
    )
);