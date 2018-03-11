<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace CBOR\Tag;

use Base64Url\Base64Url;
use CBOR\ByteStringObject;
use CBOR\ByteStringWithChunkObject;
use CBOR\CBORObject;
use CBOR\TagObject as Base;
use CBOR\TextStringObject;
use CBOR\TextStringWithChunkObject;

final class Base64UrlEncodingObject extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function create(int $additionalInformation, ?string $data, CBORObject $object): Base
    {
        return new self($additionalInformation, $data, $object);
    }

    /**
     * {@inheritdoc}
     */
    public function getNormalizedData()
    {
        $object = $this->getData();
        if (!$object instanceof ByteStringObject && !$object instanceof ByteStringWithChunkObject && !$object instanceof TextStringObject && !$object instanceof TextStringWithChunkObject) {
            return $object->getNormalizedData();
        }

        return Base64Url::decode($object->getNormalizedData());
    }
}