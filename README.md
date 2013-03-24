# H2P

Convert your HTML files to PDF with ease. Thanks to PhantomJS, you can write CSS3, HTML5 and Javascript and it will convert and print your files just like you see them in your browser.

## How to use

It's very simple, but first you must download the PhantomJS binary file according to your system (Windows, Mac, Linux-X86 or Linux-X86_64) and put it in the right **bin** folder.

You can download it here: http://phantomjs.org/download.html

```php
use H2P\Converter;
use H2P\Adapter\PhantomJS;
use H2P\TempFile;

// Set the input content to convert
$input = new TempFile($htmlString);
$input = 'http://www.google.com/';
$input = '/path/to/file.html';

// Then do the conversion
$output = new TempFile();
$instance = new Converter(new PhantomJS(), $input, $output);
$instance->convert();

// Save it somewhere
$output->save('/another/path/to/file.pdf');
// or
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Type: application/pdf');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($output->getFileName()));
echo $output->getContent();
```

More information: http://garajau.com.br/blog/2013/03/h2p-convert-html-files-to-pdf/

## Composer

Just put `{ "kriansa/h2p": "dev-master" }` into your require property.

## License

* MIT License
