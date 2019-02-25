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

interface CBORObject
{
    public function getMajorType();

    public function getAdditionalInformation();

    /**
     * @return mixed|null
     */
    public function getNormalizedData($ignoreTags = false);

    public function __toString();
}
