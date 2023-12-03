<?php

namespace Mihaib\LargeJsonParser;

use stdClass;

class JsonItem
{
    private string $content = '';
    private int $contentLength = 0;

    public function isValid(): bool
    {
        if ($this->contentLength) {
            return !is_null($this->decode());
        }

        return false;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function length(): int
    {
        return $this->contentLength;
    }

    public function decode(): null|array|stdClass|string
    {
        return json_decode($this->content);
    }

    public function appendString(string $string): void
    {
        $this->content .= $string;
        $this->contentLength += strlen($string);
    }

    public function appendChar(string $char): void
    {
        $this->content .= $char;
        $this->contentLength++;
    }

    public function isObject(): bool
    {
        return $this->content[0] === '{';
    }

    public function isArray(): bool
    {
        return $this->content[0] === '[';
    }
}
