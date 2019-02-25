<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace CBOR;

final class InfiniteMapObject extends AbstractCBORObject implements \Countable, \IteratorAggregate
{
     const MAJOR_TYPE = 0b101;
     const ADDITIONAL_INFORMATION = 0b00011111;

    /**
     * @var MapItem[]
     */
    private $data = [];

    public function __construct()
    {
        parent::__construct(self::MAJOR_TYPE, self::ADDITIONAL_INFORMATION);
    }

    public function append(CBORObject $key, CBORObject $value)
    {
        $this->data[] = new MapItem($key, $value);
    }

    public function count()
    {
        return \count($this->data);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->data);
    }

    public function getNormalizedData($ignoreTags = false): array
    {
        $result = [];
        foreach ($this->data as $object) {
            $result[$object->getKey()->getNormalizedData($ignoreTags)] = $object->getValue()->getNormalizedData($ignoreTags);
        }

        return $result;
    }

    public function __toString()
    {
        $result = parent::__toString();
        foreach ($this->data as $object) {
            $result .= (string) $object->getKey();
            $result .= (string) $object->getValue();
        }
        $result .= \Safe\hex2bin('FF');

        return $result;
    }
}
