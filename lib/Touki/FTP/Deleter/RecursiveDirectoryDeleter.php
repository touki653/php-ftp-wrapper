<?php

namespace Touki\FTP\Deleter;

use Touki\FTP\FilesystemFetcher;
use Touki\FTP\CommandInterface;
use Touki\FTP\FTPWrapper;
use Touki\FTP\Model\Directory;
use Touki\FTP\Exception\DeletionException;

/**
 * Recursive Directory Deleter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class RecursiveDirectoryDeleter implements CommandInterface
{
    /**
     * Directory to delete
     * @var Directory
     */
    protected $directory;

    /**
     * Constructor
     *
     * @param Directory $directory Directory to delete
     */
    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(FTPWrapper $wrapper, FilesystemFetcher $fetcher)
    {
        if (false === $this->delete($this->directory, $wrapper, $fetcher)) {
            throw new DeletionException(sprintf("Couldn't delete directory %s", $this->directory->getRealpath()));
        }
    }

    /**
     * Processes the deletion
     *
     * @param Directory         $directory Directory to delete
     * @param FTPWrapper        $wrapper   FTP Wrapper
     * @param FilesystemFetcher $fetcher   Filesystem fetcher
     */
    private function delete(Directory $directory, FTPWrapper $wrapper, FilesystemFetcher $fetcher)
    {
        $this->deleteFiles($directory, $wrapper, $fetcher);
        $this->deleteDirectories($directory, $wrapper, $fetcher);

        return !!$wrapper->rmdir($directory->getRealpath());
    }

    /**
     * Deletes files in a directory
     *
     * @param Directory         $directory Directory to delete
     * @param FTPWrapper        $wrapper   FTP Wrapper
     * @param FilesystemFetcher $fetcher   Filesystem fetcher
     */
    private function deleteFiles(Directory $directory, FTPWrapper $wrapper, FilesystemFetcher $fetcher)
    {
        foreach ($fetcher->findFiles($directory) as $file) {
            if (false === $wrapper->delete($file->getRealpath())) {
                throw new DeletionException(sprintf("Couldn't delete file %s", $file->getRealpath()));
            }
        }
    }

    /**
     * Deletes directories in a directory
     *
     * @param Directory         $directory Directory to delete
     * @param FTPWrapper        $wrapper   FTP Wrapper
     * @param FilesystemFetcher $fetcher   Filesystem fetcher
     */
    private function deleteDirectories(Directory $directory, FTPWrapper $wrapper, FilesystemFetcher $fetcher)
    {
        foreach ($fetcher->findDirectories($directory) as $dir) {
            if (false === $this->delete($dir, $wrapper, $fetcher)) {
                throw new DeletionException(sprintf("Couldn't delete directory %s", $dir->getRealpath()));
            }
        }
    }
}
