<?php

namespace Chumper\Zipper;

use Chumper\Zipper\Repositories\RepositoryInterface;
use Exception;
use Illuminate\Filesystem\Filesystem;


class Zipper
{
    
    const WHITELIST = 1;

    
    const BLACKLIST = 2;

    
    const EXACT_MATCH = 4;

    
    private $currentFolder = '';

    
    private $file;

    
    private $repository;

    
    private $filePath;

    
    public function __construct(Filesystem $fs = null)
    {
        $this->file = $fs ? $fs : new Filesystem();
    }

    
    public function make($pathToFile, $type = 'zip')
    {
        $new = $this->createArchiveFile($pathToFile);

        $objectOrName = $type;
        if (is_string($type)) {
            $objectOrName = 'Chumper\Zipper\Repositories\\' . ucwords($type) . 'Repository';
        }

        if (!is_subclass_of($objectOrName, 'Chumper\Zipper\Repositories\RepositoryInterface')) {
            throw new \InvalidArgumentException("Class for '{$objectOrName}' must implement RepositoryInterface interface");
        }

        try {
            if (is_string($objectOrName)) {
                $this->repository = new $objectOrName($pathToFile, $new);
            } else {
                $this->repository = $type;
            }
        } catch(Exception $e) {
            throw $e;
        }

        $this->filePath = $pathToFile;

        return $this;
    }

    
    public function zip($pathToFile)
    {
        $this->make($pathToFile);

        return $this;
    }

    
    public function phar($pathToFile)
    {
        $this->make($pathToFile, 'phar');

        return $this;
    }

    
    public function rar($pathToFile)
    {
        $this->make($pathToFile, 'rar');

        return $this;
    }

    
    public function extractTo($path, array $files = [], $methodFlags = self::BLACKLIST)
    {
        if (!$this->file->exists($path) && !$this->file->makeDirectory($path, 0755, true)) {
            throw new \RuntimeException('Failed to create folder');
        }

        if ($methodFlags & self::EXACT_MATCH) {
            $matchingMethod = function ($haystack) use ($files) {
                return in_array($haystack, $files, true);
            };
        } else {
            $matchingMethod = function ($haystack) use ($files) {
                return starts_with($haystack, $files);
            };
        }

        if ($methodFlags & self::WHITELIST) {
            $this->extractFilesInternal($path, $matchingMethod);
        } else {
                        $this->extractFilesInternal($path, function ($filename) use ($matchingMethod) {
                return !$matchingMethod($filename);
            });
        }
    }

    
    public function extractMatchingRegex($extractToPath, $regex)
    {
        if (empty($regex)) {
            throw new \InvalidArgumentException('Missing pass valid regex parameter');
        }

        $this->extractFilesInternal($extractToPath, function ($filename) use ($regex) {
            $match = preg_match($regex, $filename);
            if ($match === 1) {
                return true;
            } elseif ($match === false) {
                                                                throw new \RuntimeException("regular expression match on '$filename' failed with error. Please check if pattern is valid regular expression.");
            }

            return false;
        });
    }

    
    public function getFileContent($filePath)
    {
        if ($this->repository->fileExists($filePath) === false) {
            throw new Exception(sprintf('The file "%s" cannot be found', $filePath));
        }
        return $this->repository->getFileContent($filePath);
    }

    
    public function add($pathToAdd, $fileName = null)
    {
        if (is_array($pathToAdd)) {
            foreach ($pathToAdd as $key=>$dir) {
                if (!is_int($key)) {
                    $this->add($dir, $key); }
                else {
                    $this->add($dir);
                }
            }
        } elseif ($this->file->isFile($pathToAdd)) {
            if ($fileName) {
                $this->addFile($pathToAdd, $fileName);
            } else {
                $this->addFile($pathToAdd);
            }
        } else {
            $this->addDir($pathToAdd);
        }

        return $this;
    }

    
    public function addEmptyDir($dirName)
    {
        $this->repository->addEmptyDir($dirName);

        return $this;
    }

    
    public function addString($filename, $content)
    {
        $this->addFromString($filename, $content);

        return $this;
    }

    
    public function getStatus()
    {
        return $this->repository->getStatus();
    }

    
    public function remove($fileToRemove)
    {
        if (is_array($fileToRemove)) {
            $self = $this;
            $this->repository->each(function ($file) use ($fileToRemove, $self) {
                if (starts_with($file, $fileToRemove)) {
                    $self->getRepository()->removeFile($file);
                }
            });
        } else {
            $this->repository->removeFile($fileToRemove);
        }

        return $this;
    }

    
    public function getFilePath()
    {
        return $this->filePath;
    }

    
    public function usePassword($password)
    {
        return $this->repository->usePassword($password);
    }

    
    public function close()
    {
        if (null !== $this->repository) {
            $this->repository->close();
        }
        $this->filePath = '';
    }

    
    public function folder($path)
    {
        $this->currentFolder = $path;

        return $this;
    }

    
    public function home()
    {
        $this->currentFolder = '';

        return $this;
    }

    
    public function delete()
    {
        if (null !== $this->repository) {
            $this->repository->close();
        }

        $this->file->delete($this->filePath);
        $this->filePath = '';
    }

    
    public function getArchiveType()
    {
        return get_class($this->repository);
    }

    
    public function getCurrentFolderPath()
    {
        return $this->currentFolder;
    }

    
    public function contains($fileInArchive)
    {
        return $this->repository->fileExists($fileInArchive);
    }

    
    public function getRepository()
    {
        return $this->repository;
    }

    
    public function getFileHandler()
    {
        return $this->file;
    }

    
    public function getInternalPath()
    {
        return empty($this->currentFolder) ? '' : $this->currentFolder.'/';
    }

    
    public function listFiles($regexFilter = null)
    {
        $filesList = [];
        if ($regexFilter) {
            $filter = function ($file) use (&$filesList, $regexFilter) {
                                set_error_handler(function () {
                });
                $match = preg_match($regexFilter, $file);
                restore_error_handler();

                if ($match === 1) {
                    $filesList[] = $file;
                } elseif ($match === false) {
                    throw new \RuntimeException("regular expression match on '$file' failed with error. Please check if pattern is valid regular expression.");
                }
            };
        } else {
            $filter = function ($file) use (&$filesList) {
                $filesList[] = $file;
            };
        }
        $this->repository->each($filter);

        return $filesList;
    }

