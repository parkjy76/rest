<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * RestClient
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


class RestClient
{
    /**
     * CONNECT_TIMEOUT
     * 
     * @access  const
     * @var     unsigned integer
     */
    const CONNECT_TIMEOUT = 10;

    /**
     * EXEC_TIMEOUT
     * 
     * @access  const
     * @var     unsigned integer
     */
    const EXEC_TIMEOUT = 30;

    /**
     * _curlOptions
     * 
     * @access protected
     * @var    array
     */
    protected $_curlOptions = array();

    /**
     * _curlResource
     * 
     * @access protected
     * @var    resource
     */
    protected $_curlResource = NULL;

    /**
     * _result
     * 
     * @access protected
     * @var    string
     */
    protected $_result;

    /**
     * _headers
     * 
     * @access protected
     * @var    array
     */
    protected $_headers;


    /**
     * Constructor
     * 
     * @access public
     * @return void
     * @throws RuntimeException
     */
    public function __construct()
    {
        if( !extension_loaded('curl') )
            throw new RuntimeException('cURL extension has to be loaded to use '. __CLASS__);

        $this->init();
    }

    /**
     * init
     * 
     * @access public
     * @return bool
     */
    public function init()
    {
        $this->_curlOptions = array(
                            CURLOPT_CONNECTTIMEOUT  => self::CONNECT_TIMEOUT,
                            CURLOPT_TIMEOUT         => self::EXEC_TIMEOUT,
                            CURLOPT_RETURNTRANSFER  => TRUE,
                            CURLOPT_HEADER          => FALSE,
                            CURLOPT_FRESH_CONNECT   => FALSE,
                            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_NONE,
                        );

        $this->_result = NULL;
        $this->_headers = array();

        return TRUE;
    }

    /**
     * open
     * 
     * open(initialize) curl session
     * 
     * @access public
     * @return bool
     * @throws Exception
     */
    public function open()
    {
        if( is_resource($this->_curlResource) ) $this->close();

        if( !$this->_curlResource = curl_init() )
            throw new ErrorException('Failed to initialize a cURL session');

        $this->setCurlOption(CURLOPT_SSL_VERIFYPEER, FALSE);
        $this->setCurlOption(CURLOPT_SSL_VERIFYHOST, 0);

        return TRUE;
    }

    /**
     * write
     * 
     * @access public
     * @param  string $method
     * @param  string $url
     * @param  mixed $data [Optional]
     * @return array
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws ErrorException
     */
    public function write( $method, $url, $data='' )
    {
        if( !is_resource($this->_curlResource) )
            throw new LogicException('cURL session is not initialized. Please connect');

        if( !(is_null($data) || is_scalar($data) || is_array($data)) )
            throw new InvalidArgumentException('Invalid data format');

        // set url, data
        $this->_setData($method, $url, $data);

        // set header
        $this->setCurlOption(CURLOPT_HTTPHEADER, $this->_headers);

        // set options
        if( !curl_setopt_array($this->_curlResource, $this->_curlOptions) )
            throw new InvalidArgumentException('cURL option could not be successfully set');

        // exec
        if( !isset($this->_curlOptions[CURLOPT_RETURNTRANSFER]) || !$this->_curlOptions[CURLOPT_RETURNTRANSFER] )
        {
            ob_start();
            curl_exec($this->_curlResource);
            $this->_result = ob_get_contents();
            ob_end_clean();
        }
        else $this->_result = curl_exec($this->_curlResource);

        // check error
        if( curl_errno($this->_curlResource) )
            throw new ErrorException(curl_error($this->_curlResource) . '(' . curl_errno($this->_curlResource) . ')');

        return curl_getinfo($this->_curlResource);
    }

    /**
     * read
     * 
     * @access public
     * @return string
     */
    public function read()
    {
         return $this->_result;
    }

    /**
     * setAccept
     * 
     * @access public
     * @param  string $type
     * @return bool
     */
    public function setAccept( $type )
    {
        $rType = RestUtil::getMimeType($type);

        if( !RestUtil::isAvailableMimeType($rType) ) return FALSE;

        $this->_headers[] = 'Accept: ' . $type;

        return TRUE;
    }

    /**
     * setContentType
     * 
     * @access public
     * @param  string $type
     * @return bool
     */
    public function setContentType( $type )
    {
        $rType = RestUtil::getMimeType($type);

        if( !RestUtil::isAvailableMimeType($rType) ) return FALSE;

        $this->_headers[] = 'Content-Type: ' . $type;

        return TRUE;
    }

    /**
     * setHeader
     * 
     * @access public
     * @param  array $headers
     * @return bool
     */
    public function setHeader( array $headers )
    {
        $this->_headers = array_merge($this->_headers, $headers);

        return TRUE;
    }

    /**
     * setAuth
     * Basic Authentication
     * 
     * @see    POSTの場合はusernameのみで良い
     * @access public
     * @param  string $username
     * @param  string $password [Optional]
     * @return bool
     */
    public function setAuth( $username, $password=NULL )
    {
        $this->setCurlOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setCurlOption(CURLOPT_USERPWD, $username . ':' . $password);

        return TRUE;
    }

    /**
     * setCurlOption
     * 
     * @access public
     * @param  string|int $option
     * @param  mixed $value
     * @return bool
     */
    public function setCurlOption( $option, $value )
    {
        $this->_curlOptions[$option] = $value;

        return TRUE;
    }

    /**
     * close
     * 
     * close curl session
     * 
     * @access public
     * @return bool
     */
    public function close()
    {
        if( is_resource($this->_curlResource) )
        {
            curl_close($this->_curlResource);
            $this->_curlResource = NULL;
        }

        return TRUE;
    }

    /**
     * _setData
     * 
     * @access private
     * @param  string $method
     * @param  string $url
     * @param  mixed $data
     * @return bool
     * @throws InvalidArgumentException
     */
    private function _setData( $method, $url, $data )
    {
        $method = strtoupper($method);
        if( !RestMethod::isAvailable($method) ) throw new InvalidArgumentException($method . 'is not supported yet');

        $methodClass = 'RestMethod_' . $method;;
        require_once('method/' . $methodClass . '.php');

        $restMethod = new RestMethod(new $methodClass);

        foreach( $restMethod->set($url, $data) as $key => $value )
            $this->setCurlOption($key, $value);

        return TRUE;
    }
}
