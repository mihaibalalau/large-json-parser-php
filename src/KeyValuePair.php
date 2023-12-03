<?php

namespace Mihaib\LargeJsonParser;

class KeyValuePair
{
    public function __construct(
        public string $key,
        public JsonItem $value
    ) {
    }
}
