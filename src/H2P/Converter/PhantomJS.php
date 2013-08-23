<?php
/*
 * H2P - HTML to PDF PHP library
 *
 * PhantomJS Converter Class
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

namespace H2P\Converter;

use H2P\Converter\ConverterAbstract;
use H2P\Exception;
use H2P\Request;

class PhantomJS extends ConverterAbstract
{
    /**
     * Constants
     */
    const ORIENTATION_PORTRAIT = 'Portrait';
    const ORIENTATION_LANDSCAPE = 'Landscape';

    /**
     * Page sizes
     */
    const FORMAT_A0 = 'A0';
    const FORMAT_A1 = 'A1';
    const FORMAT_A2 = 'A2';
    const FORMAT_A3 = 'A3';
    const FORMAT_A4 = 'A4';
    const FORMAT_A5 = 'A5';
    const FORMAT_A6 = 'A6';
    const FORMAT_A7 = 'A7';
    const FORMAT_A8 = 'A8';
    const FORMAT_A9 = 'A9';
    const FORMAT_B0 = 'B0';
    const FORMAT_B1 = 'B1';
    const FORMAT_B2 = 'B2';
    const FORMAT_B3 = 'B3';
    const FORMAT_B4 = 'B4';
    const FORMAT_B5 = 'B5';
    const FORMAT_B6 = 'B6';
    const FORMAT_B7 = 'B7';
    const FORMAT_B8 = 'B8';
    const FORMAT_B9 = 'B9';
    const FORMAT_B10 = 'B10';
    const FORMAT_C5E = 'C5E';
    const FORMAT_COMM10E = 'Comm10E';
    const FORMAT_DLE = 'DLE';
    const FORMAT_EXECUTIVE = 'Executive';
    const FORMAT_FOLIO = 'Folio';
    const FORMAT_LEDGER = 'Ledger';
    const FORMAT_LEGAL = 'Legal';
    const FORMAT_LETTER = 'Letter';
    const FORMAT_TABLOID = 'Tabloid';

    /**
     * Options available to the PhantomJS
     * @var array
     */
    protected $options = array(
        'orientation' => self::ORIENTATION_PORTRAIT,
        'format' => self::FORMAT_A4,
        'zoomFactor' => 1,
        'border' => '1cm',
        'header' => null,
        'footer' => null,
    );

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
     * @param array $options
     */
    public function __construct($options = null)
    {
        $paths = !empty($options['search_paths']) ? $options['paths'] : array();

        // Set the right path for the bin directory
        $this->binPath = realpath(__DIR__ . '/' . $this->binPath);

        $options and $this->setOptions($options);
        $this->detectSearchPaths($paths);
    }

    /**
     * Set and detect paths for PhantomJS binary file
     * 
     * @param array|string $paths
     * @return \H2P\Converter\PhantomJS $this
     */
    protected function detectSearchPaths($paths = array())
    {
        $os = php_uname('s');

        if (is_string($paths)) {
            $paths = array($paths);
        }

        switch ($os) {
            case 'Windows NT':
                $default_path = $this->binPath . '/win32/phantomjs.exe';
                break;
            case 'Darwin':
                $default_path = $this->binPath . '/mac/phantomjs';
                break;
            case 'Linux':
                $default_path = $this->binPath . (PHP_INT_MAX == 2147483647 ? '/linux-x86/phantomjs' : '/linux-x86_64/phantomjs');
        }

        array_unshift($paths, $default_path);
        $this->searchPaths = $paths;
        
        return $this;
    }

    /**
     * Set the PhantomJS search path
     *
     * @param array|string $searchPaths
     * @return \H2P\Converter\PhantomJS $this
     */
    public function setSearchPaths($searchPaths)
    {
        return $this->detectSearchPaths($searchPaths);
    }

    /**
     * Append an search path to the stack
     *
     * @param $searchPath
     * @return \H2P\Converter\PhantomJS $this
     */
    public function addSearchPath($searchPath)
    {
        $this->searchPaths[] = $searchPath;
        return $this;
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
     * Fix the Request array key names to PhantomJS request object
     *
     * @param Request $origin
     * @return array
     */
    protected static function fixRequestKeyNames(Request $origin)
    {
        $request = $origin->toArray();

        return array_filter(array(
            'uri' => $request['uri'],
            'method' => $request['method'],
            'params' => $request['params'] ? http_build_query($request['params'], null, '&') : null,
            'headers' => $request['headers'],
            'cookies' => array_map(function($cookie) {
                return array_filter(array(
                    'name' => $cookie['name'],
                    'value' => $cookie['value'],
                    'domain' => $cookie['domain'],
                    'path' => $cookie['path'],
                    'httponly' => $cookie['httpOnly'],
                    'secure' => $cookie['secure'],
                    'expires' => $cookie['expires'],
                ));
            }, $request['cookies']),
        ));
    }

    /**
     * Convert the URI to destination with the specified options
     *
     * @param \H2P\Request $origin
     * @param string $destination The destination full path
     * @return bool
     * @throws \H2P\Exception
     */
    protected function transform(Request $origin, $destination)
    {
        $request = self::fixRequestKeyNames($origin);

        $args = array(
            'destination' => $destination,
            'request' => $request,
        ) + $this->options;

        $result = json_decode(trim(shell_exec($this->getBinPath() . ' ' . escapeshellarg(json_encode($args)))));

        if (!$result->success) {
            throw new Exception('Error while executing PhantomJS: "' . $result->response . '"');
        }

        return true;
    }
}
