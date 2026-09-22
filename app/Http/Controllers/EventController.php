<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()->where('status', 'open');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('location', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(9)->withQueryString();

        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::withCount('participants')->findOrFail($id);

        return view('events.show', compact('event'));
    }
}
