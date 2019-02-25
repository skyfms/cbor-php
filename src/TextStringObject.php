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

final class TextStringObject extends AbstractCBORObject
{
     const MAJOR_TYPE = 0b011;

    /**
     * @var int|null
     */
    private $length;

    /**
     * @var string
     */
    private $data;

    public function __construct($data)
    {
        list($additionalInformation, $length) = LengthCalculator::getLengthOfString($data);

        parent::__construct(self::MAJOR_TYPE, $additionalInformation);
        $this->data = $data;
        $this->length = $length;
    }

    public function getValue()
    {
        return $this->data;
    }

    public function getLength()
    {
        return mb_strlen($this->data, 'utf8');
    }

    public function getNormalizedData($ignoreTags = false)
    {
        return $this->data;
    }

    public function __toString()
    {
        $result = parent::__toString();
        if (null !== $this->length) {
            $result .= $this->length;
        }
        $result .= $this->data;

        return $result;
    }
}
