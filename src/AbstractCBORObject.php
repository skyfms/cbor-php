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

abstract class AbstractCBORObject implements CBORObject
{
    /**
     * @var int
     */
    private $majorType;

    /**
     * @var int
     */
    protected $additionalInformation;

    public function __construct($majorType, $additionalInformation)
    {
        $this->majorType = $majorType;
        $this->additionalInformation = $additionalInformation;
    }

    public function getMajorType()
    {
        return $this->majorType;
    }

    public function getAdditionalInformation()
    {
        return $this->additionalInformation;
    }

    public function __toString()
    {
        return \chr($this->majorType << 5 | $this->additionalInformation);
    }
}
