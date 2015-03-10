<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestMethod_GET
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 *  
 * PHP 5 Later
 * 
 * @category   common
 * @package    lib
 * @author     Junyong Park
 * @copyright  2012
 * @version    SVN: $Id:$
 */

require_once('interface/RestMethodInterface.php');


class RestMethod_GET implements RestMethodInterface
{
    /**
     * METHOD
     * 
     * @access const
     * @var    string
     */
    const METHOD = 'GET';


    /**
     * set
     * 
     * @access public
     * @param  string
     * @param  mixed
     * @return array
     */
    public function set( $url, $data )
    {
        if( is_array($data) ) $data = http_build_query($data);
        if( strlen($data) ) $url .= '?'.$data;

        return array(CURLOPT_URL => $url,
                     CURLOPT_HTTPGET => TRUE);
    }

    /**
     * read
     * 
     * @access public
     * @return string
     */
    public function read()
    {
        return http_build_query($this->readParams());
    }

    /**
     * readParams
     * 
     * @access public
     * @return array
     */
    public function readParams()
    {
        return $_GET;
    }
}
