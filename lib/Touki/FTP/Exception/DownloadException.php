<?php

namespace Touki\FTP\Exception;

/**
 * Exception to throw when an error occured while downloading
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DownloadException extends FTPException
{
    /**
     * Overrides the default to String
     * @return string
     */
    public function __toString()
    {
        return sprintf('[Download Error] %s', $this->getMessage());
    }
}
