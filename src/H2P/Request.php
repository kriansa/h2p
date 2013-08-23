<?php
/*
 * H2P - HTML to PDF PHP library
 *
 * Request Class
 *
 * LICENSE: The MIT License (MIT)
 *
 * Copyright (C) 2013 Daniel Garajau Pereira
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify,
 * merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies
 * or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package    H2P
 * @author     Daniel Garajau <http://github.com/kriansa>
 * @copyright  2013 Daniel Garajau <http://github.com/kriansa>
 * @license    MIT License
 */

namespace H2P;
use H2P\Request\Cookie;

class Request
{
    /**
     * The URI
     * @var string
     */
    protected $uri = null;

    /**
     * The request method (GET, POST, PUT, DELETE, HEAD)
     * @var string
     */
    protected $method = null;

    /**
     * The body request params [ key => value ]
     * @var array
     */
    protected $params = array();

    /**
     * The headers [ key => value ]
     * @var array
     */
    protected $headers = array();

    /**
     * The cookies array [ cookieName => Cookie ]
     * @var array
     */
    protected $cookies = array();

    /**
     * Method constants
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_HEAD = 'HEAD';

    /**
     * An array with the allowed methods to an request
     * @var array
     */
    protected static $allowedMethods = array(
        self::METHOD_GET,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_DELETE,
        self::METHOD_HEAD,
    );

    /**
     * Constructor
     *
     * @param string $uri
     * @param string $method
     * @param array $headers
     * @param array $cookies
     * @param array $params
     */
    public function __construct($uri, $method = null, $params = null, $headers = null, $cookies = null)
    {
        $this->setUri($uri);
        $this->setMethod($method ?: self::METHOD_GET);
        $headers and $this->headers = $headers;
        $params and $this->params = $params;
        if (count($cookies)) {
            $this->addCookies($cookies);
        }
    }

    /**
     * Serialize the Request into an array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'uri' => $this->uri,
            'method' => $this->method,
            'params' => $this->params,
            'headers' => $this->headers,
            'cookies' => array_map(function(Cookie $cookie) { return $cookie->toArray(); }, array_values($this->cookies)),
        );
    }

    /**
     * Set the params
     *
     * @param array $params
     * @return Request $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Get the params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set a header
     *
     * @param string $name
     * @param string $value
     * @return \H2P\Request $this
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Get a header
     *
     * @param string $headerName
     * @return string
     * @throws \H2P\Exception
     */
    public function getHeader($headerName)
    {
        if (!array_key_exists($headerName, $this->headers)) {
            throw new Exception('Header "' . $headerName . '" was not found!');
        }

        return $this->headers[$headerName];
    }

    /**
     * Get all headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Remove a header
     *
     * @param string $headerName
     * @return \H2P\Request $this
     * @throws \H2P\Exception
     */
    public function removeHeader($headerName)
    {
        if (!array_key_exists($headerName, $this->headers)) {
            throw new Exception('Header "' . $headerName . '" was not found!');
        }

        unset($this->headers[$headerName]);
        return $this;
    }

    /**
     * Remove ALL headers set
     *
     * @return \H2P\Request $this
     */
    public function clearHeaders()
    {
        $this->headers = array();
        return $this;
    }

    /**
     * Set the URI
     *
     * @param string $uri
     * @return \H2P\Request $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get the URI
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the method
     *
     * @param string $method
     * @return \H2P\Request $this
     * @throws \H2P\Exception
     */
    public function setMethod($method)
    {
        if (!in_array($method, self::$allowedMethods)) {
            throw new Exception('Method "' . $method . '" not allowed!');
        }

        $this->method = $method;
        return $this;
    }

    /**
     * Get the method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Adds an array with cookies to the request
     *
     * @param array $cookies
     * @return \H2P\Request $this
     */
    public function addCookies($cookies)
    {
        foreach ($cookies as $cookie) {
            $this->addCookie($cookie);
        }

        return $this;
    }

    /**
     * Adds a cookie to the request
     *
     * @param \H2P\Request\Cookie $cookie
     * @return \H2P\Request $this
     */
    public function addCookie(Cookie $cookie)
    {
        $this->cookies[$cookie->getName()] = $cookie;
        return $this;
    }

    /**
     * Remove a previously set cookie
     *
     * @param string $cookieName
     * @return \H2P\Request $this
     * @throws \H2P\Exception
     */
    public function removeCookie($cookieName)
    {
        if (!array_key_exists($cookieName, $this->cookies)) {
            throw new Exception('Cookie "' . $cookieName . '" not found!');
        }

        unset($this->cookies[$cookieName]);
        return $this;
    }

    /**
     * Remove ALL the cookies set
     *
     * @return \H2P\Request $this
     */
    public function clearCookies()
    {
        $this->cookies = array();
        return $this;
    }

    /**
     * Get a cookie previously set
     *
     * @param string $cookieName
     * @return \H2P\Request\Cookie
     * @throws \H2P\Exception
     */
    public function getCookie($cookieName)
    {
        if (!array_key_exists($cookieName, $this->cookies)) {
            throw new Exception('Cookie "' . $cookieName . '" not found!');
        }

        return $this->cookies[$cookieName];
    }

    /**
     * Return ALL the cookies
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }
}