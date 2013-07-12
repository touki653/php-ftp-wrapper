<?php

namespace Touki\FTP\Exception;

/**
 * Exception to throw when the connection is not established
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ConnectionUnestablishedException extends FTPException
{
    /**
     * {@inheritDoc}
     */
    public function __construct($message = "", $code = 0, \Exception $previous = NULL)
    {
        $this->message  = $message ?: "Connection is not established";
        $this->code     = $code;
        $this->previous = $previous;
    }
}
