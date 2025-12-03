@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Detail Pengguna</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Pengguna</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>

                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <div class="mb-3">
                        @if($user->role === 'admin')
                            <span class="badge bg-danger fs-6">Admin</span>
                        @else
                            <span class="badge bg-info fs-6">Customer</span>
                        @endif
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Telepon:</th>
                            <td>{{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bergabung:</th>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Total Pesanan:</th>
                            <td><span class="badge bg-success">{{ $user->orders->count() }}</span></td>
                        </tr>
                    </table>

                    @if(auth()->user()->role === 'admin' && auth()->id() !== $user->id)
                        <hr>
                        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <div class="mb-2">
                                <select name="role" class="form-select form-select-sm" required>
                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-user-shield"></i> Update Role
                            </button>
                        </form>
                    @endif

                    @if($user->address)
                        <hr>
                        <h6 class="text-start">Alamat:</h6>
                        <p class="text-start text-muted">{{ $user->address }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Pesanan</h5>
                </div>
                <div class="card-body">
                    @forelse($user->orders as $order)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    <div>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'diproses')
                                            <span class="badge bg-info">Diproses</span>
                                        @elseif($order->status == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-2">
                                    @foreach($order->orderItems as $item)
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>{{ $item->product ? $item->product->name : 'Produk tidak tersedia' }} ({{ $item->quantity }}x)</span>
                                            <span class="text-primary fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-end">
                                    <strong>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <p>Belum ada pesanan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
