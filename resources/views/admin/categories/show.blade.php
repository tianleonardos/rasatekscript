@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                    <li class="breadcrumb-item active">Detail Kategori</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $category->name }}</h4>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                            <h6 class="text-muted">Total Produk</h6>
                            <h3 class="text-primary mb-0">{{ $category->products_count }} produk</h3>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status</h6>
                            <span class="badge bg-success fs-6">Aktif</span>
                        </div>
                    </div>

                    @if($category->description)
                        <hr>
                        <h6 class="text-muted">Deskripsi</h6>
                        <p>{{ $category->description }}</p>
                    @endif

                    <hr>

                    <h6 class="text-muted">Informasi Tambahan</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="30%">Dibuat pada:</td>
                            <td><strong>{{ $category->created_at->format('d M Y, H:i') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Terakhir diupdate:</td>
                            <td><strong>{{ $category->updated_at->format('d M Y, H:i') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Statistik</h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Total Produk:</span>
                        <strong>{{ $category->products_count }}</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Produk Terbaru:</span>
                        <strong>{{ $category->products->count() }}</strong>
                    </div>
                </div>
            </div>

            @if($category->products->count() > 0)
                <div class="card mt-3">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-cookie"></i> Produk Terbaru</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($category->products as $product)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <img src="{{ asset('images/' . $product->image) }}"
                                             width="40" class="rounded me-3" alt="{{ $product->name }}">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-cookie-bite text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <strong class="text-primary">{{ $product->name }}</strong><br>
                                        <small class="text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                    </div>
                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}"
                           class="btn btn-primary btn-sm">
                            Lihat Semua Produk
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
