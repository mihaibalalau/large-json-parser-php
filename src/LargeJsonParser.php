<?php

namespace Mihaib\LargeJsonParser;

use Exception;
use Generator;

class LargeJsonFile
{
    public function __construct(
        private string $jsonFilePath
    ) {
        if (!file_exists($jsonFilePath)) {
            throw new Exception("The large .json file you're trying to iterate over doesn't exist! Path: $jsonFilePath");
        }
    }

    public function iterate(bool $decode = false, int $maxItemLength = 1024 * 1000): Generator
    {
        $fileHandle = fopen($this->jsonFilePath, 'r');

        if (!$fileHandle) {
            throw new Exception("The large .json file you're trying to iterate over could not be opened! Path: {$this->jsonFilePath}");
        }

        try {
            $firstCharacter = fgetc($fileHandle);

            if ($firstCharacter !== '[') {
                throw new Exception("The large .json file you're trying to iterate over isn't an array!");
            }

            while (!feof($fileHandle)) {
                $content = $this->readItem($fileHandle, $maxItemLength);

                if ($content === ']') {
                    return;
                }

                if ($decode) {
                    $json = json_decode($content, true);

                    if ($json === null) {
                        throw new Exception("Large .json file parsing error: Chunk decoding failed! " . json_last_error_msg());
                    }

                    yield $json;
                } else {
                    yield $content;
                }
            }

            return null;
        } finally {
            fclose($fileHandle);
        }
    }

    private function readItem($fileHandle, int $maxItemLength): string
    {
        $nestLevel = 0;
        $content = '';
        $contentLength = 0;

        $isInsideQuotes = false;
        $isBackslash = false;

        do {
            do {
                $currentCharacter = fgetc($fileHandle);

                if (
                    $currentCharacter === ","
                    && $nestLevel === 0
                ) {
                    $currentCharacter = fgetc($fileHandle);
                }
            } while (ctype_space($currentCharacter) && !$isInsideQuotes);

            $content .= $currentCharacter;
            $contentLength++;

            if (!$isInsideQuotes) {
                if ($currentCharacter === '[' || $currentCharacter === '{') {
                    $nestLevel++;
                    continue;
                } elseif ($currentCharacter === ']' || $currentCharacter === '}') {
                    $nestLevel--;
                    continue;
                }
            }

            if ($currentCharacter === '"' && !$isBackslash) {
                $isInsideQuotes = !$isInsideQuotes;
                continue;
            }

            $isBackslash = $currentCharacter === "\\";

            if ($contentLength > $maxItemLength) {
                throw new Exception("Large .json file parsing error: Chunk too big!");
            }
        } while ($nestLevel > 0);

        return $content;
    }
}
