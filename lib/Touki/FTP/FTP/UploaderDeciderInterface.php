<?php

namespace Touki\FTP\FTP;

/**
 * Interface class to decide which uploader to use
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface UploaderDeciderInterface
{
    /**
     * Decides which uploader to use
     *
     * @param  string            $local   Local file or resource
     * @param  array             $options Options
     * @return UploaderInterface An Uploader
     */
    public function decide($local, array $options = array());
}
