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

final class TextStringWithChunkObject extends AbstractCBORObject
{
     const MAJOR_TYPE = 0b011;
     const ADDITIONAL_INFORMATION = 0b00011111;

    /**
     * @var TextStringObject[]
     */
    private $data = [];

    public function __construct()
    {
        parent::__construct(self::MAJOR_TYPE, self::ADDITIONAL_INFORMATION);
    }

    public function add(TextStringObject $chunk)
    {
        $this->data[] = $chunk;
    }

    public function append($chunk)
    {
        $this->add(new TextStringObject($chunk));
    }

    public function getValue()
    {
        $result = '';
        foreach ($this->data as $object) {
            $result .= $object->getValue();
        }

        return $result;
    }

    public function getLength()
    {
        $length = 0;
        foreach ($this->data as $object) {
            $length += $object->getLength();
        }

        return $length;
    }

    public function getNormalizedData($ignoreTags = false)
    {
        $result = '';
        foreach ($this->data as $object) {
            $result .= $object->getNormalizedData($ignoreTags);
        }

        return $result;
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
