<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace CBOR\Test\Type;

use CBOR\StringStream;

final class OtherTest extends BaseTestCase
{
    /**
     * @test
     * @dataProvider getDataSet
     */
    public function aSignedIntegerCanBeParsed($data)
    {
        $stream = new StringStream(\Safe\hex2bin($data));
        $object = $this->getDecoder()->decode($stream);
        $object->getNormalizedData();
        static::assertEquals($data, bin2hex((string) $object));
    }

    public function getDataSet(): array
    {
        return [
            [
                'f4',
            ], [
                'f5',
            ], [
                'f6',
            ], [
                'f7',
            ], [
                'f0',
            ], [
                'f818',
            ], [
                'f8ff',
            ],
        ];
    }
}
