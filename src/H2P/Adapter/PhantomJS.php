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
     * @var string
     */
    protected $platform;

    /**
     * @var string
     */
    protected $binFolder;

    /**
     * Platform constants
     */
    const PLATFORM_WINDOWS = 'WINDOWS';
    const PLATFORM_LINUX_X86 = 'LINUX-X86';
    const PLATFORM_LINUX_X86_64 = 'LINUX-X86_64';
    const PLATFORM_DARWIN = 'DARWIN';

    /**
     * Constructor
     *
     * @param string $platform
     * @param string $binFolder
     */
    public function __construct($platform = null, $binFolder = null)
    {
        if ($platform) {
            $this->platform = $platform;
        } else {
            switch (php_uname('s')) {
                case 'Linux':
                    $this->platform = (PHP_INT_MAX == 2147483647) ? static::PLATFORM_LINUX_X86 : static::PLATFORM_LINUX_X86_64;
                    break;
                case 'Darwin':
                    $this->platform = static::PLATFORM_DARWIN;
                    break;
                case 'Windows NT':
                    $this->platform = static::PLATFORM_WINDOWS;
                    break;
            }
        }

        if ($binFolder) {
            $this->setBinFolder($binFolder);
        } else {
            $this->setBinFolder(realpath(__DIR__ . '/../../../bin'));
        }
    }

    /**
     * Set the binary folder
     *
     * @param string $binFolder
     * @return $this
     */
    public function setBinFolder($binFolder)
    {
        $this->binFolder = $binFolder;
        return $this;
    }

    /**
     * Return the binary folder
     *
     * @return string
     */
    public function getBinFolder()
    {
        return $this->binFolder;
    }


    /**
     * Get the binary script to execute
     *
     * @return mixed
     * @throws \H2P\Exception
     */
    protected function getBinPath()
    {
        static $binPaths;
        if (!$binPaths) {
            $binDir = $this->getBinFolder();
            $binPaths = array(
                static::PLATFORM_WINDOWS => $binDir . '/win32/phantomjs.exe',
                static::PLATFORM_LINUX_X86 => $binDir . '/linux-x86/phantomjs',
                static::PLATFORM_LINUX_X86_64 => $binDir . '/linux-x86_64/phantomjs',
                static::PLATFORM_DARWIN => $binDir . '/mac/phantomjs',
            );
        }

        if (!is_file($binPaths[$this->platform])) {
            throw new Exception('PhantomJS binary not found! Please, download it at <http://phantomjs.org/download.html> and put it on ' . dirname($binPaths[$this->platform]) . ' folder');
        }

        return escapeshellarg($binPaths[$this->platform]) . ' ' . escapeshellarg($binDir . '/converter.js');
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