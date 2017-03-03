<?php

namespace App\Helpers;

class File {
    public static function getFileExtension($url)
    {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $ext = preg_replace('/(.)\?.+/', '$1', $ext);
        return $ext;
    }

    public static function setNewName($fileUrl, $newFileName) {
        $fileInfo = pathinfo($fileUrl);
        $resultFile = $fileInfo['dirname'].DIRECTORY_SEPARATOR.$newFileName.'.'.$fileInfo['extension'];
        return $resultFile;
    }
}