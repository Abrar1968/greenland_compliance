<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminResources;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    public function update(Request $request, string $model)
    {
        $resource = AdminResources::get($model);
        $data = $request->validate(['orders' => ['required', 'array'], 'orders.*' => ['integer']]);

        foreach ($data['orders'] as $id => $sortOrder) {
            $resource['model']::whereKey($id)->update(['sort_order' => $sortOrder]);
        }

        return back()->with('status', $resource['title'].' order updated.');
    }
}
