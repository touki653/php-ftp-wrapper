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
     * @var Directory
     */
    protected $directory;

    /**
     * Constructor
     *
     * @param Directory $directory Directory to create
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
        $parts = explode('/', trim($this->directory->getRealpath(), '/'));
        $path  = '';

        foreach ($parts as $part) {
            $path = sprintf("%s/%s", $path, $part);

            if (null === $fetcher->findDirectoryByName($path) && !$wrapper->mkdir($path)) {
                throw new CreationException(sprintf("Could not create directory %s", $path));
            }
        }
    }
}
