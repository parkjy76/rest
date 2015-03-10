<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestInterface
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


interface RestInterface
{
    /**
     * get
     * 
     * @access public
     * @param  mixed
     * @return mixed
     */
    public function get( $val );

    /**
     * post
     * 
     * @access public
     * @param  mixed
     * @return mixed
     */
    public function post( $val );

    /**
     * put
     * 
     * @access public
     * @param  mixed
     * @return mixed
     */
    public function put( $val );

    /**
     * delete
     * 
     * @access public
     * @param  mixed
     * @return mixed
     */
    public function delete( $val );
}
