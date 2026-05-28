@extends('frontend.layout')

@section('title')
    Shop
@endsection

@section('content')
<main class="shop">
    <section>
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <div class="row">
                        @foreach ($shops as $item)
                            @if ($item->regular_price == $item->sale_price)
                                <div class="col-3">
                                    <figure>
                                        <div class="thumbnail border border-success">
                                            <a href="">
                                                <img src="{{ $item->image }}" alt="">
                                            </a>
                                        </div>
                                        <div class="detail">
                                            <div class="price-list">
                                                <div class="price d-none">US 10</div>
                                                <div class="sale-price ">US {{ $item->sale_price }}</div>
                                            </div>
                                            <h5 class="title">{{ $item->product_name }}</h5>
                                            
                                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Add to cart</button>
                                            </form>
                                        </div>
                                    </figure>
                                </div>
                            @else
                                <div class="col-3">
                                    <figure>
                                        <div class="thumbnail border border-success">
                                            <div class="status">
                                                Promotion
                                            </div>
                                            <a href="">
                                                <img src="{{ $item->image }}" alt="">
                                            </a>
                                        </div>
                                        <div class="detail">
                                            <div class="price-list">
                                                <div class="price d-none">US 10</div>
                                                <div class="regular-price "><strike> US {{ $item->regular_price }}</strike></div>
                                                <div class="sale-price ">US {{ $item->sale_price }}</div>
                                            </div>
                                            <h5 class="title">{{ $item->product_name }}</h5>
                                            
                                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Add to cart</button>
                                            </form>
                                        </div>
                                    </figure>
                                </div>
                            @endif
                        @endforeach

                        <div class="col-12">
                            <ul class="pagination">
                                {{ $shops->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-3 filter">
                    <h4 class="title">Category</h4>
                    <ul>
                        <li>
                            <a href="/shop">ALL</a>
                        </li>
                        @foreach ($cate as $item)
                            <li>
                                <a href="{{ route('shop', ['cate' => $item->category_name]) }}">
                                    {{ $item->category_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <h4 class="title mt-4">Price</h4>
                    <div class="block-price mt-4">
                        <a href="{{ route('shop', ['price' => 'max']) }}">High</a>
                        <a href="{{ route('shop', ['price' => 'min']) }}">Low</a>
                    </div>

                    <h4 class="title mt-4">Promotion</h4>
                    <div class="block-price mt-4">
                        <a href="{{ route('shop', ['promotion' => 'true']) }}">Promotion Product</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection