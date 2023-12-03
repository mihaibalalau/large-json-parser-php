<?php

namespace Mihaib\LargeJsonParser;

use Exception;

class FileHandler
{
    private $fileHandle;
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->openFile();
    }

    public function __destruct()
    {
        $this->closeFile();
    }

    private function openFile(): void
    {
        if (!file_exists($this->filePath)) {
            throw new Exception("The file you're trying to open doesn't exist! Path: $this->filePath");
        }

        $this->fileHandle = fopen($this->filePath, 'r');

        if (!$this->fileHandle) {
            throw new Exception("The file could not be opened! Path: {$this->filePath}");
        }
    }

    public function closeFile(): void
    {
        fclose($this->fileHandle);
    }

    public function currentPosition(): int
    {
        return ftell($this->fileHandle);
    }

    public function moveStart(int $offset = 0): void
    {
        fseek($this->fileHandle, $offset, SEEK_SET);
    }

    public function moveOffset(int $offset = 1): void
    {
        fseek($this->fileHandle, $offset, SEEK_CUR);
    }

    public function moveEnd(int $offset = 0): void
    {
        fseek($this->fileHandle, $offset, SEEK_END);
    }

    public function getChar(): string|false
    {
        return fgetc($this->fileHandle);
    }

    public function getChars(int $count = 1): string
    {
        $value = '';
        $length = 0;

        while (!$this->isEndOfFile() && $length <= $count) {
            $value .= $this->getChar();
            $length++;
        }

        return $value;
    }

    public function isEndOfFile(): bool
    {
        return feof($this->fileHandle);
    }
}
