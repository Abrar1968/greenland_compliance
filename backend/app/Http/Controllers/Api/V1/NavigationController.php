<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NavItem;

class NavigationController extends Controller
{
    public function index()
    {
        $items = NavItem::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'label', 'href', 'location']);

        return response()->json([
            'data' => [
                'header' => $this->forLocation($items, 'header'),
                'footer_quick' => $this->forLocation($items, 'footer_quick'),
                'footer_services' => $this->forLocation($items, 'footer_services'),
                'footer_policy' => $this->forLocation($items, 'footer_policy'),
            ],
        ]);
    }

    private function forLocation($items, string $location): array
    {
        return $items->where('location', $location)
            ->map(fn ($item) => ['id' => $item->id, 'label' => $item->label, 'href' => $item->href])
            ->values()
            ->all();
    }
}
