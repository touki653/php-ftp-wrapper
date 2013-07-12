<?php

namespace Touki\FTP\Exception;

/**
 * Exception to throw when an error occured while connecting
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ConnectionException extends FTPException
{
    /**
     * Overrides the default to String
     * @return string
     */
    public function __toString()
    {
        return sprintf('[Connection Error] %s', $this->getMessage());
    }
}
