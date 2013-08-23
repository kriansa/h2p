<?php
/*
 * H2P - HTML to PDF PHP library
 *
 * Request Cookie Class
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

namespace H2P\Request;

class Cookie
{
    /**
     * Name
     * @var string
     */
    protected $name = null;

    /**
     * Value
     * @var string
     */
    protected $value = null;

    /**
     * Accepted domain
     * @var string
     */
    protected $domain = null;

    /**
     * Path
     * @var string
     */
    protected $path = null;

    /**
     * HTTP Only?
     * @var bool
     */
    protected $httpOnly = null;

    /**
     * Secure?
     * @var bool
     */
    protected $secure = null;

    /**
     * Unix timestamp expire
     * @var int
     */
    protected $expires = null;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $value
     * @param string $domain
     * @param string $path
     * @param bool $httpOnly
     * @param bool $secure
     * @param int $expires
     */
    public function __construct($name, $value, $domain = null, $path = '/', $httpOnly = false, $secure = false, $expires = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->domain = $domain;
        $this->path = $path;
        $this->httpOnly = $httpOnly;
        $this->secure = $secure;
        $this->expires = $expires;
    }

    /**
     * Set the domain
     *
     * @param string $domain
     * @return \H2P\Request\Cookie $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Get the domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set the expire time
     *
     * @param int $expires
     * @return \H2P\Request\Cookie $this
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * Get the expire time
     *
     * @return int
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set whether the cookie is http only
     *
     * @param boolean $httpOnly
     * @return \H2P\Request\Cookie $this
     */
    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

    /**
     * Get whether the cookie is http only
     *
     * @return boolean
     */
    public function getHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * Set the name
     *
     * @param string $name
     * @return \H2P\Request\Cookie $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the path
     *
     * @param string $path
     * @return \H2P\Request\Cookie $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set whether the cookie is secure
     *
     * @param boolean $secure
     * @return \H2P\Request\Cookie $this
     */
    public function setSecure($secure)
    {
        $this->secure = $secure;
        return $this;
    }

    /**
     * Check whether the cookie is secure
     *
     * @return boolean
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * Set the value
     *
     * @param string $value
     * @return \H2P\Request\Cookie $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get the value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the cookie serialized to an array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'name' => $this->name,
            'value' => $this->value,
            'domain' => $this->domain,
            'path' => $this->path,
            'httpOnly' => $this->httpOnly,
            'secure' => $this->secure,
            'expires' => $this->expires,
        );
    }
}