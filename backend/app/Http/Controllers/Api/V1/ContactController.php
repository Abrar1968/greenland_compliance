<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactDepartment;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function info()
    {
        $settings = SiteSetting::firstOrCreate([]);

        return response()->json([
            'data' => [
                'banner_label' => 'Our Office',
                'address' => $settings->address,
                'phone' => $settings->primary_phone,
                'email' => $settings->primary_email,
                'map_embed_url' => $settings->map_embed_url,
                'office_image_url' => $settings->office_image_url,
                'social_links' => SocialLink::where('is_active', true)->orderBy('sort_order')->get(['platform', 'url']),
                'departments' => ContactDepartment::where('is_active', true)->orderBy('sort_order')->get(['id', 'title', 'email', 'sort_order']),
            ],
        ]);
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 422);
        }

        ContactMessage::create($validator->validated());

        return response()->json([
            'data' => ['message' => 'Your message has been received. We will get back to you shortly.'],
        ], 201);
    }
}
