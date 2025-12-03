@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active">Detail Produk</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('images/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow">
                    @else
                        <div class="bg-light rounded py-5">
                            <i class="fas fa-cookie-bite fa-10x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Statistik</h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Rating Rata-rata:</span>
                        <strong class="text-warning">
                            {{ number_format($product->averageRating(), 1) }} 
                            <i class="fas fa-star"></i>
                        </strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Total Review:</span>
                        <strong>{{ $product->totalReviews() }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Stok Tersedia:</span>
                        <strong class="{{ $product->stock > 10 ? 'text-success' : 'text-danger' }}">
                            {{ $product->stock }} pcs
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $product->name }}</h4>
                    <div>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Kategori</h6>
                            <span class="badge bg-info fs-6">{{ $product->category ? $product->category->name : 'No Category' }}</span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Harga</h6>
                            <h3 class="text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-muted">Deskripsi</h6>
                    <p>{{ $product->description }}</p>

                    <hr>

                    <h6 class="text-muted">Informasi Tambahan</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="30%">Dibuat pada:</td>
                            <td><strong>{{ $product->created_at->format('d M Y, H:i') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Terakhir diupdate:</td>
                            <td><strong>{{ $product->updated_at->format('d M Y, H:i') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Reviews -->
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Review Pelanggan ({{ $product->totalReviews() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($product->reviews as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <small class="text-muted">{{ $review->created_at->format('d M Y H:i') }}</small>
                                </div>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="mt-2 mb-0">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada review untuk produk ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
