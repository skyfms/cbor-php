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

final class InfiniteListObject extends AbstractCBORObject implements \Countable, \IteratorAggregate
{
     const MAJOR_TYPE = 0b100;
     const ADDITIONAL_INFORMATION = 0b00011111;

    /**
     * @var CBORObject[]
     */
    private $data = [];

    public function __construct()
    {
        parent::__construct(self::MAJOR_TYPE, self::ADDITIONAL_INFORMATION);
    }

    public function getNormalizedData($ignoreTags = false): array
    {
        return array_map(function (CBORObject $item) use ($ignoreTags) {
            return $item->getNormalizedData($ignoreTags);
        }, $this->data);
    }

    public function add(CBORObject $item)
    {
        $this->data[] = $item;
    }

    public function count()
    {
        return \count($this->data);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->data);
    }

    public function __toString()
    {
        $result = parent::__toString();
        foreach ($this->data as $object) {
            $result .= (string) $object;
        }
        $result .= \Safe\hex2bin('FF');

        return $result;
    }
}
