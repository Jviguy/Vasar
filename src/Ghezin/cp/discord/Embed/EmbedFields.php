<?php

declare(strict_types=1);

namespace Ghezin\cp\discord\Embed;

class EmbedFields extends EmbedMember
{
    /** @var EmbedField[] $fields*/
    private $fields;

    public function __construct(EmbedField ...$fields) {
        $this->fields = $fields;
    }

    public function Marshal()
    {
        foreach ($this->fields as $field) {
            $field->Marshal();
        }
    }

    public function UnMarshal()
    {
        foreach ($this->fields as $field) {
            $field->UnMarshal();
        }
    }

    public function GetMemberName(): string
    {
        return "fields";
    }
}