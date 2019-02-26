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

final class StringStream implements Stream
{
    /**
     * @var resource
     */
    private $resource;

    public function __construct($data)
    {
        $resource = \Safe\fopen('php://memory', 'r+');
        \Safe\fwrite($resource, $data);
        \Safe\rewind($resource);
        $this->resource = $resource;
    }

    public function read($length)
    {
        if (0 === $length) {
            return '';
        }
        $data = \Safe\fread($this->resource, $length);
        if (mb_strlen($data, '8bit') !== $length) {
            throw new \InvalidArgumentException(\Safe\sprintf('Out of range. Expected: %d, read: %d.', $length, mb_strlen($data, '8bit')));
        }

        return $data;
    }
}
