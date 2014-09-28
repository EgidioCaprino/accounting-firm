<?php
namespace DatabaseTest\Model;

use DatabaseTest\Bootstrap;
use Utils\Date\DateUtils;
use Utils\File\FileUtils;

class FolderIteratorTest extends \PHPUnit_Framework_TestCase {
    public function testIteration() {
        $user = Bootstrap::getServiceManager()->get('Database\Model\User');
        $user->populate(array(
            "username" => "test_iterator_user",
            "email" => "test_iterator_user@egidiocaprino.it",
            "password" => hash("sha256", "qwerty123")
        ));
        $user->save();

        $rootFolder = Bootstrap::getServiceManager()->get('Database\Model\Folder');
        $rootFolder->populate(array("name" => "root_folder"));
        $rootFolder->save();

        $script = new \SplFileInfo(__FILE__);
        $fileInRoot = Bootstrap::getServiceManager()->get('Database\Model\File');
        $fileInRoot->populate(array(
            "id_folder" => $rootFolder->id_folder,
            "id_user" => $user->id_user,
            "title" => $script->getBasename(),
            "filename" => $script->getBasename(),
            "file_blob" => file_get_contents($script->getPathname()),
            "size" => $script->getSize(),
            "mime_type" => FileUtils::getMimeType($script),
            "upload_date" => date(DateUtils::MYSQL_DATETIME_FORMAT)
        ));
        $fileInRoot->save();

        $subFolder = Bootstrap::getServiceManager()->get('Database\Model\Folder');
        $subFolder->populate(array(
            "name" => "sub_folder",
            "id_parent" => $rootFolder->id_folder
        ));
        $subFolder->save();

        $fileInSub = Bootstrap::getServiceManager()->get('Database\Model\File');
        $fileInSub->populate(array(
            "id_folder" => $subFolder->id_folder,
            "id_user" => $user->id_user,
            "title" => $script->getBasename(),
            "filename" => $script->getBasename(),
            "file_blob" => file_get_contents($script->getPathname()),
            "size" => $script->getSize(),
            "mime_type" => FileUtils::getMimeType($script),
            "upload_date" => date(DateUtils::MYSQL_DATETIME_FORMAT)
        ));
        $fileInSub->save();

        $folderIterator = Bootstrap::getServiceManager()->get('Database\Model\FolderIterator');
        $folderIterator->setModel($rootFolder);
        foreach ($folderIterator as $fi1) {
            $this->assertInstanceOf('Database\Model\FolderIterator', $fi1);
            $model = $fi1->getModel();
            if ($fi1->isFile()) {
                $this->assertSame($fileInRoot->id_file, $model->id_file);
            } else {
                $this->assertSame($subFolder->id_folder, $model->id_folder);
                $iterations = 0;
                foreach ($fi1 as $fi2) {
                    $this->assertInstanceOf('Database\Model\FolderIterator', $fi2);
                    $model = $fi2->getModel();
                    $this->assertInstanceOf('Database\Model\File', $model);
                    $this->assertSame($fileInSub->id_file, $model->id_file);
                    ++$iterations;
                }
                $this->assertSame(1, $iterations);
            }
        }

        $fileInSub->delete();
        $subFolder->delete();
        $fileInRoot->delete();
        $rootFolder->delete();
        $user->delete();
    }
}