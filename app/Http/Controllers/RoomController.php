<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomController extends Controller
{

    public function index(Request $request)
    {
        $query = Room::with('location');
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Apply location filter if provided
        if ($request->has('location') && $request->location) {
            $query->where('location_id', $request->location);
        }
        
        $rooms = $query->paginate(10)->withQueryString();
        return view('pages.rooms.index', compact('rooms'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
            abort(403, 'Unauthorized');
        }

        $locations = Location::all();
        return view('pages.rooms.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'area' => 'required|numeric',
            'status' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('storage/uploads/rooms'), $filename);
            $validated['photo'] = 'uploads/rooms/' . $filename;
        }

        Room::create($validated);
        return redirect('/rooms')->with('success', 'Room added successfully');
    }

    public function show($id)
    {
        $room = Room::with(['location', 'items'])->findOrFail($id);
        return view('pages.rooms.show', compact('room'));
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $locations = Location::all();
        return view('pages.rooms.edit', compact('room', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'area' => 'required|numeric',
            'status' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('storage/uploads/rooms'), $filename);
            $validated['photo'] = 'uploads/rooms/' . $filename;
        }

        $room->update($validated);
        return redirect('/rooms')->with('success', 'Room updated successfully');
    }

    public function exportPdf(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
            abort(403, 'Unauthorized');
        }

        // Query data
        $query = Room::with('location');
        
        // Collect filter info for the PDF title
        $filterInfo = [];
        
        // Apply location filter
        if ($request->has('location') && $request->location) {
            $query->where('location_id', $request->location);
            $location = Location::find($request->location);
            if ($location) {
                $filterInfo[] = 'Lokasi: ' . $location->name;
            }
        }

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $filterInfo[] = 'Pencarian: ' . $request->search;
        }
        
        $rooms = $query->get()->chunk(10);
        
        // Add filter information to the PDF
        $filterText = empty($filterInfo) ? 'Semua Ruangan' : implode(', ', $filterInfo);
        
        $pdf = Pdf::loadView('pages.rooms.pdf_rooms', [
            'rooms' => $rooms,
            'filterText' => $filterText
        ]);
        
        // Generate filename with date
        $filename = 'ruangan_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function byLocation($id)
    {
    $location = Location::with('rooms')->findOrFail($id);
    $rooms = $location->rooms;

    return view('pages.rooms.by-location', compact('location', 'rooms'));
}

    public function destroy($id)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
            abort(403, 'Unauthorized');
        }
        
        $room = Room::findOrFail($id);
        
        // Check if room has any items associated with it
        if($room->items && $room->items->count() > 0) {
            // Room has items, prevent deletion
            return redirect('/rooms')->with('error', 'Tidak dapat menghapus ruangan karena ada assets yang menggunakan ruangan ini.');
        }
        
        // Check if room has any active room loans
        if($room->roomLoans && $room->roomLoans->where('status', '!=', 'returned')->count() > 0) {
            return redirect('/rooms')->with('error', 'Ruangan tidak dapat dihapus karena sedang dipinjam.');
        }
        
        // Delete the room photo if exists
        if($room->photo && file_exists(public_path('storage/' . $room->photo))) {
            unlink(public_path('storage/' . $room->photo));
        }
        
        $room->delete();
        return redirect('/rooms')->with('success', 'Ruangan berhasil dihapus.');
    }
}
