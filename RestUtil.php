<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestUtil
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


class RestUtil
{
    /**
     * REST 対応format定義
     * 
     * @access const
     * @var    string
     */
    const CONTEXT_JSON = 'json';
    const CONTEXT_XML = 'xml';
    const CONTEXT_QUERY = 'query';
    const CONTEXT_HTML = 'html';

    /**
     * _mime_context
     * 
     * @static
     * @access private
     * @var    array
     */
    private static $_mime_context = array(
                    'application/xml' => self::CONTEXT_XML,
                    'text/xml' => self::CONTEXT_XML,
                    'application/json' => self::CONTEXT_JSON,
                    'application/javascript' => self::CONTEXT_JSON,
                    'text/javascript' => self::CONTEXT_JSON,
                    'application/x-www-form-urlencoded' => self::CONTEXT_QUERY,
                    'multipart/form-data' => self::CONTEXT_QUERY,
                    'text/html' => self::CONTEXT_HTML,
                    'application/xhtml+xml' => self::CONTEXT_HTML,
                );

    /**
     * _context_mime
     * 
     * @static
     * @access private
     * @var    array
     */
    private static $_context_mime = array(
                    self::CONTEXT_XML => 'application/xml',
                    self::CONTEXT_JSON => 'application/json',
                    self::CONTEXT_QUERY => 'application/x-www-form-urlencoded',
                    self::CONTEXT_HTML => 'text/html',
                );

    /**
     * isAvailableMimeType
     * 
     * @static
     * @access public
     * @param  string $mime
     * @return bool
     */
    public static function isAvailableMimeType( $mime )
    {
        if( isset(self::$_mime_context[$mime]) ) return TRUE;

        return FALSE;
    }

    /**
     * isAvailableContextType
     * 
     * @static
     * @access public
     * @param  string $context
     * @return string
     */
    public static function isAvailableContextType( $context )
    {
        if( isset(self::$_context_mime[$context]) ) return TRUE;

        return FALSE;
    }

    /**
     * getContextFromMime
     * 
     * @static
     * @access public
     * @param  string $mime
     * @return string
     */
    public static function getContextFromMime( $mime )
    {
        if( self::isAvailableMimeType($mime) ) return self::$_mime_context[$mime];

        return NULL; 
    }

    /**
     * getMimeFromContext
     * 
     * @static
     * @access public
     * @param  string $context
     * @return string
     */
    public static function getMimeFromContext( $context )
    {
        if( self::isAvailableContextType($context) ) return self::$_context_mime[$context];

        return NULL; 
    }

    /**
     * getMimeType
     * 
     * @see text/html,application/xhtml+xml,application/xml;q=0.9
     * @static
     * @access public
     * @param  string $mime
     * @return bool
     */
    public static function getMimeType( $mime )
    {
        $mimeInfo = array();
        $mime = strtolower(str_replace(' ', '', $mime));
        $mimes = explode(',', $mime);

        foreach( $mimes as $value )
        {
            $quality = 1;

            $mimeExp = explode(';', $value);
            if( isset($mimeExp[1]) )
            {
                $mimeExp[1] = trim($mimeExp[1]);
                if( strpos($mimeExp[1], 'q=') === 0 ) $quality = substr($mimeExp[1], 2);
            }

            $mimeInfo[$mimeExp[0]] = $quality;
        }

        unset($mime, $mimes, $mimeExp);
        arsort($mimeInfo);
 
        foreach( $mimeInfo as $mime => $quality )
            if( $quality ) break;

        return $mime;
    }
}
