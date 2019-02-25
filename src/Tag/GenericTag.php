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

use CBOR\CBORObject;
use CBOR\TagObject as Base;

final class GenericTag extends Base
{
    public static function getTagId()
    {
        return -1;
    }

    public static function createFromLoadedData($additionalInformation, $data, CBORObject $object): Base
    {
        return new self($additionalInformation, $data, $object);
    }

    public function getNormalizedData($ignoreTags = false)
    {
        return $this->object;
    }
}
