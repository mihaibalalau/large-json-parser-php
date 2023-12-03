<?php

namespace Tests\Unit;

use Exception;
use Mihaib\LargeJsonParser\FileHandler;
use Mihaib\LargeJsonParser\JsonFileArrayParser;
use Mihaib\LargeJsonParser\LargeJsonFileIterator;
use PHPUnit\Framework\TestCase;

class FileHandlerTest extends TestCase
{
    private $testFilePath = 'data/test.json'; // Ensure this file exists for testing

    public function testSuccessfulFileOpening()
    {
        $handler = new FileHandler($this->testFilePath);

        $iterator = new LargeJsonFileIterator(new JsonFileArrayParser($handler));

        foreach ($iterator->iterate() as $item) {
            // var_dump($item->content(), $item->decode());
            // var_dump($item->content());
            var_dump($item->decode());
        }
        die;
        $this->assertInstanceOf(FileHandler::class, $handler);
    }

    public function testFileNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("The file you're trying to open doesn't exist! Path: nonexistent/file/path.json");

        new FileHandler('nonexistent/file/path.json');
    }

    public function testCharacterReading()
    {
        $handler = new FileHandler($this->testFilePath);
        $char = $handler->getChar();
        $this->assertIsString($char);
    }

    public function testEndOfFile()
    {
        $handler = new FileHandler($this->testFilePath);
        while (!$handler->isEndOfFile()) {
            $handler->getChar(); // Read until the end of the file
        }

        $this->assertTrue($handler->isEndOfFile());
    }

    public function testFileClosure()
    {
        $handler = new FileHandler($this->testFilePath);
        unset($handler); // This triggers __destruct
        $this->assertTrue(true); // If no error is thrown, the test passes
    }
}
