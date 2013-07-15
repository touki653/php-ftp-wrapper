<?php

namespace Touki\FTP;

use Touki\FTP\Exception\UploadException;
use Touki\FTP\Exception\DownloadException;
use Touki\FTP\Exception\DirectoryException;

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
     * @return boolean TRUE when upload succeeded
     *
     * @throws UploadException When an error occured
     */
    public function upload($remoteFile, $local, array $options = array());

    /**
     * Downloads a remote file into a resource or file
     *
     * @param  mixed   $local      Local Resource or file
     * @param  string  $remoteFile Remote file path
     * @param  array   $options    Options
     * @return boolean TRUE when download succeeded
     *
     * @throws DownloadException When an error occured
     */
    public function download($local, $remoteFile, array $options = array());

    /**
     * Checks if a remote file exists
     *
     * @param  string  $remoteFile Remote file path
     * @return boolean TRUE when it exists, FALSE when it doesn't
     */
    public function fileExists($remoteFile);

    /**
     * Checks if a directory exists
     *
     * @param  string  $directory Directory name
     * @return boolean TRUE if it exists, FALSE when it doesn't
     */
    public function directoryExists($directory);

    /**
     * Changes the current working directory
     *
     * @param  string  $directory Target directory
     * @return boolean TRUE on success
     *
     * @throws DirectoryException When an error occured
     */
    public function chdir($directory);

    /**
     * Changes to the parent directory
     *
     * @return boolean TRUE on success
     */
    public function cdup();

    /**
     * Creates a new directory
     *
     * @param  string  $directory The name of the directory to create
     * @return boolean TRUE on success
     */
    public function mkdir($directory);
}
