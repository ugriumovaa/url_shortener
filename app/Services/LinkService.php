<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Str;

class LinkService
{
    public static function generateShortCode(): array|false|string
    {
        return Str::lower(Str::random(6));
    }

    public function createShortUrl(string $link): Link
    {
        $existing = Link::query()
            ->where('url', $link)
            ->first();

        if ($existing) {
            return $existing;
        }

        $code = $this->generateShortCode();
        return Link::query()->create([
            'url' => $link,
            'code' => $code,
        ]);
    }

    public function getLinkByCode(string $code): string
    {
        $link = Link::query()
            ->where('code', $code)
            ->firstOrFail();

        $link->increment('clicks');

        return $link->url;
    }

    public function getLinkStats(string $code): Link
    {
        return Link::query()
            ->where('code', $code)
            ->firstOrFail();

    }

}
