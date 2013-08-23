# H2P [![Build Status](https://api.travis-ci.org/kriansa/h2p.png)](https://travis-ci.org/kriansa/h2p)

Convert your HTML files to PDF with ease. Thanks to PhantomJS, you can write CSS3, HTML5 and Javascript and it will convert and print your files just like you see them in your browser.

## WIP-2.0

This branch is the WIP for the new version. Anything here can be changed until the release. There are breaking changes since 1.0.

## How to use

It's very simple, but first you must download the PhantomJS binary file according to your system (Windows, Mac, Linux-X86 or Linux-X86_64) and put it in the right **bin** folder.

You can download it here: http://phantomjs.org/download.html

```php
use H2P\Converter\PhantomJS;
use H2P\TempFile;

$converter = new PhantomJS();
$output = new TempFile();

// Convert destination accepts TempFile or string with the path to save the file
$converter->convert('http://www.google.com/', $output);

// Save it somewhere
$output->save('/another/path/to/file.pdf');
// or send
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Type: application/pdf');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($output->getFileName()));
readfile($output->getFileName());
```

You can find more examples in the **samples** folder.

## Composer

Just put `{ "kriansa/h2p": "dev-wip-2.0" }` into your require property.

## License

* MIT License