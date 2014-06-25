/**
 * H2P - HTML to PDF PHP library
 *
 * JS Converter File
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

var page = require('webpage').create();
var args = require('system').args;
var fs = require('fs');

function errorHandler(e) {
    console.log(JSON.stringify({
        success: false,
        response: e.toString()
    }));

    // Stop the script
    phantom.exit(0);
}

try {
    if (args.length < 3) {
        throw 'You must pass the URI and the Destination param!';
    }

    var uri = args[1];
    var destination = args[2];
    var format = args[3] || 'A4';
    var orientation = args[4] || 'portrait';
    var border = args[5] || '1cm';

    page.customHeaders = {
        'User-Agent': 'PhantomJS'
    };

    page.onLoadFinished = function(status) {
        try {
            if (status !== 'success') {
                throw 'Unable to access the URI!';
            }

            page.paperSize = { format: format, orientation: orientation, border: border };
            page.render(destination, { format: 'pdf' });

            console.log(JSON.stringify({
                success: true,
                response: null
            }));

            // Stop the script
            phantom.exit(0);

        } catch (e) {
            errorHandler(e);
        }
    };

    if (uri.substr(0,7) == 'file://') {
        var f = fs.open(uri.substr(7), 'r');
        page.content = f.read();
        f.close();
    } else {
        page.open(uri);
    }

} catch (e) {
    errorHandler(e);
}