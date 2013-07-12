<?php

namespace Touki\FTP\Exception;

/**
 * Exception to throw when the connection is already established
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ConnectionEstablishedException extends FTPException
{
    /**
     * {@inheritDoc}
     */
    public function __construct($message = "", $code = 0, \Exception $previous = NULL)
    {
        $this->message  = $message ?: "Connection is already established";
        $this->code     = $code;
        $this->previous = $previous;
    }
}
