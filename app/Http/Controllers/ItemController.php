<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Item;
use App\Models\Room;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

use App\Exports\ItemsExport;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'room.location'])->where('is_active', true);
        
        // Pencarian berdasarkan nama item
        if ($request->has('search') && $request->search) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category) {
            $query->where('cat_id', $request->category);
        }
        
        // Filter berdasarkan ruangan
        if ($request->has('room') && $request->room) {
            $query->where('room_id', $request->room);
        }
        
        // Filter berdasarkan lokasi
        if ($request->has('location') && $request->location) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('location_id', $request->location);
            });
        }
        
        $items = $query->paginate(10);
        
        // Untuk dropdown filter
        $categories = Category::all();
        $rooms = Room::all();
        $locations = Location::all();
        
        return view('pages.items.index', [
            'items' => $items,
            'categories' => $categories,
            'rooms' => $rooms,
            'locations' => $locations,
            'request' => $request
        ]);
    }

    public function create()
    {
        $categories = category::all(); // Ambil semua kategori
        $rooms = Room::all(); // Ambil semua room
        return view('pages.items.create', compact('categories', 'rooms'));
    }


public function store(Request $request)
{
    $validatedData = $request->validate([
        'cat_id' => 'required|exists:categories,id',
        'item_name' => 'required|string|max:255',
        'qty' => 'required|integer|min:0',
        'good_qty' => 'required|integer|min:0',
        'broken_qty' => 'required|integer|min:0',
        'lost_qty' => 'required|integer|min:0',
        'room_id' => 'required|exists:rooms,id',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Validasi total quantity
    $total = $validatedData['good_qty'] + $validatedData['broken_qty'] + $validatedData['lost_qty'];
    if ($total > $validatedData['qty']) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['qty' => 'Total kondisi barang (baik + rusak + hilang) tidak boleh melebihi total quantity']);
    }

    $path = null;
    if ($request->hasFile('photo')) {
        $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('storage/uploads/items'), $filename);
        $path = 'uploads/items/' . $filename;
    }

    $item = Item::create([
        'cat_id' => $validatedData['cat_id'],
        'item_name' => $validatedData['item_name'],
        'qty' => $validatedData['qty'],
        'good_qty' => $validatedData['good_qty'],
        'broken_qty' => $validatedData['broken_qty'],
        'lost_qty' => $validatedData['lost_qty'],
        'room_id' => $validatedData['room_id'],
        'photo' => $path,
        'is_borrowable' => $request->has('is_borrowable')
    ]);

    return redirect('/item')->with('success', 'Item has been added');
}

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all(); // Ambil semua kategori
        $rooms = Room::all(); // Ambil semua room
        return view('pages.items.edit', compact('item', 'categories', 'rooms'));
    }
    
    public function show($id)
    {
        $item = Item::findOrFail($id);
        
        return view('pages.items.show', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cat_id' => 'required',
            'item_name' => 'required',
            'qty' => 'required|integer|min:0',
            'good_qty' => 'required|integer|min:0',
            'broken_qty' => 'required|integer|min:0',
            'lost_qty' => 'required|integer|min:0',
            'room_id' => 'required',
            'is_borrowable' => 'boolean'
        ]);

        // Validasi total quantity
        $total = $validatedData['good_qty'] + $validatedData['broken_qty'] + $validatedData['lost_qty'];
        if ($total > $validatedData['qty']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['qty' => 'Total kondisi barang (baik + rusak + hilang) tidak boleh melebihi total quantity']);
        }

        $item = Item::findOrFail($id);
        $item->update([
            'cat_id' => $validatedData['cat_id'],
            'item_name' => $validatedData['item_name'],
            'qty' => $validatedData['qty'],
            'good_qty' => $validatedData['good_qty'],
            'broken_qty' => $validatedData['broken_qty'],
            'lost_qty' => $validatedData['lost_qty'],
            'room_id' => $validatedData['room_id'],
            'is_borrowable' => $request->has('is_borrowable')
        ]);

        return redirect('/item')->with('success', 'Item has been updated');
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        $item->is_active = false;
        $item->save();

        return redirect('/item')->with('success', 'Item has been deactivated');
    }

 public function exportPdf(Request $request)
{
    if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
        abort(403, 'Unauthorized');
    }
    
    // Query data
    $query = Item::with(['category', 'room.location'])->where('is_active', true);
    
    // Collect filter info for the PDF title
    $filterInfo = [];
    
    // Apply search filter
    if ($request->has('search') && $request->search) {
        $query->where('item_name', 'like', '%' . $request->search . '%');
        $filterInfo[] = 'Pencarian: ' . $request->search;
    }
    
    // Apply category filter
    if ($request->has('category') && $request->category) {
        $query->where('cat_id', $request->category);
        $category = \App\Models\Category::find($request->category);
        if ($category) {
            $filterInfo[] = 'Kategori: ' . $category->cat_name;
        }
    }
    
    // Apply room filter
    if ($request->has('room') && $request->room) {
        $query->where('room_id', $request->room);
        $room = \App\Models\Room::find($request->room);
        if ($room) {
            $filterInfo[] = 'Ruangan: ' . $room->name;
        }
    }
    
    // Apply location filter
    if ($request->has('location') && $request->location) {
        $query->whereHas('room', function($q) use ($request) {
            $q->where('location_id', $request->location);
        });
        $location = \App\Models\Location::find($request->location);
        if ($location) {
            $filterInfo[] = 'Lokasi: ' . $location->name;
        }
    }
    
    $items = $query->get()->chunk(10);
    
    // Add filter information to the PDF
    $filterText = empty($filterInfo) ? 'Semua Item' : implode(', ', $filterInfo);
    
    $pdf = PDF::loadView('pages.items.pdf', [
        'items' => $items,
        'filterText' => $filterText
    ]);
    
    // Generate filename with date
    $filename = 'inventaris_' . date('Y-m-d') . '.pdf';
    
    return $pdf->download($filename);
}

