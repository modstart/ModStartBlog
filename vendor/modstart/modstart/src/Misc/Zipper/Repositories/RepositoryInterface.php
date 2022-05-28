<?php

namespace Chumper\Zipper\Repositories;


interface RepositoryInterface
{
    
    public function __construct($filePath, $new = false, $archiveImplementation = null);

    
    public function addFile($pathToFile, $pathInArchive);

    
    public function addFromString($name, $content);

    
    public function addEmptyDir($dirName);

    
    public function removeFile($pathInArchive);

    
    public function getFileContent($pathInArchive);

    
    public function getFileStream($pathInArchive);

    
    public function each($callback);

    
    public function fileExists($fileInArchive);

    
    public function usePassword($password);

    
    public function getStatus();

    
    public function close();
}
