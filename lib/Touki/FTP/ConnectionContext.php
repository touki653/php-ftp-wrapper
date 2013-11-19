<?php

namespace Touki\FTP;

use Touki\FTP\ConnectionInterface;
use Touki\FTP\Exception\ConnectionException;
use Touki\FTP\Exception\ConnectionEstablishedException;
use Touki\FTP\Exception\ConnectionUnestablishedException;

/**
 * Standard connection
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ConnectionContext
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
     * Constructor
     *
     * @param string  $host     The FTP server address
     * @param string  $username Username to login with
     * @param string  $password Password to login with
     * @param integer $port     Specify the port to connect to
     * @param integer $timeout  Specify the default timeout
     */
    public function __construct($host, $username = 'anonymous', $password = 'guest', $port = 21, $timeout = 90)
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port     = $port;
        $this->timeout  = $timeout;
    }

    /**
     * Get Host
     *
     * @return string FTP server address
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set Host
     *
     * @param string $host FTP server address
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Get Username
     *
     * @return string Username to login with
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set Username
     *
     * @param string $username Username to login with
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get Password
     *
     * @return string Password to login with
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Password
     *
     * @param string $password Password to login with
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get Port
     *
     * @return integer Port to connect to
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set Port
     *
     * @param integer $port Port to connect to
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Get Timeout
     *
     * @return integer Default timeout
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set Timeout
     *
     * @param integer $timeout Default timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
