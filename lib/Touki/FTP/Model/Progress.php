<?php

namespace Touki\FTP\Model;

/**
 * Progress model
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Progress
{
    /**
     * Max size
     * @var float
     */
    protected $maxSize;

    /**
     * Current size
     * @var float
     */
    protected $currentSize;

    /**
     * Constructor
     *
     * @param integer $maxSize Max Size
     */
    public function __construct($maxSize)
    {
        $this->setMaxSize($maxSize);
    }

    /**
     * Get MaxSize
     *
     * @return float Max size
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }
    
    /**
     * Set MaxSize
     *
     * @param float $maxSize Max size
     */
    public function setMaxSize($maxSize)
    {
        if (!$maxSize) {
            throw new \InvalidArgumentException(sprintf("Invalid Max Size given %s", $maxSize));
        }

        $this->maxSize = $maxSize;

        return $this;
    }

    /**
     * Get CurrentSize
     *
     * @return float Current Size
     */
    public function getCurrentSize()
    {
        return $this->currentSize;
    }
    
    /**
     * Set CurrentSize
     *
     * @param float $currentSize Current Size
     */
    public function setCurrentSize($currentSize)
    {
        $this->currentSize = $currentSize;
    
        return $this;
    }

    /**
     * Get Percentage
     *
     * @return float Percentage
     */
    public function getPercentage()
    {
        return round(($this->currentSize * 100) / $this->maxSize, 2);
    }

    /**
     * Get size left
     *
     * @return float
     */
    public function getLeft()
    {
        return $this->maxSize - $this->currentSize;
    }
}
