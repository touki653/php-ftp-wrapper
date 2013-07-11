<?php

namespace Touki\FTP\Connection;

use Touki\FTP\ConnectionInterface;
use Touki\FTP\Exception\BadMethodCallException;
use Touki\FTP\Exception\ConnectionException;

/**
 * Standard connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Standard implements ConnectionInterface
{
    /**
     * FTP server address
     * @var string
     */
    protected $host;

    /**
     * Port
     * @var integer
     */
    protected $port;

    /**
     * Timeout
     * @var integer
     */
    protected $timeout;

    /**
     * Username
     * @var string
     */
    protected $username;

    /**
     * Password
     * @var string
     */
    protected $password;

    /**
     * FTP Stream
     * @var resource
     */
    protected $stream;

    /**
     * Wether stream is already open
     * @var boolean
     */
    protected $connected = false;

    /**
     * Constructor
     *
     * @param string  $host     The FTP server address
     * @param string  $username Username to login with
     * @param string  $password Password to login with
     * @param integer $port     Specify the port to connect to
     * @param integer $timeout  Specify the default timeout
     */
    public function __construct($host, $username = 'anonymous', $password = '', $port = 21, $timeout = 90)
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port     = $port;
        $this->timeout  = $timeout;
    }

    /**
     * Opens the connection
     *
     * @throws BadMethodCallException When connection is already running
     * @throws ConnectionException    When connection to server failed
     * @throws ConnectionException    When loging-in to server failed
     */
    public function open()
    {
        if ($this->isConnected()) {
            throw new BadMethodCallException("Connection is already established");
        }

        $stream = ftp_connect($this->host, $this->port, $this->timeout);

        if (false === $stream) {
            throw new ConnectionException(sprintf("Could not connect to server %s:%s", $this->host, $this->port));
        }

        if (!@ftp_login($stream, $this->username, $this->password)) {
            throw new ConnectionException(
                sprintf("Invalid combination of username (%s) and password (%s)", $this->username, $this->password)
            );
        }

        $this->connected = true;
        $this->stream    = $stream;
    }

    /**
     * Closes the connection
     *
     * @throws BadMethodCallException When connection is not established
     */
    public function close()
    {
        if (!$this->isConnected()) {
            throw new BadMethodCallException("Tried to close an unitialized connection");
        }

        ftp_close($this->stream);

        $this->connected = false;
    }

    /**
     * Returns whether the connection is active
     *
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * {@inheritDoc}
     *
     * @throws BadMethodCallException When connection is not established
     */
    public function getStream()
    {
        if (!$this->isConnected()) {
            throw new BadMethodCallException("Cannot get stream context. Connection is not established");
        }

        return $this->stream;
    }
}
