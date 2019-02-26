<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace CBOR\OtherObject;

use Assert\Assertion;
use CBOR\OtherObject as Base;

final class SinglePrecisionFloatObject extends Base
{
    public static function supportedAdditionalInformation(): array
    {
        return [26];
    }

    public static function createFromLoadedData($additionalInformation, $data): Base
    {
        return new self($additionalInformation, $data);
    }

    /**
     * @return SinglePrecisionFloatObject
     */
    public static function create($value): self
    {
        if (4 !== mb_strlen($value, '8bit')) {
            throw new \InvalidArgumentException('The value is not a valid single precision floating point');
        }

        return new self(26, $value);
    }

    public function getNormalizedData($ignoreTags = false)
    {
        $data = $this->data;
        Assertion::string($data, 'Invalid data');
        $single = gmp_intval(gmp_init(bin2hex($data), 16));
        $exp = ($single >> 23) & 0xff;
        $mant = $single & 0x7fffff;

        if (0 === $exp) {
            $val = $mant * 2 ** (-(126 + 23));
        } elseif (0b11111111 !== $exp) {
            $val = ($mant + (1 << 23)) * 2 ** ($exp - (127 + 23));
        } else {
            $val = 0 === $mant ? INF : NAN;
        }

        return 1 === ($single >> 31) ? -$val : $val;
    }

    public function getExponent()
    {
        $data = $this->data;
        Assertion::string($data, 'Invalid data');
        $single = gmp_intval(gmp_init(bin2hex($data), 16));

        return ($single >> 23) & 0xff;
    }

    public function getMantissa()
    {
        $data = $this->data;
        Assertion::string($data, 'Invalid data');
        $single = gmp_intval(gmp_init(bin2hex($data), 16));

        return $single & 0x7fffff;
    }

    public function getSign()
    {
        $data = $this->data;
        Assertion::string($data, 'Invalid data');
        $single = gmp_intval(gmp_init(bin2hex($data), 16));

        return 1 === ($single >> 31) ? -1 : 1;
    }
}
