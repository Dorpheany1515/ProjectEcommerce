<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ url('css/frontend/theme.css') }}" rel="stylesheet">
        <link href="{{ url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <header class=" bg-primary-subtle">
            <div class="container">
                    <div class="logo">
                            <a href="/">
                                <h1>E-BOOK</h1>
                            </a>
                    </div>
                
                    <ul class="menu">
                        <li>
                            <a href="/">HOME</a>
                        </li>
                        <li>
                            <a href="shop">SHOP</a>
                        </li>
                        <li>
                            <a href="news">NEWS</a>
                        </li>
                    </ul>
                        <div class="search d-flex gap-2 mb-3">
                            <form action="{{ route('shop') }}" method="get" class="mb-4 d-flex">
                                <input type="text" name="s" class="form-control" placeholder="Search..."
                                    value="{{ request('s') }}">
                                <button type="submit" class="btn btn-outline-primary ms-2">
                                    <img src="{{ asset('uploads/search.png') }}" alt="search" style="width: 24px; height: 24px;">
                                </button>
                            </form>
                    <div class="signup">
                        @auth
                            <button class="btn btn-primary">
                                <a class="text-light text-decoration-none" href="{{ route('logout') }}">Logout</a>
                            </button>
                        @else
                            <button class="btn btn-primary">
                                <a class="text-light text-decoration-none" href="{{ route('login') }}">Login</a>
                            </button>
                            <button class="btn btn-danger">
                                <a class="text-light text-decoration-none" href="{{ route('register') }}">Sign Up</a>
                            </button>
                        @endauth
                    </div>
                </div>

            </div>
            
        </header>
        @yield('content')
        <footer>
            <span>
                AllRight Recieved @ 2023
            </span>
        </footer>

    </body>
    <script src="{{ url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js') }}"></script>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.btn-add-cart').on('click', function(e) {
        e.preventDefault();
        
        let button = $(this);
        let form = button.closest('.add-to-cart-form');
        let url = form.data('url');
        let token = form.find('input[name="_token"]').val();

        // ប្តូរអក្សរលើប៊ូតុងបណ្តោះអាសន្នពេលកំពុងដំណើរការ
        button.prop('disabled', true).text('Adding...');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: token
            },
            success: function(response) {
                // រក្សាប៊ូតុងឲ្យមកសភាពដើម
                button.prop('disabled', false).text('Add to cart');
                
                // បង្ហាញផ្ទាំងសារ Notification តូចមួយស្អាត ដោយមិនបាច់ Reload ទំព័រ
                alert(response.message);
            },
            error: function(xhr) {
                button.prop('disabled', false).text('Add to cart');
                alert('មានបញ្ហាអ្វីមួយបានកើតឡើង ឬលោកអ្នកមិនទាន់បានបើក Database!');
            }
        });
    });
});
</script>