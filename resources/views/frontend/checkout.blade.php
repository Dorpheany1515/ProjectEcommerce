<!-- resources/views/frontend/checkout.blade.php -->
@extends('frontend.layout')
@section('content')
<div class="container py-5">
    <h3 class="main-title">CHECKOUT</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">Order Summary</div>
                <div class="card-body">
                    @foreach($cart as $id => $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                        <span>US {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span class="text-danger">US {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <!-- Shared Fields -->
                    <div id="shared-fields">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Payment Method Tabs -->
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#cash">Cash on Delivery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#stripe">Credit Card</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Cash on Delivery -->
                        <div class="tab-pane fade show active" id="cash">
                            <p class="text-muted">Pay when your order arrives.</p>
                            <form action="{{ route('payment.cash') }}" method="POST">
                                @csrf
                                <input type="hidden" name="name" class="sync-field" data-target="name">
                                <input type="hidden" name="email" class="sync-field" data-target="email">
                                <input type="hidden" name="phone" class="sync-field" data-target="phone">
                                <input type="hidden" name="address" class="sync-field" data-target="address">
                                <button type="submit" class="btn btn-success w-100">Place Order (Cash)</button>
                            </form>
                        </div>

                        <!-- Stripe -->
                        <div class="tab-pane fade" id="stripe">
                            <form action="{{ route('payment.stripe') }}" method="POST" id="stripe-form">
                                @csrf
                                <input type="hidden" name="name" class="sync-field" data-target="name">
                                <input type="hidden" name="email" class="sync-field" data-target="email">
                                <input type="hidden" name="phone" class="sync-field" data-target="phone">
                                <input type="hidden" name="address" class="sync-field" data-target="address">
                                <div class="mb-3">
                                    <label>Card Details</label>
                                    <div id="card-element" class="form-control py-2"></div>
                                    <div id="card-errors" class="text-danger mt-1"></div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Pay US {{ number_format($total, 2) }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Sync hidden fields from shared inputs
    document.querySelectorAll('#shared-fields input, #shared-fields textarea').forEach(input => {
        input.addEventListener('input', () => {
            document.querySelectorAll(`.sync-field[data-target="${input.id}"]`).forEach(hidden => {
                hidden.value = input.value;
            });
        });
    });

    // Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    document.getElementById('stripe-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const { token, error } = await stripe.createToken(cardElement);
        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            e.target.appendChild(hiddenInput);
            e.target.submit();
        }
    });
</script>
@endsection