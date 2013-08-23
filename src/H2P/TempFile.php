<?php
/*
 * H2P - HTML to PDF PHP library
 *
 * TempFile Class
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
use H2P\Exception;

class TempFile
{
    protected $file;

    /**
     * Constructor
     *
     * @param string $content
     * @param string $extension
     * @throws \H2P\Exception
     */
    public function __construct($content = null, $extension = 'tmp')
    {
        $cache_name = sys_get_temp_dir() . DIRECTORY_SEPARATOR . sha1(uniqid()) . '.' . $extension;

        if (!touch($cache_name)) {
            throw new Exception('Cache file couldn\'t be created!');
        }

        $this->file = $cache_name;
        $content and file_put_contents($this->file, $content);
    }

    /**
     * Destruct deletes the tempfile
     */
    public function __destruct()
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    /**
     * Get the file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file;
    }

    /**
     * Get the file content
     *
     * @return string
     */
    public function getContent()
    {
        return file_get_contents($this->file);
    }

    /**
     * Set the file content
     *
     * @param $content
     * @return \H2P\TempFile $this
     */
    public function setContent($content)
    {
        file_put_contents($this->file, $content);
        return $this;
    }

    /**
     * Persists the temp file in another location
     *
     * @param string $destination
     * @return bool
     */
    public function save($destination)
    {
        return copy($this->file, $destination);
    }

    /**
     * Get the file content
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }
}