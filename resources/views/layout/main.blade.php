<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EatsWay</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @include('layout.header')
    @yield('content')
    <script>
        function updateHeaderCartCount() {
            const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

            // .length ang gagamitin para makuha kung ilang unique items ang nasa cart
            const uniqueItemsCount = cart.length;

            const headerCount = document.getElementById('header-cart-count');
            if (headerCount) {
                if (uniqueItemsCount > 0) {
                    headerCount.innerText = `(${uniqueItemsCount})`;
                    headerCount.classList.remove('d-none');
                } else {
                    headerCount.classList.add('d-none');
                }
            }
        }
    </script>
    @include('layout.footer')
</body>

</html>