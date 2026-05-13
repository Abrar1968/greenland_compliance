<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Support\AdminResources;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'resources' => AdminResources::all(),
            'unreadMessages' => ContactMessage::where('is_read', false)->count(),
        ]);
    }
}
