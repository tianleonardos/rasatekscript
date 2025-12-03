@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 mb-4">
            @if($product->image)
                <img src="{{ asset('images/' . $product->image) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
            @else
                <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-cookie-bite fa-10x text-muted"></i>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <span class="badge badge-custom mb-2">{{ $product->category->name }}</span>
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                <div class="text-warning fs-4">
                    @if($product->totalReviews() > 0)
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= floor($averageRating) ? '' : ($i - 0.5 <= $averageRating ? '-half-alt' : ' text-muted') }}"></i>
                        @endfor
                        <span class="text-dark fs-6">({{ number_format($averageRating, 1) }}) - {{ $product->totalReviews() }} review</span>
                    @else
                        <span class="text-muted">Belum ada rating</span>
                    @endif
                </div>
            </div>

            <p class="lead mb-4">{{ $product->description }}</p>

            <h2 class="text-primary fw-bold mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
            
            <p class="mb-4">
                <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                    Stok: {{ $product->stock }} pcs
                </span>
            </p>

            @auth
                @if(auth()->user()->isCustomer())
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg" {{ $product->stock == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus"></i> 
                            {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                </a>
            @endauth
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Review Pelanggan</h3>
            
            @forelse($product->reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $review->user->name }}</h5>
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-muted' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        @if($review->comment)
                            <p class="card-text mb-0">{{ $review->comment }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada review untuk produk ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection