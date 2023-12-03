<?php

namespace Mihaib\LargeJsonParser;

use Exception;

class JsonFileArrayParser extends JsonFileParser
{
    protected function init(): void
    {
        $this->skipWhitespace();

        if ($this->isCurrentCharacter('[')) {
            $this->step();
        } else {
            throw new Exception("Invalid json file! Root must be array!");
        }
    }

    public function nextItem(): null|JsonItem
    {
        $this->skipWhitespace();
        $this->skipComma();

        if ($this->isEndOfJson()) {
            return null;
        }

        $item = new JsonItem();

        do {
            $this->skipWhitespace();

            if ($this->isOpeningEntity()) {
                $this->nestLevel++;
            } else if ($this->isClosingEntity()) {
                $this->nestLevel--;
            }

            if ($this->isQuote()) {

                $item->appendString($this->readRawString());
            } else {

                $item->appendChar($this->currentCharacter());
                $this->step();
            }
        } while ($this->nestLevel > 0);

        $this->nestLevel = 0;

        return $item;
    }
}
