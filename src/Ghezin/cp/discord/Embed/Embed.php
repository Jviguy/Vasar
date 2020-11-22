<?php

declare(strict_types=1);

namespace Ghezin\cp\discord\Embed;

use ArrayAccess;

class Embed implements ArrayAccess
{
    /** @var $json EmbedMember[] */
    private $json;

    public function offsetSet($offset, $value) {
        if (!isset($offset)) {
            $this->json[] = $value;
        } else {
            $this->json[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->json[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->json[$offset]);
    }

    public function offsetGet($offset) {
        return $this->json[$offset];
    }

    public function Marshal() {
        foreach ($this->json as $member) {
            $member->Marshal();
        }
    }

    public function UnMarshal() {
        foreach ($this->json as $member) {
            $member->UnMarshal();
        }
    }
}