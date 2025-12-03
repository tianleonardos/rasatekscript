@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-box"></i> Pesanan Saya</h2>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pesanan #{{ $order->id }}</h5>
                            <span class="badge
                                @if($order->status == 'pending') bg-warning text-dark
                                @elseif($order->status == 'diproses') bg-info
                                @elseif($order->status == 'selesai') bg-success
                                @else bg-danger @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Total:</strong></p>
                                    <p class="text-primary fw-bold fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Tanggal:</strong></p>
                                    <p>{{ $order->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Produk:</strong>
                                <ul class="list-unstyled mb-0">
                                    @foreach($order->orderItems->take(2) as $item)
                                        <li>• {{ $item->product ? $item->product->name : 'Produk tidak tersedia' }} ({{ $item->quantity }}x)</li>
                                    @endforeach
                                    @if($order->orderItems->count() > 2)
                                        <li class="text-muted">+{{ $order->orderItems->count() - 2 }} produk lainnya</li>
                                    @endif
                                </ul>
                            </div>

                            <div class="mb-3">
                                <strong>Metode Pembayaran:</strong>
                                @if($order->payment_method == 'tunai')
                                    <span class="badge bg-success">
                                        <i class="fas fa-money-bill-wave"></i> Tunai
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-mobile-alt"></i> E-Wallet
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada pesanan</h4>
            <p class="text-muted">Anda belum melakukan pemesanan apapun.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
