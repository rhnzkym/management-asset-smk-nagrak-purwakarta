<?php

namespace App\Http\Controllers;

use App\Models\ItemLoan;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class ItemLoanController extends Controller
{
    public function index(Request $request)
    {
        $statuses = ['pending', 'pinjam', 'kembali'];
        $query = ItemLoan::with(['item', 'user', 'room.room'])
            ->orderByRaw("FIELD(status, 'pending', 'pinjam', 'kembali') ASC")
            ->orderByRaw("ISNULL(room_loan_id) DESC");

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

        $loans = $query->paginate(10)->withQueryString();

        return view('pages.loans.index', compact('loans', 'statuses'));
    }

    public function create(Item $item)
    {
        return view('item_loans.create', compact('item'));
    }

    public function store(Request $request, Item $item)
    {
        // Validate that the requested quantity doesn't exceed available good condition items
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $item->good_qty,
        ], [
            'jumlah.max' => 'Jumlah peminjaman tidak boleh melebihi jumlah barang dengan kondisi baik (' . $item->good_qty . ')',
        ]);

        // Create new loan record
        ItemLoan::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'room_loan_id' => null,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
            'tanggal_pinjam' => Carbon::now(),
        ]);

        return redirect()->route('item_loans.index')->with('success', 'Berhasil meminjam item.');
    }

    public function exportPdf(Request $request)
    {
        $statuses = ['pending', 'pinjam', 'kembali'];
        $query = ItemLoan::with(['item', 'user', 'room.room'])
            ->orderByRaw("FIELD(status, 'pending', 'pinjam', 'kembali') ASC");
            
        // Collect filter info for the PDF title
        $filterInfo = [];

        // Apply status filter if provided
        if ($request->filled('status') && in_array($request->status, $statuses)) {
            $query->where('status', $request->status);
            $filterInfo[] = 'Status: ' . ucfirst($request->status);
        }

        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
            $filterInfo[] = 'Dari Tanggal: ' . $request->start_date;
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
            $filterInfo[] = 'Sampai Tanggal: ' . $request->end_date;
        }

        // Apply date sorting
        if ($request->filled('sort')) {
            if ($request->sort === 'newest') {
                $query->orderBy('tanggal_pinjam', 'desc');
                $filterInfo[] = 'Urutan: Terbaru';
            } elseif ($request->sort === 'oldest') {
                $query->orderBy('tanggal_pinjam', 'asc');
                $filterInfo[] = 'Urutan: Terlama';
            }
        } else {
            $query->orderBy('tanggal_pinjam', 'desc'); // Default to newest
        }

        $item_loans = $query->get()->chunk(10);
        
        // Generate filename with date
        $filename = 'peminjaman_' . date('Y-m-d') . '.pdf';
        
        // Add filter information to the PDF
        $filterText = empty($filterInfo) ? 'Semua Peminjaman' : implode(', ', $filterInfo);

        $pdf = PDF::loadView('pages.loans.pdf', [
            'item_loans' => $item_loans,
            'filterText' => $filterText
        ]);
        
        return $pdf->download($filename);
    }
    
    public function exportExcel(Request $request)
    {
        if (!in_array(Auth::user()->role, ['super_admin', 'resepsionis'])) {
            abort(403, 'Unauthorized');
        }
        
        $statuses = ['pending', 'pinjam', 'kembali'];
        $query = ItemLoan::with(['item', 'user', 'room.room'])
            ->orderByRaw("FIELD(status, 'pending', 'pinjam', 'kembali') ASC");
            
        // Collect filter info for the filename
        $filterInfo = [];

        // Apply status filter if provided
        if ($request->filled('status') && in_array($request->status, $statuses)) {
            $query->where('status', $request->status);
            $filterInfo[] = 'status-' . $request->status;
        }

        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
            $filterInfo[] = 'dari-' . $request->start_date;
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date);
            $filterInfo[] = 'sampai-' . $request->end_date;
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

        $loans = $query->get();
        
        // Generate filename with date and filters
        $filename = 'peminjaman_' . date('Y-m-d');
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
        
        $callback = function() use ($loans, $filterInfo) {
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
            fputcsv($file, ['No', 'Nama Peminjam', 'Nama Barang', 'Jumlah', 'Status', 'Tanggal Pinjam', 'Tanggal Kembali']);
            
            // Tambahkan data
            foreach ($loans as $index => $loan) {
                fputcsv($file, [
                    $index + 1,
                    $loan->user->nama ?? '-',
                    $loan->item->item_name ?? '-',
                    $loan->jumlah,
                    ucfirst($loan->status),
                    $loan->tanggal_pinjam ? \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d-m-Y') : '-',
                    $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d-m-Y') : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $loan = ItemLoan::with(['user', 'item.category', 'item.room'])->findOrFail($id);
        return view('pages.loans.show', compact('loan'));
    }

    public function return(Request $request, $id)
    {
        $loan = ItemLoan::findOrFail($id);
        if ($loan->status !== 'pinjam') {
            return back()->with('error', 'Item tidak dalam status dipinjam.');
        }

        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'good' => 'required|integer|min:0',
            'broken' => 'required|integer|min:0',
            'lost' => 'required|integer|min:0',
        ]);

        $total = $request->good + $request->broken + $request->lost;
        if ($total != $loan->jumlah) {
            return back()->with('error', 'Barang yang di input tidak sesuai dengan barang yang di pinjam. Total harus sama dengan jumlah yang dipinjam.');
        }

        // Store return photo if provided
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('return-photos', 'public');
        }

        // Update loan record with return details
        $loan->update([
            'status' => 'kembali',
            'tanggal_kembali' => now(),
            'return_photo' => $photoPath,
            'good' => $request->good,
            'broken' => $request->broken,
            'lost' => $request->lost,
        ]);

        // Update item quantities
        $item = $loan->item;
        
        // Add the returned items according to their condition
        // No need to subtract from good_qty here since we already did that when approving the loan
        $item->good_qty += $request->good;
        $item->broken_qty += $request->broken;
        $item->lost_qty += $request->lost;
        
        // Make sure we don't have negative values
        $item->good_qty = max(0, $item->good_qty);
        $item->broken_qty = max(0, $item->broken_qty);
        $item->lost_qty = max(0, $item->lost_qty);
        
        // Save the updated item quantities
        $item->save();

        return redirect()->route('item_loans.index')->with('success', 'Item berhasil dikembalikan.');
    }
}
