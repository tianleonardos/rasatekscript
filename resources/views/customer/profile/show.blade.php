@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Profil Saya') }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> {{ __('Edit Profil') }}
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end fw-bold">{{ __('Nama') }}</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ auth()->user()->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end fw-bold">{{ __('Alamat Email') }}</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end fw-bold">Nomor Telepon</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ auth()->user()->phone ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end fw-bold">{{ __('Peran') }}</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end fw-bold">{{ __('Anggota Sejak') }}</label>
                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ auth()->user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
