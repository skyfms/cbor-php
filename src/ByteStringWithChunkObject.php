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

final class ByteStringWithChunkObject extends AbstractCBORObject
{
     const MAJOR_TYPE = 0b010;
     const ADDITIONAL_INFORMATION = 0b00011111;

    /**
     * @var ByteStringObject[]
     */
    private $chunks = [];

    public function __construct()
    {
        parent::__construct(self::MAJOR_TYPE, self::ADDITIONAL_INFORMATION);
    }

    public function add(ByteStringObject $chunk)
    {
        $this->chunks[] = $chunk;
    }

    public function append($chunk)
    {
        $this->add(new ByteStringObject($chunk));
    }

    public function getValue()
    {
        $result = '';
        foreach ($this->chunks as $chunk) {
            $result .= $chunk->getValue();
        }

        return $result;
    }

    public function getLength()
    {
        $length = 0;
        foreach ($this->chunks as $chunk) {
            $length += $chunk->getLength();
        }

        return $length;
    }

    public function getNormalizedData($ignoreTags = false)
    {
        $result = '';
        foreach ($this->chunks as $chunk) {
            $result .= $chunk->getNormalizedData($ignoreTags);
        }

        return $result;
    }

    public function __toString()
    {
        $result = parent::__toString();
        foreach ($this->chunks as $chunk) {
            $result .= (string) $chunk;
        }
        $result .= \Safe\hex2bin('FF');

        return $result;
    }
}
