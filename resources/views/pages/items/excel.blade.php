<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Item</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Ruangan</th>
            <th>Total Qty</th>
            <th>Qty Baik</th>
            <th>Qty Rusak</th>
            <th>Qty Hilang</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->category->cat_name }}</td>
                <td>{{ $item->room->location->name }}</td>
                <td>{{ $item->room->name }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->good_qty }}</td>
                <td>{{ $item->broken_qty }}</td>
                <td>{{ $item->lost_qty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
