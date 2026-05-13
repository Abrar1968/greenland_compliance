<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::where('is_active', true)->orderBy('sort_order');

        if ($request->filled('page')) {
            $query->whereIn('page', [$request->string('page')->toString(), 'global']);
        }

        return response()->json([
            'data' => $query->get()->map(fn ($item) => [
                'id' => $item->id, 'author' => $item->author, 'role' => $item->role, 'quote' => $item->quote, 'avatar_url' => $item->avatar_url, 'sort_order' => $item->sort_order,
            ]),
        ]);
    }
}
