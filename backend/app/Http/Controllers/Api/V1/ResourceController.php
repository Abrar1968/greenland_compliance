<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FormTemplate;
use App\Models\NewsPost;
use App\Models\Publication;

class ResourceController extends Controller
{
    public function publications()
    {
        return response()->json([
            'data' => Publication::where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => [
                'id' => $item->id, 'title' => $item->title, 'format' => $item->format, 'category' => $item->category, 'file_url' => $item->file_url, 'sort_order' => $item->sort_order,
            ]),
        ]);
    }

    public function forms()
    {
        $items = FormTemplate::where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => [
            'id' => $item->id, 'title' => $item->title, 'format' => $item->format, 'language' => $item->language, 'file_url' => $item->file_url, 'sort_order' => $item->sort_order, 'category_group' => $item->category_group,
        ]);

        return response()->json(['data' => $items->groupBy('category_group')->map(fn ($group) => $group->map(fn ($item) => collect($item)->except('category_group'))->values())]);
    }

    public function news()
    {
        return response()->json([
            'data' => NewsPost::where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => [
                'id' => $item->id, 'title' => $item->title, 'category' => $item->category, 'published_at' => $item->published_at?->format('Y-m-d'), 'image_url' => $item->image_url, 'format' => $item->format, 'external_url' => $item->external_url, 'sort_order' => $item->sort_order,
            ]),
        ]);
    }
}
