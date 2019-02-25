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

abstract class OtherObject extends AbstractCBORObject
{
     const MAJOR_TYPE = 0b111;

    /**
     * @var string|null
     */
    protected $data;

    /**
     * @return int[]
     */
    abstract public static function supportedAdditionalInformation(): array;

    abstract public static function createFromLoadedData($additionalInformation, $data): self;

    public function __construct($additionalInformation, $data)
    {
        parent::__construct(self::MAJOR_TYPE, $additionalInformation);
        $this->data = $data;
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
