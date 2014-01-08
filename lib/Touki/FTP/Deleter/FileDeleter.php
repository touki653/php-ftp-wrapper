<?php

namespace Touki\FTP\Deleter;

use Touki\FTP\Model\File;
use Touki\FTP\FTPWrapper;
use Touki\FTP\FilesystemFetcher;
use Touki\FTP\CommandInterface;
use Touki\FTP\Exception\CreationException;

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
        if (null === $fetcher->findFileByName($this->file->getRealpath())) {
            return;
        }

        
    }
}
