<?php
namespace Utils\File;

class FileUtils {
    public static function getMimeType($file) {
        if ($file instanceof \DirectoryIterator) {
            $file = $file->getPathname();
        }
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file);
        return $mimeType;
    }
} 