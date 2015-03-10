<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestMethod
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 *  
 * PHP 5.3 Later
 * 
 * @category   common
 * @package    lib
 * @author     Junyong Park
 * @copyright  2012
 * @version    SVN: $Id:$
 */


class RestMethod
{
    /**
     * $_available
     * 
     * @static
     * @access protected
     * @var    array
     */
    protected static $_available = array('GET','POST','PUT','DELETE');

    /**
     * _method
     * 
     * @access protected
     * @var    RestMethodInterface
     */
    protected $_method = NULL;


    /**
     * Constructor
     * 
     * @access public
     * @return void
     */
    public function __construct( RestMethodInterface $method )
    {
        $this->_method = $method;
    }

    /**
     * isAvailable
     * 
     * @static
     * @access public
     * @param  string $method
     * @return bool
     */
    public static function isAvailable( $method )
    {
        return in_array($method, static::$_available);
    }

    /**
     * __call
     * 
     * @access public
     * @param  string $method
     * @param  array $params
     * @return mixed
     */
    public function __call( $method, $params )
    {
        if( method_exists($this->_method, $method) )
            return call_user_func_array(array($this->_method, $method), $params);
    }
}
