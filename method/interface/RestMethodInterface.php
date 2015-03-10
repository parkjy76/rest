<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestMethodInterface
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


interface RestMethodInterface
{
    /**
     * set
     * 
     * @access public
     * @param  string
     * @param  mixed
     * @return array
     */
    public function set( $url, $data );

    /**
     * read
     * 
     * @access public
     * @return string
     */
    public function read();

    /**
     * readParams
     * 
     * @access public
     * @return array
     */
    public function readParams();
}
