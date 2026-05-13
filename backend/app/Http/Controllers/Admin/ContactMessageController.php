<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        return view('admin.contact-messages.index', ['items' => ContactMessage::latest()->paginate(20)]);
    }

    public function show(int $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => true]);

        return view('admin.contact-messages.show', compact('message'));
    }

    public function markRead(int $id)
    {
        ContactMessage::findOrFail($id)->update(['is_read' => true]);

        return back()->with('status', 'Message marked as read.');
    }

    public function destroy(int $id)
    {
        ContactMessage::findOrFail($id)->delete();

        return redirect()->route('admin.contact-messages.index')->with('status', 'Message deleted.');
    }
}
