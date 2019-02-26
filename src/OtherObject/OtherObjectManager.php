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

use CBOR\OtherObject;

class OtherObjectManager
{
    /**
     * @var string[]
     */
    private $classes = [];

    public function add($class)
    {
        foreach ($class::supportedAdditionalInformation() as $ai) {
            if ($ai < 0) {
                throw new \InvalidArgumentException('Invalid additional information.');
            }
            $this->classes[$ai] = $class;
        }
    }

    public function getClassForValue($value)
    {
        return array_key_exists($value, $this->classes) ? $this->classes[$value] : GenericObject::class;
    }

    public function createObjectForValue($value, $data): OtherObject
    {
        /** @var OtherObject $class */
        $class = $this->getClassForValue($value);

        return $class::createFromLoadedData($value, $data);
    }
}