    private function getCurrentFolderWithTrailingSlash()
    {
        if (empty($this->currentFolder)) {
            return '';
        }

        $lastChar = mb_substr($this->currentFolder, -1);
        if ($lastChar !== '/' || $lastChar !== '\\') {
            return $this->currentFolder.'/';
        }

        return $this->currentFolder;
    }

    
    
    private function createArchiveFile($pathToZip)
    {
        if (!$this->file->exists($pathToZip)) {
            $dirname = dirname($pathToZip);
            if (!$this->file->exists($dirname) && !$this->file->makeDirectory($dirname, 0755, true)) {
                throw new \RuntimeException('Failed to create folder');
            } elseif (!$this->file->isWritable($dirname)) {
                throw new Exception(sprintf('The path "%s" is not writeable', $pathToZip));
            }

            return true;
        }

        return false;
    }

    
    private function addDir($pathToDir)
    {
                foreach ($this->file->files($pathToDir) as $file) {
            $this->addFile($pathToDir.'/'.basename($file));
        }

                foreach ($this->file->directories($pathToDir) as $dir) {
            $old_folder = $this->currentFolder;
            $this->currentFolder = empty($this->currentFolder) ? basename($dir) : $this->currentFolder.'/'.basename($dir);
            $this->addDir($pathToDir.'/'.basename($dir));
            $this->currentFolder = $old_folder;
        }
    }

    
    private function addFile($pathToAdd, $fileName = null)
    {
        if (!$fileName) {
            $info = pathinfo($pathToAdd);
            $fileName = isset($info['extension']) ?
                $info['filename'].'.'.$info['extension'] :
                $info['filename'];
        }

        $this->repository->addFile($pathToAdd, $this->getInternalPath().$fileName);
    }

    
    private function addFromString($filename, $content)
    {
        $this->repository->addFromString($this->getInternalPath().$filename, $content);
    }

    private function extractFilesInternal($path, callable $matchingMethod)
    {
        $self = $this;
        $this->repository->each(function ($fileName) use ($path, $matchingMethod, $self) {
            $currentPath = $self->getCurrentFolderWithTrailingSlash();
            if (!empty($currentPath) && !starts_with($fileName, $currentPath)) {
                return;
            }

            $filename = str_replace($self->getInternalPath(), '', $fileName);
            if ($matchingMethod($filename)) {
                $self->extractOneFileInternal($fileName, $path);
            }
        });
    }

    
    private function extractOneFileInternal($fileName, $path)
    {
        $tmpPath = str_replace($this->getInternalPath(), '', $fileName);

                if (strpos($fileName, '../') !== false || strpos($fileName, '..\\') !== false) {
            throw new \RuntimeException('Special characters found within filenames');
        }

                $dir = pathinfo($path.DIRECTORY_SEPARATOR.$tmpPath, PATHINFO_DIRNAME);
        if (!$this->file->exists($dir) && !$this->file->makeDirectory($dir, 0755, true, true)) {
            throw new \RuntimeException('Failed to create folders');
        }

        $toPath = $path.DIRECTORY_SEPARATOR.$tmpPath;
        $fileStream = $this->getRepository()->getFileStream($fileName);
        $this->getFileHandler()->put($toPath, $fileStream);
    }
}
