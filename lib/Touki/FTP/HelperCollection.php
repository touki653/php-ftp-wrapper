<?php

namespace Touki\FTP;

/**
 * This collection just acts like a basic array.
 * Made a class instead in order to modify its behaviour if needed
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class HelperCollection
{
    /**
     * Helpers
     * @var array
     */
    protected $helpers = array();

    /**
     * Set helper
     *
     * @param string $key   Helper key
     * @param mixed  $value Helper value
     */
    public function set($key, $value)
    {
        $this->helpers[$key] = $value;
    }

    /**
     * Get helper
     *
     * @param string $key Helper key
     *
     * @return mixed Helper value
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->helpers)) {
            return;
        }

        return $this->helpers[$key];
    }
}
