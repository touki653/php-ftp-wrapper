<?php

namespace Touki\FTP\Exception;

use RuntimeException;

/**
 * Exception thrown when an error occured while deleting
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DeletionException extends RuntimeException implements FTPException
{
}
