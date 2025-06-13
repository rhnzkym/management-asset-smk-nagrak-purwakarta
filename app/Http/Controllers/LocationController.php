<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::with('rooms.items');
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
        }
        
        $locations = $query->paginate(10)->withQueryString();
        return view('pages.locations.index', compact('locations'));
    }

    public function show($id)
    {
        $location = Location::with('rooms.items')->findOrFail($id);
        return view('pages.locations.show', compact('location'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
        abort(403, 'Unauthorized');
        }
        return view('pages.locations.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'area' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
        $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('storage/uploads/locations'), $filename);
        $path = 'uploads/locations/' . $filename;
    }

        Location::create([
            'name' => $request->name,
            'address' => $request->address,
            'area' => $request->area,
            'photo' => $path ?? null, 
        ]);

        return redirect('/locations')->with('success', 'Location has been added');
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return view('pages.locations.edit', compact('location'));
    }
    
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'area' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $location = Location::findOrFail($id);
        $updateData = [
            'name' => $request->name,
            'address' => $request->address,
            'area' => $request->area,
        ];

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($location->photo) {
                $oldPhotoPath = public_path('storage/' . $location->photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            
            // Upload foto baru
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('storage/uploads/locations'), $filename);
            $updateData['photo'] = 'uploads/locations/' . $filename;
        }

        $location->update($updateData);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui!');
    }
    
    public function exportPdf(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
            abort(403, 'Unauthorized');
        }

        // Query data
        $query = Location::withCount('rooms');
        
        // Collect filter info for the PDF title
        $filterInfo = [];
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $filterInfo[] = 'Pencarian: ' . $request->search;
        }
        
        $locations = $query->get()->chunk(10);
        
        // Add filter information to the PDF
        $filterText = empty($filterInfo) ? 'Semua Lokasi' : implode(', ', $filterInfo);
        
        // Load the PDF view with the locations data
        $pdf = PDF::loadView('pages.locations.pdf', [
            'locations' => $locations,
            'filterText' => $filterText
        ]);

        // Generate filename with date
        $filename = 'lokasi_' . date('Y-m-d') . '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        
        // Cek apakah lokasi memiliki ruangan yang berisi asset
        $hasItems = false;
        foreach ($location->rooms as $room) {
            if ($room->items->count() > 0) {
                $hasItems = true;
                break;
            }
        }
        
        if ($hasItems) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus lokasi karena ada assets yang menggunakan lokasi ini.');
        }
        
        // Cek apakah lokasi masih memiliki ruangan
        if ($location->rooms()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus lokasi karena masih memiliki ruangan!');
        }
        
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus!');
    }
}
