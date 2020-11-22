<?php

declare(strict_types=1);

namespace Ghezin\cp\discord\Embed;

class EmbedThumbnail extends EmbedMember
{
    /** @var string $url the Thumbnail url */
    private $url;

    public function Marshal()
    {
        $this["url"] = $this->url;
    }

    public function UnMarshal()
    {
        unset($this["url"]);
    }

    public function GetMemberName(): string
    {
        return "thumbnail";
    }
}