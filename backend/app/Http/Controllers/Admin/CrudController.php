<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminResources;
use App\Support\MediaUploader;
use Illuminate\Http\Request;

abstract class CrudController extends Controller
{
    abstract protected function resourceKey(): string;

    public function index()
    {
        $resource = AdminResources::get($this->resourceKey());
        $query = $resource['model']::query();
        if (in_array('sort_order', $resource['fields'], true)) {
            $query->orderBy('sort_order');
        }
        $items = $query->latest('id')->paginate(20);

        return view('admin.crud.index', compact('resource', 'items'));
    }

    public function create()
    {
        $resource = AdminResources::get($this->resourceKey());
        $item = new $resource['model'];
        $options = $this->options($resource);

        return view('admin.crud.create', compact('resource', 'item', 'options'));
    }

    public function store(Request $request, MediaUploader $uploader)
    {
        $resource = AdminResources::get($this->resourceKey());
        $data = $this->validated($request, $resource);
        $data = $this->handleUploads($request, $resource, $uploader, $data);
        $resource['model']::create($data);

        return redirect()->route('admin.'.$resource['route'].'.index')->with('status', $resource['title'].' created.');
    }

    public function edit(int $id)
    {
        $resource = AdminResources::get($this->resourceKey());
        $item = $resource['model']::findOrFail($id);
        $options = $this->options($resource);

        return view('admin.crud.edit', compact('resource', 'item', 'options'));
    }

    public function show(int $id)
    {
        $resource = AdminResources::get($this->resourceKey());

        return redirect()->route('admin.'.$resource['route'].'.edit', $id);
    }

    public function update(Request $request, MediaUploader $uploader, int $id)
    {
        $resource = AdminResources::get($this->resourceKey());
        $item = $resource['model']::findOrFail($id);
        $data = $this->validated($request, $resource, $item);
        $data = $this->handleUploads($request, $resource, $uploader, $data, $item);
        $item->update($data);

        return redirect()->route('admin.'.$resource['route'].'.index')->with('status', $resource['title'].' updated.');
    }

    public function destroy(MediaUploader $uploader, int $id)
    {
        $resource = AdminResources::get($this->resourceKey());
        $item = $resource['model']::findOrFail($id);

        foreach (array_keys($resource['images'] ?? []) as $field) {
            $uploader->delete($item->{$field});
        }
        foreach (array_keys($resource['files'] ?? []) as $field) {
            $uploader->delete($item->{$field});
        }

        $item->delete();

        return redirect()->route('admin.'.$resource['route'].'.index')->with('status', $resource['title'].' deleted.');
    }

    private function validated(Request $request, array $resource, $item = null): array
    {
        $rules = [];
        foreach ($resource['fields'] as $field) {
            $rules[$field] = str_ends_with($field, '_id') ? ['required', 'integer'] : ['nullable', 'string'];
            if (in_array($field, ['title', 'label', 'name', 'author', 'question', 'email', 'slug', 'year', 'published_at'], true)) {
                $rules[$field] = ['required', 'string'];
            }
            if ($field === 'email') {
                $rules[$field][] = 'email';
            }
            if ($field === 'is_active') {
                $rules[$field] = ['nullable', 'boolean'];
            }
            if ($field === 'sort_order') {
                $rules[$field] = ['nullable', 'integer'];
            }
        }
        foreach ($resource['images'] ?? [] as $field => $_dir) {
            $rules[$field] = [in_array($field, $resource['required_images'] ?? [], true) && ! $item ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'];
        }
        foreach ($resource['files'] ?? [] as $field => $_dir) {
            $rules[$field] = ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:20480'];
        }

        $data = $request->validate($rules);
        foreach ($resource['fields'] as $field) {
            if ($field === 'is_active') {
                $data[$field] = $request->boolean($field);
            }
        }

        return $data;
    }

    private function handleUploads(Request $request, array $resource, MediaUploader $uploader, array $data, $item = null): array
    {
        foreach ($resource['images'] ?? [] as $field => $directory) {
            if ($request->hasFile($field)) {
                $data[$field] = $uploader->storeImage($request->file($field), $directory, $item?->{$field});
            }
        }
        foreach ($resource['files'] ?? [] as $field => $directory) {
            if ($request->hasFile($field)) {
                $data[$field] = $uploader->storeFile($request->file($field), $directory, $item?->{$field});
            }
        }

        return $data;
    }

    private function options(array $resource): array
    {
        $options = [];
        foreach ($resource['selects'] ?? [] as $field => [$model, $label]) {
            $options[$field] = $model::orderBy($label)->pluck($label, 'id');
        }

        return $options;
    }
}
