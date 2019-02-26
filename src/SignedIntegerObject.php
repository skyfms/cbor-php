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

final class SignedIntegerObject extends AbstractCBORObject
{
     const MAJOR_TYPE = 0b001;

    /**
     * @var string|null
     */
    private $data;

    public function __construct($additionalInformation, $data)
    {
        parent::__construct(self::MAJOR_TYPE, $additionalInformation);
        $this->data = $data;
    }

    public static function createObjectForValue($additionalInformation, $data): self
    {
        return new self($additionalInformation, $data);
    }

    public static function createFromGmpValue(\GMP $value): self
    {
        if (gmp_cmp($value, gmp_init(0)) >= 0) {
            throw new \InvalidArgumentException('The value must be a negative integer.');
        }

        $minusOne = gmp_init(-1);
        $computed_value = gmp_sub($minusOne, $value);

        switch (true) {
            case gmp_intval($computed_value) < 24:
                $ai = gmp_intval($computed_value);
                $data = null;
                break;
            case gmp_cmp($computed_value, gmp_init('FF', 16)) < 0:
                $ai = 24;
                $data = \Safe\hex2bin(str_pad(gmp_strval($computed_value, 16), 2, '0', STR_PAD_LEFT));
                break;
            case gmp_cmp($computed_value, gmp_init('FFFF', 16)) < 0:
                $ai = 25;
                $data = \Safe\hex2bin(str_pad(gmp_strval($computed_value, 16), 4, '0', STR_PAD_LEFT));
                break;
            case gmp_cmp($computed_value, gmp_init('FFFFFFFF', 16)) < 0:
                $ai = 26;
                $data = \Safe\hex2bin(str_pad(gmp_strval($computed_value, 16), 8, '0', STR_PAD_LEFT));
                break;
            default:
                throw new \InvalidArgumentException('Out of range. Please use NegativeBigIntegerTag tag with ByteStringObject object instead.');
        }

        return new self($ai, $data);
    }

    public function getValue()
    {
        return $this->getNormalizedData();
    }

    public function getNormalizedData($ignoreTags = false)
    {
        if (null === $this->data) {
            return (string) (-1 - $this->additionalInformation);
        }

        $result = gmp_init(bin2hex($this->data), 16);
        $minusOne = gmp_init(-1);
        $result = gmp_sub($minusOne, $result);

        return gmp_strval($result, 10);
    }

    public function __toString()
    {
        $result = parent::__toString();
        if (null !== $this->data) {
            $result .= $this->data;
        }

        return $result;
    }
}
