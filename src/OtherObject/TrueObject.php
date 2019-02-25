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

use CBOR\OtherObject as Base;

final class TrueObject extends Base
{
    public function __construct()
    {
        parent::__construct(21, null);
    }

    public static function supportedAdditionalInformation(): array
    {
        return [21];
    }

    public static function createFromLoadedData($additionalInformation, $data): Base
    {
        return new self();
    }

    public function getNormalizedData($ignoreTags = false)
    {
        return true;
    }
}
