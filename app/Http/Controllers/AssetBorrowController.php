<?php

namespace App\Http\Controllers;

use App\Models\ItemLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetBorrowController extends Controller
{
    public function index()
    {
        $itemLoans = ItemLoan::with(['item', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('assets.borrow_index', compact('itemLoans'));
    }

    public function show($id)
    {
        $loan = ItemLoan::findOrFail($id);
        return view('assets.borrow_detail', compact('loan'));
    }

    public function showReturnForm($id)
    {
        $loan = ItemLoan::with(['item'])->findOrFail($id);
        return view('assets.return_confirm', compact('loan'));
    }

    public function confirmReturnSubmit(Request $request, $id)
    {
        $loan = ItemLoan::with(['item'])->findOrFail($id);

        $request->validate([
            'good_qty' => 'required|integer|min:0',
            'broken_qty' => 'required|integer|min:0',
            'lost_qty' => 'required|integer|min:0',
            'return_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $item = $loan->item;

        // Validate total returned items equals borrowed amount
        $totalReturned = $request->good_qty + $request->broken_qty + $request->lost_qty;
        if ($totalReturned != $loan->jumlah) {
            return redirect()->back()
                ->withErrors(['good_qty' => 'Total jumlah barang yang dikembalikan harus sama dengan jumlah yang dipinjam (' . $loan->jumlah . ')'])
                ->withInput();
        }

        // Update item quantities
        $item->good_qty += $request->good_qty;
        $item->broken_qty += $request->broken_qty;
        $item->lost_qty += $request->lost_qty;
        $item->save();

        // Upload return photo if provided
        $photoPath = null;
        if ($request->hasFile('return_photo')) {
            $photoPath = $request->file('return_photo')->store('return-photos', 'public');
            // Make sure the field name matches what ItemLoanController uses
            $loan->return_photo = $photoPath;
        }

        // Update loan status and return date
        $loan->status = 'kembali';
        $loan->tanggal_kembali = now();
        $loan->good = $request->good_qty;
        $loan->broken = $request->broken_qty;
        $loan->lost = $request->lost_qty;
        $loan->save();

        return redirect()->route('assets.borrow.show', $loan->id)
            ->with('success', 'Pengembalian barang berhasil dikonfirmasi.');
    }
}
