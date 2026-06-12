<?php

namespace App\Http\Resources\Link;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Link
 */
class LinkStatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url,
            'code' => $this->code,
            'clicks' => $this->clicks,
            'created_at' => $this->created_at,
        ];
    }
}
