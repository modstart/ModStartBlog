<?php

namespace Chumper\Zipper\Repositories;

use Exception;
use ZipArchive;

class ZipRepository implements RepositoryInterface
{
    private $archive;

    
    public function __construct($filePath, $create = false, $archive = null)
    {
                if (!class_exists('ZipArchive')) {
            throw new Exception('Error: Your PHP version is not compiled with zip support');
        }
        $this->archive = $archive ? $archive : new ZipArchive();

        $res = $this->archive->open($filePath, ($create ? ZipArchive::CREATE : null));
        if ($res !== true) {
            throw new Exception("Error: Failed to open $filePath! Error: " . $this->getErrorMessage($res));
        }
    }

    
    public function addFile($pathToFile, $pathInArchive)
    {
        $this->archive->addFile($pathToFile, $pathInArchive);
    }

    
    public function addEmptyDir($dirName)
    {
        $this->archive->addEmptyDir($dirName);
    }

    
    public function addFromString($name, $content)
    {
        $this->archive->addFromString($name, $content);
    }

    
    public function removeFile($pathInArchive)
    {
        $this->archive->deleteName($pathInArchive);
    }

    
    public function getFileContent($pathInArchive)
    {
        return $this->archive->getFromName($pathInArchive);
    }

    
    public function getFileStream($pathInArchive)
    {
        return $this->archive->getStream($pathInArchive);
    }

    
    public function each($callback)
    {
        for ($i = 0; $i < $this->archive->numFiles; ++$i) {
                        $stats = $this->archive->statIndex($i);
            if ($stats['size'] === 0 && $stats['crc'] === 0) {
                continue;
            }
            call_user_func_array($callback, [
                 $this->archive->getNameIndex($i),
                 $this->archive->statIndex($i)
            ]);
        }
    }

    
    public function fileExists($fileInArchive)
    {
        return $this->archive->locateName($fileInArchive) !== false;
    }

    
    public function usePassword($password)
    {
        return $this->archive->setPassword($password);
    }

    
    public function getStatus()
    {
        return $this->archive->getStatusString();
    }

    
    public function close()
    {
        @$this->archive->close();
    }

    private function getErrorMessage($resultCode)
    {
        switch ($resultCode) {
            case ZipArchive::ER_EXISTS:
                return 'ZipArchive::ER_EXISTS - File already exists.';
            case ZipArchive::ER_INCONS:
                return 'ZipArchive::ER_INCONS - Zip archive inconsistent.';
            case ZipArchive::ER_MEMORY:
                return 'ZipArchive::ER_MEMORY - Malloc failure.';
            case ZipArchive::ER_NOENT:
                return 'ZipArchive::ER_NOENT - No such file.';
            case ZipArchive::ER_NOZIP:
                return 'ZipArchive::ER_NOZIP - Not a zip archive.';
            case ZipArchive::ER_OPEN:
                return 'ZipArchive::ER_OPEN - Can\'t open file.';
            case ZipArchive::ER_READ:
                return 'ZipArchive::ER_READ - Read error.';
            case ZipArchive::ER_SEEK:
                return 'ZipArchive::ER_SEEK - Seek error.';
            default:
                return "An unknown error [$resultCode] has occurred.";
        }
    }
}
