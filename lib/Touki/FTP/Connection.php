<?php

namespace Touki\FTP;

use Touki\FTP\Exception\ConnectionUnestablishedException;

/**
 * Connection model
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Connection implements ConnectionInterface
{
    /**
     * FTP resource
     * @var resource
     */
    protected $stream;

    /**
     * Whether it is established
     * @var boolean
     */
    protected $connected = false;

    /**
     * {@inheritDoc}
     */
    public function getStream()
    {
        if (!$this->isConnected()) {
            throw new ConnectionUnestablishedException("Cannot get stream context, connection is not established");
        }

        return $this->stream;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * {@inheritDoc}
     */
    public function setConnected($connected)
    {
        $this->connected = $connected;
    }
}
