<?php

namespace Touki\FTP;

/**
 * Base interface for any filesystem factory
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface FilesystemFactoryInterface
{
    /**
     * Builds a file from a given input line
     *
     * @param  string     $input Input string
     * @return Filesystem Newly created File object
     */
    public function build($input, $prefix = '');
}
