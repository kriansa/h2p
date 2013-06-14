<?php
/**
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
use H2P\Adapter\AdapterAbstract;
use H2P\TempFile;

class Converter
{
    /**
     * @var \H2P\Adapter\AdapterAbstract
     */
    protected $adapter;

    /**
     * @var string|\H2P\TempFile
     */
    protected $uri;

    /**
     * @var string|\H2P\TempFile
     */
    protected $destination;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $orientation;

    /**
     * @var string
     */
    protected $border;

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
     * Constructor
     *
     * @param \H2P\Adapter\AdapterAbstract $adapter
     * @param string|\H2P\TempFile $uri
     * @param string|\H2P\TempFile $destination
     * @param string $format
     * @param string $orientation
     * @param string $border 1cm, 3in
     */
    public function __construct(AdapterAbstract $adapter, $uri, $destination, $format = null, $orientation = null, $border = null, $zoom = null, $session = null)
    {
        // Set defaults
        $format or $format = static::FORMAT_A4;
        $orientation or $orientation = static::ORIENTATION_PORTRAIT;
        $border or $border = '1cm';
        $zoom or $zoom = '1';

        $this->adapter = $adapter;
        $this->uri = $uri;
        $this->destination = $destination;
        $this->format = (string) $format;
        $this->orientation = (string) $orientation;
        $this->border = (string) $border;
        $this->zoom = (float) $zoom;
        $this->session = (string) $session;
    }

    /**
     * Create a new instance (factory method)
     *
     * @param \H2P\Adapter\AdapterAbstract $adapter
     * @param string|\H2P\TempFile $uri
     * @param string|\H2P\TempFile $destination
     * @param string $format
     * @param string $orientation
     * @param string $border 1cm, 3in
     * @return \H2P\Converter
     */
    public static function create(AdapterAbstract $adapter, $uri, $destination, $format = null, $orientation = null, $border = null)
    {
        return new static($adapter, $uri, $destination, $format, $orientation, $border);
    }

    /**
     * Convert a single file and returns the handler output
     *
     * @return bool
     */
    public function convert()
    {
        $uri = $this->uri;
        if ($this->uri instanceof TempFile) {
            $uri = $this->uri->getFileName();
        }

        $destination = $this->destination;
        if ($this->destination instanceof TempFile) {
            $destination = $this->destination->getFileName();
        }

        return $this->adapter->convert($uri, $destination, $this->format, $this->orientation, $this->border, $this->zoom, $this->session);
    }
}