public function exportExcel(Request $request)
{
    if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
        abort(403, 'Unauthorized');
    }

    // Query data
    $query = Item::with(['category', 'room.location'])->where('is_active', true);
    
    // Collect filter info for the filename
    $filterInfo = [];
    
    // Apply search filter
    if ($request->has('search') && $request->search) {
        $query->where('item_name', 'like', '%' . $request->search . '%');
        $filterInfo[] = 'search-' . str_replace(' ', '_', $request->search);
    }
    
    // Apply category filter
    if ($request->has('category') && $request->category) {
        $query->where('cat_id', $request->category);
        $category = \App\Models\Category::find($request->category);
        if ($category) {
            $filterInfo[] = 'category-' . str_replace(' ', '_', $category->cat_name);
        }
    }
    
    // Apply room filter
    if ($request->has('room') && $request->room) {
        $query->where('room_id', $request->room);
        $room = \App\Models\Room::find($request->room);
        if ($room) {
            $filterInfo[] = 'room-' . str_replace(' ', '_', $room->name);
        }
    }
    
    // Apply location filter
    if ($request->has('location') && $request->location) {
        $query->whereHas('room', function($q) use ($request) {
            $q->where('location_id', $request->location);
        });
        $location = \App\Models\Location::find($request->location);
        if ($location) {
            $filterInfo[] = 'location-' . str_replace(' ', '_', $location->name);
        }
    }
    
    $items = $query->get();
    
    // Generate filename with date and filters
    $filename = 'items_' . date('Y-m-d');
    if (!empty($filterInfo)) {
        $filename .= '_' . implode('_', $filterInfo);
    }
    $filename .= '.csv';
    
    // Persiapkan response sebagai file CSV
    $headers = [
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => 'attachment; filename=' . $filename,
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];
    
    $callback = function() use ($items, $request, $filterInfo) {
        // Buat file handle untuk output
        $file = fopen('php://output', 'w');
        
        // Tambahkan BOM (Byte Order Mark) agar Excel dapat membaca karakter Unicode dengan benar
        fputs($file, "\xEF\xBB\xBF");
        
        // Add filter information as a header row if filters applied
        if (!empty($filterInfo)) {
            fputcsv($file, ['Filter yang diterapkan: ' . implode(', ', $filterInfo)]);
            fputcsv($file, []); // Add empty row for spacing
        }
        
        // Tambahkan header
        fputcsv($file, ['No', 'Nama Item', 'Kategori', 'Lokasi', 'Ruangan', 'Total Qty', 'Qty Baik', 'Qty Rusak', 'Qty Hilang']);
        
        // Tambahkan data
        foreach ($items as $index => $item) {
            fputcsv($file, [
                $index + 1,
                $item->item_name,
                $item->category->cat_name,
                $item->room->location->name,
                $item->room->name,
                $item->qty,
                $item->good_qty,
                $item->broken_qty,
                $item->lost_qty
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

//Untuk menampilkan item via room
public function byRoom($roomId)
{
    $room = Room::with(['items.category', 'location'])->findOrFail($roomId);
    $items = $room->items()->where('is_active', true)->paginate(10);
    $location = $room->location;
    $filterText = 'Items in Room: ' . $room->name;

    return view('pages.items.by-room', compact('room', 'items', 'location', 'filterText'));
}

public function toggleBorrowable($id)
{
    $item = Item::findOrFail($id);
    $item->is_borrowable = !$item->is_borrowable;
    $item->save();

    return redirect()->back()->with('success', 'Status peminjaman berhasil diubah.');
}

}
