@extends('layouts.app')

@section('content')


<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="p-5 text-center bg-gradient rounded-3" style="background: brown;">
                <h1 class="text-white fw-bold mb-3">🍪 Selamat Datang di RasaTekScript</h1>
                <p class="text-white fs-5">Cookies homemade terbaik dengan bahan premium pilihan</p>
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Kategori</h4>
            <div class="btn-group" role="group">
                <a href="{{ route('home') }}" class="btn {{ !isset($selectedCategory) ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('category.filter', $category->id) }}" 
                       class="btn {{ isset($selectedCategory) && $selectedCategory->id == $category->id ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        <div class="col-12 mb-3">
            <h4>{{ isset($selectedCategory) ? $selectedCategory->name : 'Semua Produk' }}</h4>
        </div>
        
        @forelse($products as $product)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ asset('images/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    @else
                        <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-cookie-bite fa-5x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <span class="badge badge-custom mb-2 align-self-start">{{ $product->category->name }}</span>
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                        
                        <div class="mb-2">
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= floor($product->averageRating()) ? '' : '-half-alt' }}"></i>
                                @endfor
                                <small class="text-muted">({{ $product->totalReviews() }})</small>
                            </div>
                        </div>
                        
                        <h5 class="text-primary fw-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                        <p class="text-muted small mb-3">Stok: {{ $product->stock }}</p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            
                            @auth
                                @if(auth()->user()->isCustomer())
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm w-100" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-cart-plus"></i> 
                                            {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada produk tersedia.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection