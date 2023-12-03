<?php

namespace Mihaib\LargeJsonParser;

use Generator;

class LargeJsonFileIterator
{
    public function __construct(
        private JsonFileArrayParser $jsonFileArrayParser,
        private int $maxItemLength = 1024 * 1000 // 1MB
    ) {
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
