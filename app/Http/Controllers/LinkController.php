<?php

namespace App\Http\Controllers;

use App\Http\Requests\Link\StoreLinkRequest;
use App\Http\Resources\Link\LinkStatsResource;
use App\Http\Resources\Link\StoreLinkResource;
use App\Services\LinkService;
use Illuminate\Http\RedirectResponse;

class LinkController extends Controller
{
    public function __construct(private readonly LinkService $service) {}

    public function store(StoreLinkRequest $request): StoreLinkResource
    {
        return new StoreLinkResource($this->service->createShortUrl($request->validated('url')));
    }

    function redirect(string $code): RedirectResponse
    {
        return redirect()->away($this->service->getLinkByCode($code));
    }

    public function stats(string $code): LinkStatsResource
    {
        return new LinkStatsResource($this->service->getLinkStats($code));
    }
}
