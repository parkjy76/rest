<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestMethod_POST
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


class RestMethod_POST implements RestMethodInterface
{
    /**
     * METHOD
     * 
     * @access const
     * @var    string
     */
    const METHOD = 'POST';


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
                     CURLOPT_POST => TRUE,
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
        $data = file_get_contents("php://input");
        if( !strlen($data) ) $data = http_build_query($_POST);

        return $data;
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
