<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestMethod_PUT
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


class RestMethod_PUT implements RestMethodInterface
{
    /**
     * METHOD
     * 
     * @access const
     * @var    string
     */
    const METHOD = 'PUT';


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

        return array(CURLOPT_URL => $url,
                     CURLOPT_CUSTOMREQUEST => self::METHOD,
                     CURLOPT_POSTFIELDS => $data);
    }

    /**
     * read
     * 
     * @access public
     * @return string
     */
    public function read()
    {
        return file_get_contents("php://input");
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
