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
use CBOR\ListObject;
use CBOR\SignedIntegerObject;
use CBOR\TagObject as Base;
use CBOR\UnsignedIntegerObject;

final class DecimalFractionTag extends Base
{
    public static function getTagId()
    {
        return 4;
    }

    public static function createFromLoadedData($additionalInformation, $data, CBORObject $object): Base
    {
        return new self($object);
    }

    public function __construct(CBORObject $object)
    {
        if (!$object instanceof ListObject || 2 !== \count($object)) {
            throw new \InvalidArgumentException('This tag only accepts a ListObject object that contains an exponent and a mantissa.');
        }
        $e = $object->get(0);
        if (!$e instanceof UnsignedIntegerObject && !$e instanceof SignedIntegerObject) {
            throw new \InvalidArgumentException('The exponent must be a Signed Integer or an Unsigned Integer object.');
        }
        $m = $object->get(1);
        if (!$m instanceof UnsignedIntegerObject && !$m instanceof SignedIntegerObject && !$m instanceof NegativeBigIntegerTag && !$m instanceof PositiveBigIntegerTag) {
            throw new \InvalidArgumentException('The mantissa must be a Positive or Negative Signed Integer or an Unsigned Integer object.');
        }

        parent::__construct(4, null, $object);
    }

    public static function createFromExponentAndMantissa(CBORObject $e, CBORObject $m): Base
    {
        $object = new ListObject();
        $object->add($e);
        $object->add($m);

        return new self($object);
    }

    public function getNormalizedData($ignoreTags = false)
    {
        if ($ignoreTags) {
            return $this->object->getNormalizedData($ignoreTags);
        }

        if (!$this->object instanceof ListObject || 2 !== \count($this->object)) {
            return $this->object->getNormalizedData($ignoreTags);
        }
        $e = $this->object->get(0);
        $m = $this->object->get(1);

        if (!$e instanceof UnsignedIntegerObject && !$e instanceof SignedIntegerObject) {
            return $this->object->getNormalizedData($ignoreTags);
        }
        if (!$m instanceof UnsignedIntegerObject && !$m instanceof SignedIntegerObject && !$m instanceof NegativeBigIntegerTag && !$m instanceof PositiveBigIntegerTag) {
            return $this->object->getNormalizedData($ignoreTags);
        }

        return rtrim(
            bcmul(
                $m->getNormalizedData($ignoreTags),
                bcpow(
                    '10',
                    $e->getNormalizedData($ignoreTags),
                    100),
                100),
            '0'
        );
    }
}
