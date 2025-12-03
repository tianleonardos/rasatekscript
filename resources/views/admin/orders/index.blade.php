@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-shopping-bag"></i> Kelola Pesanan</h2>

    <!-- Filter Status -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.orders.index') }}" 
                   class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semua
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                   class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                    Pending
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'diproses']) }}" 
                   class="btn {{ request('status') == 'diproses' ? 'btn-info' : 'btn-outline-info' }}">
                    Diproses
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'selesai']) }}" 
                   class="btn {{ request('status') == 'selesai' ? 'btn-success' : 'btn-outline-success' }}">
                    Selesai
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'dibatalkan']) }}" 
                   class="btn {{ request('status') == 'dibatalkan' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Dibatalkan
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Jumlah Item</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <strong>{{ $order->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $order->user ? $order->user->email : 'N/A' }}</small>
                                </td>
                                <td>{{ $order->orderItems->count() }} item</td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->status == 'diproses')
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteOrder({{ $order->id }})" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $order->id }}" 
                                          action="{{ route('admin.orders.destroy', $order->id) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                    <p>Belum ada pesanan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function deleteOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
        document.getElementById('delete-form-' + orderId).submit();
    }
}
</script>
@endsection
