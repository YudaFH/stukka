<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\ScheduledEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanEventController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'all'); // Default 'all' or 'my'
        $status = $request->query('status');
        $search = $request->query('search');
        $month = $request->query('month');

        $query = ScheduledEvent::with(['crew', 'crewAssignments.user']);

        if ($tab === 'my') {
            $query->whereHas('crew', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%");
            });
        }

        if (!empty($month)) {
            $query->where('start_date', 'like', "{$month}%");
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(9)->withQueryString();

        $stats = [
            'total'     => ScheduledEvent::count(),
            'my'        => $user->assignedEvents()->count(),
            'scheduled' => ScheduledEvent::where('status', 'scheduled')->count(),
            'ongoing'   => ScheduledEvent::where('status', 'ongoing')->count(),
            'completed' => ScheduledEvent::where('status', 'completed')->count(),
        ];

        return view('karyawan.events.index', compact('events', 'tab', 'status', 'search', 'month', 'stats', 'user'));
    }

    public function show($id)
    {
        $event = ScheduledEvent::with(['crew', 'crewAssignments.user'])->findOrFail($id);
        $user = Auth::user();

        // Cari penugasan role user ini
        $myAssignment = $event->crew->firstWhere('id', $user->id);

        return view('karyawan.events.show', compact('event', 'myAssignment'));
    }
}
