<?php

namespace Touki\FTP\Exception;

/**
 * Exception to throw when an error occured while uploading
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class UploadException extends FTPException
{
    /**
     * Overrides the default to String
     * @return string
     */
    public function __toString()
    {
        return sprintf('[Upload Error] %s', $this->getMessage());
    }
}
