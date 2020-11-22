<?php

declare(strict_types=1);

namespace Ghezin\cp\discord\Embed;

class EmbedAuthor extends EmbedMember
{
    /** @var string $name the name of the Author */
    private $name;

    /** @var string $url the url for the Author */
    private $url;

    /** @var string $iconurl the url for the icon */
    private $iconurl;

    public function __construct(string $name, string $url = null, string $iconurl = null) {
        $this->name = $name;
        $this->url = $url;
        $this->iconurl = $iconurl;
    }


    public function Marshal()
    {
        $this["name"] = $this->name;
        $this["url"] = $this->url;
        $this["icon_url"] = $this->iconurl;
    }

    public function UnMarshal()
    {
        unset($this["name"]);
        unset($this["url"]);
        unset($this["icon_url"]);
    }

    public function GetMemberName(): string
    {
        return "author";
    }
}