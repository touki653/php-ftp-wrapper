<?php

namespace Touki\FTP;

/**
 * Connection interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface ConnectionInterface
{
    /**
     * Returns the connection stream
     *
     * @return resource FTP Connection stream
     */
    public function getStream();

    /**
     * Tells wether the connection is established
     *
     * @return boolean TRUE if connected, FALSE if not
     */
    public function isConnected();
}
