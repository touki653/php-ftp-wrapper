<?php

namespace Touki\FTP;

use Touki\FTP\Exception\ConnectionUnestablishedException;

/**
 * Connection DTO
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
     * Connection Context
     * @var ConnectionContext
     */
    protected $context;

    /**
     * {@inheritDoc}
     */
    public function getStream()
    {
        if (false === $this->isConnected()) {
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

        return $this;
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

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext(ConnectionContext $context = null)
    {
        $this->context = $context;

        return $this;
    }
}
