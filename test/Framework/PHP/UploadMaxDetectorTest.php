<?php

namespace Framework\PHP;

use PHPUnit\Framework\TestCase;

class UploadMaxDetectorTest extends TestCase
{
    public function testBytesExtract()
    {
        self::assertEquals(2, UploadMaxDetector::bytesExtract('2'));
        self::assertEquals(2, UploadMaxDetector::bytesExtract('2B'));

        self::assertEquals(2*1024, UploadMaxDetector::bytesExtract('2K'));
        self::assertEquals(2*1024, UploadMaxDetector::bytesExtract('2KB'));
        self::assertEquals(2*1024, UploadMaxDetector::bytesExtract('2k'));

        self::assertEquals(2*1024*1024, UploadMaxDetector::bytesExtract('2M'));
        self::assertEquals(2*1024*1024, UploadMaxDetector::bytesExtract('2MB'));
        self::assertEquals(2*1024*1024, UploadMaxDetector::bytesExtract('2m'));

        self::assertEquals(2*1024*1024*1024, UploadMaxDetector::bytesExtract('2G'));
        self::assertEquals(2*1024*1024*1024, UploadMaxDetector::bytesExtract('2GB'));
        self::assertEquals(2*1024*1024*1024, UploadMaxDetector::bytesExtract('2g'));

        self::assertEquals(2*1024*1024*1024*1024, UploadMaxDetector::bytesExtract('2T'));
        self::assertEquals(2*1024*1024*1024*1024, UploadMaxDetector::bytesExtract('2TB'));
        self::assertEquals(2*1024*1024*1024*1024, UploadMaxDetector::bytesExtract('2t'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBytesExtractWrongArgument()
    {
        self::assertEquals(UploadMaxDetector::bytesExtract('2.2K'),  2*1024);
    }

    public function testBytesFormat()
    {
        self::assertEquals('2 B',  UploadMaxDetector::bytesFormat(2));
        self::assertEquals('2 KB', UploadMaxDetector::bytesFormat(2000, 0));
        self::assertEquals('2 MB', UploadMaxDetector::bytesFormat(2000000, 0));
        self::assertEquals('2 GB', UploadMaxDetector::bytesFormat(2000000000, 0));
        self::assertEquals('2 TB', UploadMaxDetector::bytesFormat(2000000000000, 0));
    }
}
