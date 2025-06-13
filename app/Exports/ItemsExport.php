<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Illuminate\Support\Facades\View;

class ItemsExport {
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function sheet($excel) {
        $excel->sheet('Items', function(LaravelExcelWorksheet $sheet) {
            $query = Item::with(['category', 'room.location']);
            
            // Apply filters if they exist
            if ($this->request->has('search') && $this->request->search) {
                $query->where('item_name', 'like', '%' . $this->request->search . '%');
            }
            
            if ($this->request->has('category') && $this->request->category) {
                $query->where('cat_id', $this->request->category);
            }
            
            if ($this->request->has('room') && $this->request->room) {
                $query->where('room_id', $this->request->room);
            }
            
            if ($this->request->has('location') && $this->request->location) {
                $query->whereHas('room', function($q) {
                    $q->where('location_id', $this->request->location);
                });
            }
            
            $items = $query->get();

            $sheet->loadView('pages.items.excel', [
                'items' => $items
            ]);
        });
    }
}
