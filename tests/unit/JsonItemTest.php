<?php

namespace Tests\Unit;

use Mihaib\LargeJsonParser\JsonItem;
use PHPUnit\Framework\TestCase;

class JsonItemTest extends TestCase
{
    public function testIsValid()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendString('{"key": "value"}');
        $this->assertTrue($jsonItem->isValid());

        $jsonItem = new JsonItem();
        $jsonItem->appendString('invalid json');
        $this->assertFalse($jsonItem->isValid());
    }

    public function testContent()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendString('{"key": "value"}');
        $this->assertEquals('{"key": "value"}', $jsonItem->content());
    }

    public function testLength()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendString('{"key": "value"}');
        $this->assertEquals(strlen('{"key": "value"}'), $jsonItem->length());
    }

    public function testDecode()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendString('{"key": "value"}');
        $result = $jsonItem->decode();
        $this->assertEquals("value", $result->key);
    }

    public function testAppendString()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendString('{"key":');
        $jsonItem->appendString('"value"}');
        $this->assertEquals('{"key":"value"}', $jsonItem->content());
        $this->assertEquals(strlen('{"key":"value"}'), $jsonItem->length());
    }

    public function testAppendChar()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendChar('{');
        $jsonItem->appendChar('}');
        $this->assertEquals('{}', $jsonItem->content());
        $this->assertEquals(2, $jsonItem->length());
    }

    public function testIsObject()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendString('{"key": "value"}');
        $this->assertTrue($jsonItem->isObject());

        $jsonItem = new JsonItem();
        $jsonItem->appendString('["value"]');
        $this->assertFalse($jsonItem->isObject());
    }

    public function testIsArray()
    {
        $jsonItem = new JsonItem();
        $jsonItem->appendString('["value"]');
        $this->assertTrue($jsonItem->isArray());

        $jsonItem = new JsonItem();
        $jsonItem->appendString('{"key": "value"}');
        $this->assertFalse($jsonItem->isArray());
    }
}
