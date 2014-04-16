<?php

namespace Touki\FTP\Deleter;

use Touki\FTP\Model\File;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FilesystemFetcher;
use Touki\FTP\CommandInterface;
use Touki\FTP\Exception\DeletionException;
use Touki\FTP\Exception\NoResultException;

/**
 * File deleter
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FileDeleter implements CommandInterface
{
    /**
     * File
     * @var File
     */
    protected $file;

    /**
     * Constructor
     *
     * @param File $file File to delete
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(FTPWrapper $wrapper, FilesystemFetcher $fetcher)
    {
        try {
            $fetcher->findFileByName($this->file->getRealpath());
        } catch (NoResultException $e) {
            throw new DeletionException(
                sprintf("Cannot delete file %s as it doesn't exist", $this->file->getRealpath()),
                $e->getCode(),
                $e
            );
        }

        if (false === $wrapper->delete($this->file->getRealpath())) {
            throw new DeletionException(sprintf("Couldn't delete file %s", $this->file->getRealpath()));
        }
    }
}
