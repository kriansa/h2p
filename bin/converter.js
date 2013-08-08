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
    
    var config = {};
    
    if (args[3]){
    	config = JSON.parse(args[3]);
    }

    var uri = args[1];
    var destination = args[2];
    var format = config.format || 'A4';
    var orientation = config.orientation || 'portrait';
    var border = config.border || '1cm';

    page.customHeaders = {
        'User-Agent': 'PhantomJS'
    };

    page.open(uri, function (status) {
        try {
            if (status !== 'success') {
                throw 'Unable to access the URI!';
            }

            page.paperSize = { format: format, orientation: orientation, border: border };
            
            if(config.zoomFactor){
            	page.zoomFactor = config.zoomFactor;
            }
            
            if(config.margin){
            	page.paperSize.margin = config.margin;
            }
            
            // Custom header in the markup
            if (page.evaluate(function(){return typeof h2pHeader == "object";})) {

                page.paperSize.header.height = page.evaluate(function() {
                    return h2pHeader.header.height;
                });
                
                page.paperSize.header.contents = phantom.callback(function(pageNum, numPages) {
                    return page.evaluate(function(pageNum, numPages){return h2pHeader.header.contents(pageNum, numPages);}, pageNum, numPages);
                });
            }

            // Custom footer in the markup
            if (page.evaluate(function(){return typeof h2pFooter == "object";})) {
            	
            	page.paperSize.footer.height = page.evaluate(function() {
                    return h2pFooter.footer.height;
                });
            	
            	page.paperSize.footer.contents = phantom.callback(function(pageNum, numPages) {
                    return page.evaluate(function(pageNum, numPages){return h2pFooter.footer.contents(pageNum, numPages);}, pageNum, numPages);
                });
            }

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
    });
} catch (e) {
    errorHandler(e);
}