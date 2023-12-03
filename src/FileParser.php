<?php

namespace Mihaib\LargeJsonParser;

class FileParser
{
    private false|string $previousCharacter = false;
    private false|string $currentCharacter = false;
    private false|string $nextCharacter = false;

    public function __construct(
        protected FileHandler $fileHandler
    ) {
        $this->currentCharacter = $fileHandler->getChar();
        $this->nextCharacter = $fileHandler->getChar();
    }

    public function fileHandler(): FileHandler
    {
        return $this->fileHandler;
    }

    public function step(): void
    {
        $this->previousCharacter = $this->currentCharacter;
        $this->currentCharacter = $this->nextCharacter;

        if ($this->fileHandler->isEndOfFile()) {
            $this->nextCharacter = false;
        } else {
            $this->nextCharacter = $this->fileHandler->getChar();
        }
    }

    public function previousCharacter(): null|string
    {
        return $this->previousCharacter;
    }

    public function currentCharacter(): string
    {
        return $this->currentCharacter;
    }

    public function nextCharacter(): string
    {
        return $this->nextCharacter;
    }

    public function isCurrentCharacter(string $char): bool
    {
        return $this->currentCharacter === $char;
    }

    public function isLastCharacter(): bool
    {
        return $this->fileHandler->isEndOfFile() && $this->nextCharacter === false;
    }
}
