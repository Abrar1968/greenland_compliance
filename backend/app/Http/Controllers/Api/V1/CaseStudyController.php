<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    public function index(Request $request)
    {
        $query = CaseStudy::with('category')->where('is_active', true)->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }

        return response()->json(['data' => $query->get()->map(fn ($study) => $this->summary($study))]);
    }

    public function categories()
    {
        return response()->json([
            'data' => CaseStudyCategory::orderBy('sort_order')->get(['id', 'name', 'slug', 'sort_order']),
        ]);
    }

    public function show(string $slug)
    {
        $study = CaseStudy::with('category')->where('is_active', true)->where('slug', $slug)->first();

        if (! $study) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }

        return response()->json([
            'data' => $this->summary($study) + ['body' => $study->body],
        ]);
    }

    private function summary(CaseStudy $study): array
    {
        return [
            'id' => $study->id,
            'title' => $study->title,
            'slug' => $study->slug,
            'image_url' => $study->image_url,
            'summary' => $study->summary,
            'sort_order' => $study->sort_order,
            'category' => [
                'id' => $study->category->id,
                'name' => $study->category->name,
                'slug' => $study->category->slug,
            ],
        ];
    }
}
