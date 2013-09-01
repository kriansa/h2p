/*
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
    if (args.length < 2) {
        throw 'You must pass the URI and the Destination param!';
    }

    // Take all options in one JSON param
    var options = JSON.parse(args[1]);

    page.customHeaders = options.request.headers;
    phantom.cookies = options.request.cookies;

    page.open(options.request.uri + (options.request.method == 'GET' ? '?' + options.request.params : ''), options.request.method, options.request.params, function (status) {
        try {
            if (status !== 'success') {
                throw 'Unable to access the URI! (Make sure you\'re using a .html extension if you\'re trying to use a local file)';
            }

            var paperSize = {
                format: options.format,
                orientation: options.orientation,
                border: options.border
            };

            // Custom footer in the markup
            if (page.evaluate(function(){return typeof h2pFooter == "object";})) {
            	
        		paperSize.footer = {
                    height: page.evaluate(function() {
                        return h2pFooter.height;
                    }),
                    
                    contents: phantom.callback(function(pageNum, totalPages) {
                    	
                    	var contents = page.evaluate(function() {
                    		return h2pFooter.contents;
                    	});
                    	
                		return contents.replace('{{pageNum}}', pageNum).replace('{{totalPages}}', totalPages);
                    })
                }
            	
            }else if (options.footer) {
                paperSize.footer = {
                    height: options.footer.height,
                    contents: phantom.callback(function(pageNum, totalPages) {
                        return options.footer.content.replace('{{pageNum}}', pageNum).replace('{{totalPages}}', totalPages);
                    })
                }
            }

            // Custom header in the markup
            if (page.evaluate(function(){return typeof h2pHeader == "object";})) {
            	
        		paperSize.header = {
                    height: page.evaluate(function() {
                        return h2pHeader.height;
                    }),
                    
                    contents: phantom.callback(function(pageNum, totalPages) {
                    	
                    	var contents = page.evaluate(function() {
                    		return h2pHeader.contents;
                    	});
                    	
                		return contents.replace('{{pageNum}}', pageNum).replace('{{totalPages}}', totalPages);
                    })
                }
            	
            }else if (options.header) {
                paperSize.header = {
                    height: options.header.height,
                    contents: phantom.callback(function(pageNum, totalPages) {
                        return options.header.content.replace('{{pageNum}}', pageNum).replace('{{totalPages}}', totalPages);
                    })
                }
            }

            page.paperSize = paperSize;
            page.zoomFactor = options.zoomFactor;
            page.render(options.destination, { format: 'pdf' });

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