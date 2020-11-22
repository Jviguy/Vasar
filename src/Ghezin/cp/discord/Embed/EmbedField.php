<?php


namespace Ghezin\cp\discord\Embed;


class EmbedField extends EmbedMember
{
    /** @var string $name */
    private $name;

    /** @var string $content */
    private $content;

    /** @var bool $inline */
    private $inline;

    public function __construct(string $name, string $content, bool $inline = false) {
        $this->name = $name;
        $this->content = $content;
        $this->inline = $inline;
    }

    public function Marshal()
    {
        $this["name"] = $this->name;
        $this["content"] = $this->content;
        $this["inline"] = $this->inline;
    }

    public function UnMarshal()
    {
        unset($this["name"]);
        unset($this["content"]);
        unset($this["inline"]);
    }

    public function GetMemberName(): string
    {
        return "field";
    }
}