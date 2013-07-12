<?php

namespace Touki\FTP;

use Touki\FTP\Exception\UploadException;

/**
 * Base FTP Interface
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface FTPInterface
{
    /**
     * Uploads a local resource or file
     *
     * @param  string  $remoteFile Remote file path
     * @param  mixed   $local      Local resource or file
     * @param  array   $options    Options
     * @return boolean TRUE when everything went fine
     *
     * @throws UploadException When an error occured
     */
    public function upload($remoteFile, $local, array $options = array());

    /**
     * Checks if a remote file exists
     *
     * @param  string  $remoteFile Remote file path
     * @return boolean TRUE when it exists, FALSE when it doesn't
     */
    public function exists($remoteFile);
}
