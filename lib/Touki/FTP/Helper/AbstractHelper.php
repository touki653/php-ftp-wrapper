<?php

namespace Touki\FTP\Helper;

use Touki\FTP\Commander;

/**
 * Base implementation for any helper
 * An helper methodify calls to commands
 * 
 * @author Touki <g.vincendon@vithemis.com>
 */
abstract class AbstractHelper
{
    /**
     * Commander
     * @var Commander
     */
    protected $commander;

    /**
     * Constructor
     *
     * @param Commander $commander Commander
     */
    public function __construct(Commander $commander)
    {
        $this->commander = $commander;
    }
}
