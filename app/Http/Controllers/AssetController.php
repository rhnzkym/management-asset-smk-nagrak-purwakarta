<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Borrow;
use App\Models\Room;
use App\Models\Location;
use App\Models\ItemLoan;
use App\Models\RoomLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{

 public function index(Request $request)
{
    // Ambil semua kategori yang memiliki item yang bisa dipinjam (is_borrowable = true)
    $kategori = Category::whereHas('items', function ($query) {
        $query->where('is_borrowable', true);
    })->get();

    // Ambil semua rooms yang memiliki item yang bisa dipinjam (is_borrowable = true)
    $rooms = Room::whereHas('items', function ($query) {
        $query->where('is_borrowable', true);
    })->get();

    // Query utama untuk ambil item - tampilkan semua item yang bisa dipinjam
    // terlepas dari nilai good_qty
    // Tambahkan filter untuk only show active items (is_active = 1)
    $query = Item::with(['category', 'room', 'itemLoans' => function($q) {
        $q->where('status', 'pinjam');
    }])
    ->where('is_borrowable', true)
    ->where('is_active', true);

    // Filter nama item
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('item_name', 'like', "%$search%")
              ->orWhereHas('category', function ($qc) use ($search) {
                  $qc->where('cat_name', 'like', "%$search%");
              })
              ->orWhereHas('room', function ($qr) use ($search) {
                  $qr->where('name', 'like', "%$search%");
              });
        });
    }

    // Filter kategori
    if ($request->filled('kategori_id')) {
        $query->where('cat_id', $request->kategori_id);
    }

    // Filter room
    if ($request->filled('room_id')) {
        $query->where('room_id', $request->room_id);
    }

    // Jika tanpa filter, order berdasarkan qty terbanyak
    if (!$request->filled('search') && !$request->filled('kategori_id') && !$request->filled('room_id')) {
        $query->orderBy('qty', 'desc');
    }

    $items = $query->paginate(9)->withQueryString();

    // Hitung jumlah yang tersedia untuk setiap item
    foreach ($items as $item) {
        // Get total borrowed items that are still in 'pinjam' status
        $borrowed = $item->itemLoans()
            ->where('status', 'dipinjam')
            ->sum('jumlah');
        
        // Calculate available quantity based on good_qty minus borrowed
        $item->available = $item->good_qty - $borrowed;
        
        // Get total broken and lost items
        $item->broken = $item->broken_qty;
        $item->lost = $item->lost_qty;
    }

    return view('assets.index', compact('items', 'kategori', 'rooms'));
}
    

    public function showPinjamForm($id)
    {
        $item = Item::findOrFail($id);
        return view('assets.form_pinjam', compact('item'));
    }

    public function pinjam(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $item = Item::with(['itemLoans' => function($q) {
            $q->where('status', 'pinjam');
        }])->findOrFail($id);

        // Hitung jumlah yang tersedia
        $borrowed = $item->itemLoans->sum('jumlah');
        $available = $item->good_qty - $borrowed;

        // Cek apakah barang masih tersedia
        if ($available < $request->jumlah) {
            return redirect()->back()
                ->with('error', 'Barang tidak tersedia dalam jumlah yang diminta. Tersedia: ' . $available)
                ->withInput();
        }

        // Upload foto
        $photoPath = $request->file('photo')->store('borrow-photos', 'public');

        // Buat peminjaman baru
        ItemLoan::create([
            'user_id' => auth()->id(),
            'item_id' => $id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => now(),
            'status' => 'pending',
            'photo' => $photoPath
        ]);

        return redirect()->route('assets.borrow.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibuat! Silakan tunggu persetujuan dari admin.');
    }

    public function requestReturn($id) 
    {
        $loan = ItemLoan::findOrFail($id);

        if ($loan->status != 'pinjam') {
            return back()->with('error', 'Asset ini tidak dapat dikembalikan.');
        }
    
        $loan->status = 'pending';
        $loan->save();
    
        return back()->with('success', 'Permintaan pengembalian dikirim. Menunggu konfirmasi admin.');
    }

    public function confirmReturn($id)
    {
        $loan = ItemLoan::findOrFail($id);
        
        if ($loan->status != 'pinjam') {
            return redirect()->back()->with('error', 'Status peminjaman tidak valid.');
        }

        // Update status peminjaman
        $loan->status = 'kembali';
        $loan->tanggal_kembali = now();
        $loan->save();

        return redirect()->back()->with('success', 'Pengembalian barang berhasil dikonfirmasi.');
    }

    public function rejectReturn($id)
    {
        $loan = ItemLoan::findOrFail($id);

        if ($loan->status != 'pending') {
            return back()->with('error', 'Asset ini belum minta dikembalikan.');
        }

        // Batalkan permintaan pengembalian
        $loan->status = 'hilang';
        $loan->save();

        return back()->with('success', 'Permintaan pengembalian dibatalkan.');
    }

    public function myBorrows()
    {
        $itemLoans = ItemLoan::with(['user', 'item'])
            ->where('user_id', Auth::id())
            ->orderByRaw("FIELD(status, 'pinjam', 'pending', 'kembali')")
            ->orderByDesc('tanggal_pinjam')
            ->paginate(10);

        return view('assets.borrow_index', compact('itemLoans'));
    }

    public function myBorrowsRoom()
    {
        $roomLoans = RoomLoan::with('room')
            ->where('user_id', Auth::id())
            ->orderByRaw("FIELD(status, 'pinjam', 'pending', 'kembali')")
            ->orderByDesc('tanggal_pinjam')
            ->paginate(10);

        return view('rooms.borrow_index', compact('roomLoans'));
    }

    public function showBorrowRoom($id)
    {
        $roomLoan = RoomLoan::with(['room', 'itemLoans.item'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('rooms.view_borrow', compact('roomLoan'));
    }


    public function indexBorrowRoom(Request $request)
    {
        $query = Room::with('location')->where('status', '!=', 0);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->location_id) {
            $query->where('location_id', $request->location_id);
        }

        return view('rooms.index', [
            'rooms' => $query->paginate(9),
            'locations' => Location::all(),
        ]);
    }

    public function showPinjamFormRoom($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.form_pinjam', compact('room'));
    }

    public function pinjamRoom(Request $request, $roomId)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
        ]);

        $room = Room::with('items')->findOrFail($roomId);

        // Simpan data peminjaman ruangan
        // $peminjaman = RoomLoan::create([
        //     'user_id' => auth()->id(),
        //     'room_id' => $room->id,
        //     'keterangan' => $request->keterangan,
        //     'status' => 'diproses',
        // ]);

        // Loop item dalam ruangan dan buat record peminjaman per item
        // foreach ($room->items as $item) {
        //     ItemLoan::create([
        //         'room_loan_id' => $peminjaman->id,
        //         'item_id' => $item->id,
        //         'qty' => $item->qty, // misalnya semua ikut dipinjam full
        //     ]);
        // }

        return redirect()->route('rooms.index')->with('success', 'Peminjaman ruangan berhasil diajukan!');
    }

    public function approveLoan($id)
    {
        $loan = ItemLoan::with(['item' => function($q) {
            $q->with(['itemLoans' => function($q) {
                $q->where('status', 'pinjam');
            }]);
        }])->findOrFail($id);

        $item = $loan->item;

        // Cek apakah ada cukup barang dalam kondisi baik
        if ($item->good_qty < $loan->jumlah) {
            // Update status menjadi ditolak
            $loan->status = 'ditolak';
            $loan->save();
            return redirect()->back()->with('error', 'Peminjaman ditolak karena jumlah barang kondisi baik tidak mencukupi. Tersedia: ' . $item->good_qty);
        }

        // Kurangi jumlah barang kondisi baik
        $item->good_qty -= $loan->jumlah;
        $item->save();

        // Update status peminjaman
        $loan->status = 'pinjam';
        $loan->tanggal_pinjam = now();
        $loan->save();

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function rejectLoan($id)
    {
        $loan = ItemLoan::findOrFail($id);
        $loan->status = 'ditolak';
        $loan->save();

        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak.');
    }

}
