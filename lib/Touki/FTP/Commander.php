<?php

namespace Touki\FTP;

/**
 * Manager class for calling FTP commands
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Commander
{
    /**
     * FTP Wrapper
     * @var FTPWrapper
     */
    protected $wrapper;

    /**
     * Filesystem Fetcher
     * @var FilesystemFetcher
     */
    protected $fetcher;

    /**
     * Constructor
     *
     * @param FTPWrapper        $wrapper FTP Wrapper
     * @param FilesystemFetcher $fetcher Fetcher
     */
    public function __construct(FTPWrapper $wrapper, FilesystemFetcher $fetcher)
    {
        $this->wrapper = $wrapper;
        $this->fetcher = $fetcher;
    }

    /**
     * Executes a command
     *
     * @param CommandInterface $command Command to execute
     *
     * @return mixed Command return
     */
    public function execute(CommandInterface $command)
    {
        return $command->execute($this->wrapper, $this->fetcher);
    }
}
