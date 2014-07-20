# H2P [![Build Status](https://api.travis-ci.org/kriansa/h2p.png)](https://travis-ci.org/kriansa/h2p)

Convert your HTML files to PDF with ease. Thanks to PhantomJS, you can write CSS3, HTML5 and Javascript and it will convert and print your files just like you see them in your browser.

## How to use

It's very simple, but first you must download the PhantomJS binary file according to your system (Windows, Mac, Linux-X86 or Linux-X86_64) and put it in the right **bin** folder.

You can download it here: http://phantomjs.org/download.html

## Getting started

```php
use H2P\Converter\PhantomJS;

$converter = new PhantomJS();

// Convert destination accepts H2P\TempFile or string with the path to save the file
$converter->convert('http://www.google.com/', '/home/user/Documents/page.pdf');
```

If you want to convert a HTML string, do it like so:

```php
use H2P\Converter\PhantomJS;
use H2P\TempFile;

$converter = new PhantomJS();
$input = new TempFile('<b>test string to pdf</b>', 'html'); // Make sure the 2nd parameter is 'html'

// Convert destination accepts H2P\TempFile or string with the path to save the file
$converter->convert($input, '/home/user/Documents/page.pdf');
```

## Advanced usage

```php
use H2P\Converter\PhantomJS;
use H2P\TempFile;
use H2P\Request;
use H2P\Request\Cookie;

$converter = new PhantomJS(array(
    // You should use 'search_paths' when you want to point the phantomjs binary to somewhere else
    // 'search_paths' => shell_exec('which phantomjs'),
    'orientation' => PhantomJS::ORIENTATION_LANDSCAPE,
    'format' => PhantomJS::FORMAT_A4,
    'zoomFactor' => 2,
    'border' => '1cm',
    'header' => array(
        'height' => '1cm',
        'content' => "<h1>{{pageNum}} / {{totalPages}}</h1>",
    ),
    'footer' => array(
        'height' => '1cm',
        'content' => "<h1>{{pageNum}} / {{totalPages}}</h1>",
    ),
));

// Create a full custom request
$request = new Request(
    'http://www.google.com/',
    Request::METHOD_POST,
    array('param' => 'value'), // POST params
    array('X-Header' => 'value'), // Custom headers
    array(
        new Cookie('Cookie', 'value', 'domain'), // Create a basic cookie
    )
);

$destination = new TempFile();
$converter->convert($request, $destination);
```

You can find more examples in the **samples** folder.

## Composer

Just put `{ "kriansa/h2p": "dev-master" }` into your require property.

## License

* MIT License