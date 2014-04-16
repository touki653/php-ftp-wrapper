<?php

namespace Touki\FTP\Model;

/**
 * Symlink is a file which has a target
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Symlink extends Filesystem
{
    /**
     * Target filesystem
     * @var string
     */
    protected $target;

    /**
     * Constructor
     *
     * @param string $realpath Realpath
     * @param string $target   Target path
     */
    public function __construct($realpath = null, $target = null)
    {
        parent::__construct($realpath);

        $this->target = $target;
    }

    /**
     * Get Target
     *
     * @return string Target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set Target
     *
     * @param string $target Target
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }
}
