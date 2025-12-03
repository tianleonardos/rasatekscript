@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h2>

    @if(!empty($cart) && count($cart) > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr data-id="{{ $id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item['image'])
                                                    <img src="{{ asset('images/' . $item['image']) }}" 
                                                         width="80" class="rounded me-3" alt="{{ $item['name'] }}">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                        <i class="fas fa-cookie-bite fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                                <strong>{{ $item['name'] }}</strong>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td>
                                            <div class="input-group" style="width: 130px;">
                                                <button class="btn btn-sm btn-outline-secondary btn-minus" type="button">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" class="form-control form-control-sm text-center quantity-input" 
                                                       value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}">
                                                <button class="btn btn-sm btn-outline-secondary btn-plus" type="button">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="subtotal">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <form action="{{ route('cart.clear') }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                        <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
                    </button>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Item:</span>
                            <strong id="total-items">{{ count($cart) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Harga:</span>
                            <strong class="text-primary" id="total-price">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-check-circle"></i> Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h4>Keranjang Belanja Kosong</h4>
            <p>Belum ada produk di keranjang Anda.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update quantity dengan jQuery AJAX
    $('.btn-plus, .btn-minus').click(function() {
        let row = $(this).closest('tr');
        let input = row.find('.quantity-input');
        let currentVal = parseInt(input.val());
        let max = parseInt(input.attr('max'));
        
        if ($(this).hasClass('btn-plus')) {
            if (currentVal < max) {
                input.val(currentVal + 1);
                updateCart(row);
            } else {
                alert('Jumlah melebihi stok tersedia!');
            }
        } else {
            if (currentVal > 1) {
                input.val(currentVal - 1);
                updateCart(row);
            }
        }
    });

    $('.quantity-input').change(function() {
        updateCart($(this).closest('tr'));
    });

    function updateCart(row) {
        let productId = row.data('id');
        let quantity = row.find('.quantity-input').val();

        $.ajax({
            url: `/cart/update/${productId}`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    row.find('.subtotal').text('Rp ' + response.subtotal.toLocaleString('id-ID'));
                    $('#total-price').text('Rp ' + response.total.toLocaleString('id-ID'));
                } else {
                    alert(response.message);
                }
            }
        });
    }
});
</script>
@endpush