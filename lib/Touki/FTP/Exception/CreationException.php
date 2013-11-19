<?php

namespace Touki\FTP\Exception;

use RuntimeException;

/**
 * Exception thrown when an error occured while creating
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class CreationException extends RuntimeException implements FTPException
{
}
