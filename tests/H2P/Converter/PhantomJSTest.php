<?php

namespace H2P\Converter;

use H2P\TempFile;

class PhantomJSTest extends \PHPUnit_Framework_TestCase
{
    public function testConvert()
    {
        $converter = new PhantomJS(array(
            'search_paths' => shell_exec('which phantomjs'),
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

        $file = new TempFile();
        $converter->convert(new TempFile('test string to pdf', 'html'), $file);

        $this->assertFileExists($file->getFileName());

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file->getFileName());

        $this->assertEquals('application/pdf', $mime);
    }
}
