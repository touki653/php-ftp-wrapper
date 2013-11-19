<?php

namespace Touki\FTP;

/**
 * Base interface for any FTP Command
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface CommandInterface
{
    /**
     * Executes a given command
     *
     * @param FTPWrapper           $wrapper Wrapper
     * @param FTPFilesystemManager $manager Manager
     *
     * @return mixed Command return
     */
    public function execute(FTPWrapper $wrapper, FTPFilesystemManager $manager);
}
