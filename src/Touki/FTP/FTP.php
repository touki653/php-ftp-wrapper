<?php

namespace Touki\FTP;

/**
 * Main class for FTP control
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTP
{
    /**
     * FTP Connection
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * Constructor
     *
     * @param ConnectionInterface $connection A ConnectionInterface instance
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Set Connection
     *
     * @param ConnectionInterface $connection A ConnectionInterface instance
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns a list of files in the given directory
     *
     * @link   http://php.net/ftp_nlist
     *
     * @param  string      $directory The directory to be listed
     * @return array|false An array of filenames from the specified directory,
     *                     FALSE on error
     */
    public function nlist($directory)
    {
        return ftp_nlist($this->connection->getStream(), $directory);
    }
}
