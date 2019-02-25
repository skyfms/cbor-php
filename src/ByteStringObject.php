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

final class ByteStringObject extends AbstractCBORObject
{
     const MAJOR_TYPE = 0b010;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int|null
     */
    private $length;

    public function __construct($data)
    {
        list($additionalInformation, $length) = LengthCalculator::getLengthOfString($data);

        parent::__construct(self::MAJOR_TYPE, $additionalInformation);
        $this->length = $length;
        $this->value = $data;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLength()
    {
        return mb_strlen($this->value, '8bit');
    }

    public function getNormalizedData($ignoreTags = false)
    {
        return $this->value;
    }

    public function __toString()
    {
        $result = parent::__toString();
        if (null !== $this->length) {
            $result .= $this->length;
        }
        $result .= $this->value;

        return $result;
    }
}
