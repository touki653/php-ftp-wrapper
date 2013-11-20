<?php

namespace Touki\FTP\Exception;

use RuntimeException;

/**
 * Exception thrown when no result were found while trying to find One
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class NoResultException extends RuntimeException implements FTPException
{
}
