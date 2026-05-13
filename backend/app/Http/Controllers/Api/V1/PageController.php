<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('is_active', true)->where('slug', $slug)->first();

        if (! $page) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }

        return response()->json([
            'data' => ['title' => $page->title, 'slug' => $page->slug, 'content' => $page->content],
        ]);
    }
}
