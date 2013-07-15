<?php

namespace Touki\FTP\Exception;

/**
 * Exception to throw when an error occured while doing directory manipulation
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DirectoryException extends FTPException
{
    /**
     * Overrides the default to String
     * @return string
     */
    public function __toString()
    {
        return sprintf('[Directory Error] %s', $this->getMessage());
    }
}
