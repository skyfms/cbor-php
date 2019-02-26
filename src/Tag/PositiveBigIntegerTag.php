<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace CBOR\Tag;

use CBOR\ByteStringObject;
use CBOR\CBORObject;
use CBOR\TagObject as Base;

final class PositiveBigIntegerTag extends Base
{
    public static function getTagId()
    {
        return 2;
    }

    public static function createFromLoadedData($additionalInformation, $data, CBORObject $object): Base
    {
        return new self($additionalInformation, $data, $object);
    }

    public static function create(CBORObject $object): Base
    {
        if (!$object instanceof ByteStringObject) {
            throw new \InvalidArgumentException('This tag only accepts a Byte String object.');
        }

        return new self(2, null, $object);
    }

    public function getNormalizedData($ignoreTags = false)
    {
        if ($ignoreTags) {
            return $this->object->getNormalizedData($ignoreTags);
        }

        if (!$this->object instanceof ByteStringObject) {
            return $this->object->getNormalizedData($ignoreTags);
        }

        return gmp_strval(gmp_init(bin2hex($this->object->getValue()), 16), 10);
    }
}
