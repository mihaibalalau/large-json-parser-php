<?php

namespace Mihaib\LargeJsonParser;

abstract class JsonFileParser extends FileParser
{
    protected int $nestLevel = 0;

    public function __construct(
        FileHandler $fileHandler
    ) {
        parent::__construct($fileHandler);

        $this->init();
    }

    abstract protected function init(): void;


    public function isEndOfJson(): bool
    {
        return $this->isLastCharacter() && $this->isClosingEntity();
    }

    public function isClosingEntity(): bool
    {
        $charMatches = $this->isCurrentCharacter(']') || $this->isCurrentCharacter('}');

        return $charMatches && !$this->isCurrentCharacterEscaped();
    }

    public function isOpeningEntity(): bool
    {
        $charMatches = $this->isCurrentCharacter('[') || $this->isCurrentCharacter('{');

        return $charMatches && !$this->isCurrentCharacterEscaped();
    }

    public function isCurrentCharacterEscaped(): bool
    {
        return $this->previousCharacter() === '\\';
    }

    public function readRawString(): string
    {
        $value = '';
        $totalQuotesFound = 0;

        while ($totalQuotesFound < 2) {
            $value .= $this->currentCharacter();

            if ($this->isQuote()) {
                $totalQuotesFound++;
            }

            $this->step();
        }

        return $value;
    }

    public function isQuote(): bool
    {
        return $this->isCurrentCharacter('"') && !$this->isCurrentCharacterEscaped();
    }

    public function skipComma(): void
    {
        if ($this->isCurrentCharacter(',') && $this->nestLevel === 0) {
            $this->step();
        }
    }

    public function skipWhitespace(): void
    {
        while (ctype_space($this->currentCharacter())) {
            $this->step();
        }
    }
}
