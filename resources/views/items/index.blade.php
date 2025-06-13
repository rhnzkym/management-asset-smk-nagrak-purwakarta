                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->category->cat_name }}</td>
                            <td>{{ $item->room->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->conditions }}</td>
                            <td>
                                @if($item->is_borrowable)
                                    <span class="badge bg-success">Bisa Dipinjam</span>
                                @else
                                    <span class="badge bg-danger">Tidak Bisa Dipinjam</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('items.toggle-borrowable', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $item->is_borrowable ? 'btn-danger' : 'btn-success' }}">
                                            <i class="fas {{ $item->is_borrowable ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td> 