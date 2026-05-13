<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function categories()
    {
        return response()->json([
            'data' => ServiceCategory::orderBy('sort_order')->get(['id', 'label', 'slug', 'sort_order']),
        ]);
    }

    public function index(Request $request)
    {
        $query = ServiceCategory::with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->orderBy('sort_order');

        if ($request->filled('category')) {
            $category = $query->where('slug', $request->string('category'))->first();

            if (! $category) {
                return response()->json(['error' => 'Resource not found.'], 404);
            }

            return response()->json(['data' => $this->transformCategory($category)]);
        }

        return response()->json([
            'data' => $query->get()->map(fn ($category) => $this->transformCategory($category)),
        ]);
    }

    private function transformCategory(ServiceCategory $category): array
    {
        return [
            'id' => $category->id,
            'label' => $category->label,
            'slug' => $category->slug,
            'sort_order' => $category->sort_order,
            'services' => $category->services->map(fn ($service) => [
                'id' => $service->id,
                'title' => $service->title,
                'price' => $service->price,
                'description' => $service->description,
                'badge' => $service->badge,
                'sort_order' => $service->sort_order,
            ])->values(),
        ];
    }
}
