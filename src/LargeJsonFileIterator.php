<?php

namespace Mihaib\LargeJsonParser;

use Generator;

class LargeJsonFileIterator
{
    private JsonFileArrayParser $jsonFileArrayParser;

    public function __construct(
        string $filePath,
        private int $maxItemLength = 1024 * 1000 // 1MB
    ) {
        $this->jsonFileArrayParser = new JsonFileArrayParser(new FileHandler($filePath));
    }

    /**
     * @return JsonItem[]
     */
    public function iterate(): Generator
    {
        while ($item = $this->jsonFileArrayParser->nextItem()) {
            yield $item;
        }

        return null;
    }
}
