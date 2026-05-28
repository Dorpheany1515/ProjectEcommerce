@extends('frontend.layout')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Shopping Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($cart) > 0)
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                    <tr>
                        <td><img src="{{ $item['image'] }}" width="60" alt=""></td>
                        <td>{{ $item['name'] }}</td>
                        <td>US ${{ number_format($item['price'], 2) }}</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width: 60px;" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>US ${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-4">
            <h4>Total Price: <span class="text-success">US ${{ number_format($total, 2) }}</span></h4>
            <br>
            <a href="{{ route('checkout') }}" class="btn btn-success btn-lg">Proceed to Checkout</a>
        </div>
    @else
        <div class="alert alert-warning text-center">
            Your cart is empty! <a href="/shop">Go back to Shop</a>
        </div>
    @endif
</div>
@endsection