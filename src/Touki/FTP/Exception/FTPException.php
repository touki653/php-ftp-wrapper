<?php

namespace Touki\FTP\Exception;

/**
 * Base exception class for any exception thrown by FTP
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class FTPException extends \Exception
{
    /**
     * Overrides the default to String
     * @return string
     */
    public function __toString()
    {
        return sprintf('[FTP Error] %s', $this->getMessage());
    }
}
