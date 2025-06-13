<?php

namespace App\Http\Controllers;

use App\Models\RoomLoan;
use App\Models\ItemLoan;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomLoanController extends Controller
{
    public function index(Request $request)
    {
        $statuses = ['pending', 'pinjam', 'kembali'];
        $query = RoomLoan::with(['user', 'room'])
            ->whereIn('status', $statuses);

        // Apply status filter if provided
        if ($request->filled('status') && in_array($request->status, $statuses)) {
            $query->where('status', $request->status);
        }

        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
        }

        // Apply date sorting
        if ($request->filled('sort')) {
            if ($request->sort === 'newest') {
                $query->orderBy('tanggal_pinjam', 'desc');
            } elseif ($request->sort === 'oldest') {
                $query->orderBy('tanggal_pinjam', 'asc');
            }
        } else {
            $query->orderBy('tanggal_pinjam', 'desc'); // Default to newest
        }

        $room_loans = $query->paginate(10)->withQueryString();

        return view('pages.loans.room-loan', compact('room_loans', 'statuses'));
    }

    public function show($id)
    {
        $roomLoan = RoomLoan::with(['user', 'room.items'])->findOrFail($id);
        return view('pages.loans.view-room', compact('roomLoan'));
    }


    public function create(Room $room)
    {
        $items = $room->items;
        return view('room_loans.create', compact('room', 'items'));
    }

    public function store(Request $request, Room $room)
    {
        DB::beginTransaction();

        try {
            $status = 'pending';
            $tanggalPinjam = Carbon::now();

            $roomLoan = RoomLoan::create([
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'status' => $status,
                'tanggal_pinjam' => $tanggalPinjam,
            ]);

            foreach ($room->items as $item) {
                ItemLoan::create([
                    'user_id' => Auth::id(),
                    'item_id' => $item->id,
                    'room_loan_id' => $roomLoan->id,
                    'jumlah' => 1, // default jumlah, bisa ditambahkan input jika perlu
                    'status' => $status,
                    'tanggal_pinjam' => $tanggalPinjam,
                ]);
            }

            DB::commit();
            return redirect()->route('room_loans.index')->with('success', 'Berhasil meminjam ruangan beserta isi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal meminjam ruangan: ' . $e->getMessage());
        }
    }

    
    public function exportPdf()
    {
        $room_loans = RoomLoan::with(['user', 'room'])
        ->latest()
        ->get()
        ->chunk(10); // Supaya bisa pecah halaman PDF
        $pdf = PDF::loadView('pages.loans.pdf-room', compact('room_loans'));
        return $pdf->download('room-loans.pdf');
    }


}
