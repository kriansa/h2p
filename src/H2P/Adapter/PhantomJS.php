<?php
/**
 * H2P - HTML to PDF PHP library
 *
 * PhantomJS Adapter Class
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

namespace H2P\Adapter;

use H2P\Adapter\AdapterAbstract;
use H2P\Exception;

class PhantomJS extends AdapterAbstract
{
    /**
     * Library bin path
     * 
     * @var string
     */
    protected $binPath = '../../../bin';
    
    /**
     * Paths to search PhantomJS binary file
     * 
     * @var array
     */
    protected $searchPaths = array();

    /**
     * Constructor
     *
     * @param array|string $paths Path for PhantomJS binary file
     */
    public function __construct($paths = array())
    {        
        $this->binPath = realpath(__DIR__ . '/' . $this->binPath);
        $this->detectSearchPaths($paths);
    }
    
    /**
     * Set and detect paths for PhantomJS binary file
     * 
     * @param array $paths
     * @return PhantomJS
     */
    protected function detectSearchPaths($paths = array())
    {
        $os = php_uname('s');

        if (is_string($paths)) {
            $paths = array($paths);
        }

        switch ($os) {
            case 'Windows NT':
                $paths[] = 'C:/Windows/phantomjs.exe';
                $paths[] = $this->binPath . '/win32/phantomjs.exe';
                break;
            case 'Darwin':
                $paths[] = '/usr/local/bin/phantomjs'; // I don't know if is the right path for Mac
                $paths[] = $this->binPath . '/mac/phantomjs';
                break;
            case 'Linux':
            default:
                $paths[] = '/usr/local/bin/phantomjs';
                $paths[] = $this->binPath . '/linux-x86_64/phantomjs';
                $paths[] = $this->binPath . '/linux-x86/phantomjs';
        }

        $this->searchPaths = $paths;
        
        return $this;
    }

    /**
     * Set the PhantomJS search path
     *
     * @param string $searchPath
     * @return PhantomJS
     */
    public function setSearchPath($searchPath)
    {
        return $this->detectSearchPaths($searchPath);
    }

    /**
     * Return the search paths for PhantomJS
     *
     * @return array
     */
    public function getSearchPaths()
    {
        return $this->searchPaths;
    }

    /**
     * Returns the PhantomJS binary path based on defined Search Paths
     * 
     * @return string
     * @throws Exception
     */
    protected function getPhantomPath()
    {
        // Return the first valid file
        foreach ($this->searchPaths as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        throw new Exception('PhantomJS binary not found! Please, download it at <http://phantomjs.org/download.html>');
    }
    
    /**
     * Returns H2P Converter Script Path
     * 
     * @return string
     */
    protected function getConverterPath()
    {
        return $this->binPath . '/converter.js';
    }

    /**
     * Get the binary script to execute
     *
     * @return mixed
     * @throws \H2P\Exception
     */
    protected function getBinPath()
    {
        $phantomjs = $this->getPhantomPath();
        $converter = $this->getConverterPath();

        return escapeshellarg($phantomjs) . ' ' . escapeshellarg($converter);
    }

    /**
     * Convert the URI to destination with the specified options
     *
     * @param string $uri
     * @param string $destination
     * @param string $format
     * @param string $orientation
     * @param string $border
     * @return bool
     * @throws \H2P\Exception
     */
    public function convert($uri, $destination, $format, $orientation, $border)
    {
        $bin = $this->getBinPath();
        $args[] = escapeshellarg($uri);
        $args[] = escapeshellarg($destination);
        $args[] = escapeshellarg($format);
        $args[] = escapeshellarg($orientation);
        $args[] = escapeshellarg($border);

        $result = json_decode(trim(shell_exec($bin . ' ' . implode(' ', $args))));

        if (!$result->success) {
            throw new Exception('Error while executing PhantomJS: ' . $result->response);
        }

        return true;
    }
}
