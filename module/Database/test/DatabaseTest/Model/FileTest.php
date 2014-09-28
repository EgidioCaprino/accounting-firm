<?php
namespace DatabaseTest\Model;

use DatabaseTest\Bootstrap;
use Utils\Date\DateUtils;
use Utils\File\FileUtils;

class FileTest extends \PHPUnit_Framework_TestCase {
    public function testInsertValidData() {
        $user = Bootstrap::getServiceManager()->get('Database\Model\User');
        $user->populate(array(
            "username" => "user_to_test_file",
            "email" => "file.test@egidiocaprino.it",
            "password" => hash("sha256", "qwerty123")
        ));
        $user->save();

        $folder = Bootstrap::getServiceManager()->get('Database\Model\Folder');
        $folder->populate(array("name" => "test_folder"));
        $folder->save();

        $script = new \SplFileInfo(__FILE__);
        $file = Bootstrap::getServiceManager()->get('Database\Model\File');
        $file->populate(array(
            "id_folder" => $folder->id_folder,
            "id_user" => $user->id_user,
            "title" => $script->getBasename(),
            "filename" => $script->getBasename(),
            "file_blob" => file_get_contents($script->getPathname()),
            "size" => $script->getSize(),
            "mime_type" => FileUtils::getMimeType($script),
            "upload_date" => date(DateUtils::MYSQL_DATETIME_FORMAT)
        ));
        $file->save();

        $dao = Bootstrap::getServiceManager()->get('Database\Dao\FileDao');
        $fetchedFile = $dao->findById($file->id_file);
        $this->assertNotNull($fetchedFile);
        $this->assertSame($file->id_folder, $fetchedFile->id_folder);
        $this->assertSame($file->id_user, $fetchedFile->id_user);
        $this->assertSame($file->title, $fetchedFile->title);
        $this->assertSame($file->filename, $fetchedFile->filename);
        $this->assertSame($file->file_blob, $fetchedFile->file_blob);
        $this->assertSame($file->size, $fetchedFile->size);
        $this->assertSame($file->mime_type, $fetchedFile->mime_type);
        $this->assertSame($file->upload_date, $fetchedFile->upload_date);

        $file->delete();
        $folder->delete();
        $user->delete();

        $deletedFile = $dao->findById($file->id_file);
        $this->assertNull($deletedFile);
    }
}