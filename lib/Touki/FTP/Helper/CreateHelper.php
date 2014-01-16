<?php

namespace Touki\FTP\Helper;

use Touki\FTP\Creator;

/**
 * Create helper methodify ->create() commands
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class CreateHelper extends AbstractHelper
{
    /**
     * Creates a directory, recursively
     *
     * @param Directory $directory Directory to create
     */
    public function recursiveDirectory(Directory $directory)
    {
        return $this->commander->execute(new Creator\RecursiveDirectoryCreator($directory));
    }
}
