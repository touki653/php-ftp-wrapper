<?php

namespace Touki\FTP\Exception;

use RuntimeException;

/**
 * Exception thrown when an error occured while parsing
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class ParseException extends RuntimeException implements FTPException
{
}
