<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('location', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(10)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:workshop,seminar,exhibition,bazar'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,open,closed'],
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:workshop,seminar,exhibition,bazar'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,open,closed'],
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function toggleStatus(Event $event)
    {
        if ($event->status === 'open') {
            $event->update(['status' => 'closed']);
            $message = 'Event ditutup.';
        } else {
            $event->update(['status' => 'open']);
            $message = 'Event dibuka kembali.';
        }

        return back()->with('success', $message);
    }
}
