<?php
/*
 * H2P - HTML to PDF PHP library
 *
 * Converter Class
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

class ConverterFactory
{
    /**
     * Create a new instance (factory method)
     *
     * @param array $adapter
     * @return \H2P\Converter\ConverterAbstract
     * @throws \H2P\Exception
     */
    public static function factory($adapter)
    {
        if (empty($adapter['converter'])) {
            throw new Exception('Adapter array must contain an converter key!');
        }

        $class = 'H2P\\Converter\\' . $adapter['converter'];

        if (!class_exists($class)) {
            throw new Exception('Converter not found!');
        }

        return new $class(isset($adapter['options']) ? $adapter['options'] : null);
    }
}