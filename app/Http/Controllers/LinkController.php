<?php

namespace App\Http\Controllers;

use App\Http\Requests\Link\StoreLinkRequest;
use App\Http\Resources\Link\StoreLinkResource;
use App\Models\Link;
use App\Services\LinkService;

class LinkController extends Controller
{
    public function __construct(private readonly LinkService $service) {}

    public function store(StoreLinkRequest $request): StoreLinkResource
    {
        $link = $this->service->createShortUrl($request->validated('url'));

        return new StoreLinkResource($link);
    }
}
