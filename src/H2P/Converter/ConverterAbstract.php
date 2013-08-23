<?php
/*
 * H2P - HTML to PDF PHP library
 *
 * Abstract Converter Class
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

use H2P\TempFile;
use H2P\Request;
use H2P\Exception;

abstract class ConverterAbstract
{
    /**
     * Options available to the Converter
     * @var array
     */
    protected $options = array();

    /**
     * Set an option
     *
     * @param string $option
     * @param mixed $value
     * @return \H2P\Converter\ConverterAbstract $this
     * @throws \H2P\Exception
     */
    public function setOption($option, $value)
    {
        if (array_key_exists($option, $this->options)) {
            $this->options[$option] = $value;
        } else {
            throw new Exception('Option "' . $option . '" not found for this converter!');
        }

        return $this;
    }

    /**
     * Set multiple options from an array
     *
     * @param array $options
     * @return \H2P\Converter\ConverterAbstract $this
     */
    public function setOptions($options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }

    /**
     * Get an specific option
     *
     * @param $option
     * @return mixed
     * @throws \H2P\Exception
     */
    public function getOption($option)
    {
        if (array_key_exists($option, $this->options)) {
            return $this->options[$option];
        } else {
            throw new Exception('Option "' . $option . '" not found for this converter!');
        }
    }

    /**
     * Return all the options set
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Convert the file, passing the right arguments to the Converter::transform (proxy pattern)
     *
     * @param string|\H2P\Request|\H2P\TempFile $origin
     * @param string|\H2P\TempFile $destination
     * @throws \H2P\Exception
     */
    public function convert($origin, $destination)
    {
        if (!$origin instanceof Request) {
            if ($origin instanceof TempFile) {
                $origin = $origin->getFileName();
            }

            // Create a simple GET request URI
            $request = new Request($origin);
        } else {
            $request = $origin;
        }

        if ($destination instanceof TempFile) {
            $destination = $destination->getFileName();
        }

        if (!@fopen($destination, 'a')) {
            throw new Exception('The destination file is not writable!');
        }

        $this->transform($request, $destination);
    }

    /**
     * Convert the URI to destination with the specified options
     *
     * @param \H2P\Request $origin
     * @param string $destination The destination full path
     * @return bool
     * @throws \H2P\Exception
     */
    abstract protected function transform(Request $origin, $destination);
}