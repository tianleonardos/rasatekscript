@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.show', $order->id) }}">Order #{{ $order->id }}</a></li>
            <li class="breadcrumb-item active">Review {{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Beri Review untuk {{ $product->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.store', ['order' => $order->id, 'product' => $product->id]) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <label for="star{{ $i }}" title="{{ $i }} star">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Komentar (Opsional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Bagikan pengalaman Anda dengan produk ini...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    justify-content: flex-start;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    cursor: pointer;
    font-size: 2rem;
    color: #ddd;
    margin: 0 2px;
    transition: color 0.2s;
}

/* Highlighting handled by JavaScript */

.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('.star-rating input[type="radio"]');
    const ratingLabels = document.querySelectorAll('.star-rating label');

    function updateStars(numStars) {
        ratingLabels.forEach((label, index) => {
            if (index < numStars) {
                label.style.color = '#ffc107';
            } else {
                label.style.color = '#ddd';
            }
        });
    }

    ratingLabels.forEach((label, index) => {
        label.addEventListener('mouseenter', function() {
            updateStars(index + 1);
        });

        label.addEventListener('mouseleave', function() {
            // Reset to checked value
            const checkedInput = document.querySelector('.star-rating input[type="radio"]:checked');
            if (checkedInput) {
                updateStars(parseInt(checkedInput.value));
            } else {
                updateStars(0);
            }
        });

        label.addEventListener('click', function() {
            const value = this.previousElementSibling.value;
            updateStars(parseInt(value));
        });
    });

    // Initial state
    const checkedInput = document.querySelector('.star-rating input[type="radio"]:checked');
    if (checkedInput) {
        updateStars(parseInt(checkedInput.value));
    }
});
</script>
@endsection
