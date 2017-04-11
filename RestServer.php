<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestServer
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

require_once('RestUtil.php');
require_once('RestMethod.php');


class RestServer
{
    /**
     * Constructor
     * 
     * @access public
     * @return void
     */
    public function __construct() {}

    /**
     * read
     * 
     * @access public
     * @param  RestMethod
     * @return string
     */
    public function read( RestMethod $restMethod )
    {
        return $restMethod->read();
    }

    /**
     * readParams
     *
     * @access public
     * @param  RestMethod
     * @return array
     */
    public function readParams( RestMethod $restMethod )
    {
        return $restMethod->readParams();
    }

    /**
     * response
     * 
     * @access public
     * @param  string $data
     * @param  array  $headers
     * @return bool
     */
    public function response( $data, $headers=array() )
    {
        foreach( $headers as $header ) header($header);

        if( strlen($data) ) echo $data;

        return TRUE;
    }

    /**
     * getMethod
     * 
     * @access public
     * @return string
     */
    public function getRequestMethod()
    {
        if( isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) ) return $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
        elseif( isset($_SERVER['REQUEST_METHOD']) ) return $_SERVER['REQUEST_METHOD'];

        return NULL;
    }

    /**
     * getContentType
     * 
     * @access public
     * @return mixed
     */
    public function getRequestContext()
    {
        static $ret;

        $type = $this->_getContentType();
        if( !strlen($type) ) $ret = NULL;
        else
        {
            $type = RestUtil::getMimeType($type);
            $type = RestUtil::getContextFromMime($type);

            // HTMLは許してない
            if( $type === RestUtil::CONTEXT_HTML ) $type = NULL;

            $ret = is_null($type) ? FALSE : $type;
        }

        if( $ret !== FALSE && $this->getRequestMethod() === 'GET' ) $ret = RestUtil::CONTEXT_QUERY;

        return $ret;
    }

    /**
     * getAccept
     * 
     * @access public
     * @param  boolean $altContentType [Optional]
     * @return mixed
     */
    public function getResponseContext( $altContentType=TRUE )
    {
        static $ret;

        $type = RestUtil::getServer('HTTP_ACCEPT');
        if( !strlen($type) ) $ret = NULL;
        else
        {
            if( $type === '*/*' )
            {
                if( $altContentType ) $type = $this->_getContentType();
                else $type = NULL;
            }

            if( !strlen($type) ) $ret = NULL;
            else
            {
                $type = RestUtil::getMimeType($type);
                $type = RestUtil::getContextFromMime($type);
                $ret = is_null($type) ? FALSE : $type;
            }
        }

        return $ret;
    }

    /**
     * getRemoteAddress
     * 
     * @access public
     * @return string
     */
    public function getRemoteAddress()
    {
        return RestUtil::getServer('REMOTE_ADDR');
    }

    /**
     * _getContentType
     * 
     * @access private
     * @return string
     */
    private function _getContentType()
    {
        $type = RestUtil::getServer('HTTP_CONTENT_TYPE');
        if( !strlen($type) ) $type = RestUtil::getServer('CONTENT_TYPE');

        return $type;
    }
}
